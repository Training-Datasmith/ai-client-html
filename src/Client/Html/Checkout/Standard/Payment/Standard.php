<?php

declare (strict_types=1);
/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2015-2026
 * @package Client
 * @subpackage Html
 */
namespace Aimeos\Client\Html\Checkout\Standard\Payment;

// Strings for translation
sprintf('payment');
/**
 * Default implementation of checkout payment HTML client.
 *
 * @package Client
 * @subpackage Html
 */
class Standard extends \Aimeos\Client\Html\Common\Client\Factory\Base implements \Aimeos\Client\Html\Common\Client\Factory\Iface
{
    /**
     * Returns the HTML code for insertion into the body.
     *
     * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
     * @return string HTML code
     */
    public function body(string $uid = ''): string
    {
        $view = $this->view();
        $step = $view->get('standardStepActive');
        $onepage = $view->config('client/html/checkout/standard/onepage', []);
        if ($step != 'payment' && !(in_array('payment', $onepage) && in_array($step, $onepage))) {
            return '';
        }
        return parent::body($uid);
    }
    /**
     * Processes the input, e.g. store given values.
     *
     * A view must be available and this method doesn't generate any output
     * besides setting view variables.
     */
    public function init(): void
    {
        $view = $this->view();
        $context = $this->context();
        try {
            $basket_ctrl = \Aimeos\Controller\Frontend::create($context, 'basket');
            $serv_ctrl = \Aimeos\Controller\Frontend::create($context, 'service')->uses(['media', 'price', 'text']);
            // only start if there's something to do
            if (($service_ids = $view->param('c_paymentoption', null)) !== null) {
                $basket_ctrl->delete_service('payment');
                foreach ((array) $service_ids as $idx => $id) {
                    try {
                        $basket_ctrl->add_service($serv_ctrl->get($id), $view->param('c_payment/' . $id, []), $idx);
                    } catch (\Aimeos\Controller\Frontend\Basket\Exception $e) {
                        $view->delivery_error = $e->get_errors();
                        $view->errors = array_merge($view->get('errors', []), $e->get_errors());
                        throw $e;
                    }
                }
            }
            parent::init();
            if (!isset($view->standard_step_active) && !$this->call('isAvailable', $basket_ctrl->get())) {
                $view->standard_step_active = 'payment';
            }
        } catch (\Exception $e) {
            $view->standard_step_active = 'payment';
            throw $e;
        }
    }
    /**
     * Sets the necessary parameter values in the view.
     *
     * @param \Aimeos\Base\View\Iface $view The view object which generates the HTML output
     * @param array &$tags Result array for the list of tags that are associated to the output
     * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
     * @return \Aimeos\Base\View\Iface Modified view object
     */
    public function data(\Aimeos\Base\View\Iface $view, array &$tags = [], ?string &$expire = null): \Aimeos\Base\View\Iface
    {
        $context = $this->context();
        $domains = ['media', 'price', 'text'];
        /** client/html/checkout/standard/payment/domains
         * List of domain names whose items should be available in the checkout payment templates
         *
         * The templates rendering checkout payment related data usually add the
         * images, prices and texts associated to each item. If you want to display
         * additional content like the attributes, you can configure your own list
         * of domains (attribute, media, price, text, etc. are domains) whose items
         * are fetched from the storage.
         *
         * @param array List of domain names
         * @since 2019.04
         * @see client/html/checkout/standard/delivery/domains
         */
        $domains = $context->config()->get('client/html/checkout/standard/payment/domains', $domains);
        $basket_cntl = \Aimeos\Controller\Frontend::create($context, 'basket');
        $service_cntl = \Aimeos\Controller\Frontend::create($context, 'service');
        $services = [];
        $basket = $basket_cntl->get();
        $providers = $service_cntl->uses($domains)->type('payment')->get_providers();
        $order_services = map($basket->get_service('payment'))->col(null, 'order.service.serviceid');
        foreach ($providers as $id => $provider) {
            if ($provider->is_available($basket) === true) {
                $attr = $provider->get_config_fe($basket);
                if ($oservice = $order_services->get($id)) {
                    foreach ($attr as $key => $item) {
                        $value = is_array($item->get_default()) ? key($item->get_default()) : $item->get_default();
                        $value = $oservice->get_attribute($key) ?: $value;
                        $item->value = $oservice->get_attribute($key, 'hidden') ?: $value;
                    }
                } else {
                    foreach ($attr as $item) {
                        $item->value = is_array($item->get_default()) ? key($item->get_default()) : $item->get_default();
                    }
                }
                $services[$id] = $provider->get_service_item()->set('attributes', $attr)->set('price', $provider->calc_price($basket));
            }
        }
        $view->payment_services = $services;
        $view->payment_option = $view->param('c_paymentoption', $order_services->first_key() ?: key($services));
        return parent::data($view, $tags, $expire);
    }
    /**
     * Tests if an item is available and the step can be skipped
     *
     * @param \Aimeos\MShop\Order\Item\Iface $basket Basket object
     * @return bool TRUE if step can be skipped, FALSE if not
     */
    protected function is_available(\Aimeos\M_Shop\Order\Item\Iface $basket): bool
    {
        return !empty($basket->get_service('payment'));
    }
    /** client/html/checkout/standard/payment/template-body
     * Relative path to the HTML body template of the checkout standard payment client.
     *
     * The template file contains the HTML code and processing instructions
     * to generate the result shown in the body of the frontend. The
     * configuration string is the path to the template file relative
     * to the templates directory (usually in templates/client/html).
     *
     * You can overwrite the template file configuration in extensions and
     * provide alternative templates. These alternative templates should be
     * named like the default one but suffixed by
     * an unique name. You may use the name of your project for this. If
     * you've implemented an alternative client class as well, it
     * should be suffixed by the name of the new class.
     *
     * @param string Relative path to the template creating code for the HTML page body
     * @since 2014.03
     * @see client/html/checkout/standard/payment/template-header
     */
}