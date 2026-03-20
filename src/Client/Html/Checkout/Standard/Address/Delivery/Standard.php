<?php

declare (strict_types=1);
/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2026
 * @package Client
 * @subpackage Html
 */
namespace Aimeos\Client\Html\Checkout\Standard\Address\Delivery;

/**
 * Default implementation of checkout billing address HTML client.
 *
 * @package Client
 * @subpackage Html
 */
class Standard extends \Aimeos\Client\Html\Common\Client\Factory\Base implements \Aimeos\Client\Html\Common\Client\Factory\Iface
{
    /**
     * Stores the given or fetched billing address in the basket.
     *
     * A view must be available and this method doesn't generate any output
     * besides setting view variables if necessary.
     */
    public function init(): void
    {
        $context = $this->context();
        $view = $this->view();
        try {
            if (($id = $view->param('ca_delivery_delete', null)) !== null) {
                $cntl = \Aimeos\Controller\Frontend::create($context, 'customer');
                if (($item = $cntl->uses(['customer/address'])->get()->get_address_item($id)) !== null) {
                    $cntl->delete_address_item($item)->store();
                    throw new \Aimeos\Client\Html\Exception(sprintf('Delivery address deleted successfully'));
                }
            }
            // only start if there's something to do
            if ($view->param('ca_deliveryoption') === null) {
                return;
            }
            $this->set_address($view);
            parent::init();
        } catch (\Aimeos\Controller\Frontend\Exception $e) {
            $view->address_delivery_error = $e->get_error_list();
            throw $e;
        }
    }
    /**
     * Checks the address fields for missing data and sanitizes the given parameter list.
     *
     * @param array $params Associative list of address keys (order.address.* or customer.address.*) and their values
     * @return array List of missing field names
     */
    protected function check_fields(array $params): array
    {
        $view = $this->view();
        /** client/html/common/address/delivery/mandatory
         * List of delivery address input fields that are required
         *
         * You can configure the list of delivery address fields that are
         * necessary and must be filled by the customer before he can
         * continue the checkout process. Available field keys are:
         *
         * * company
         * * vatid
         * * salutation
         * * firstname
         * * lastname
         * * address1
         * * address2
         * * address3
         * * postal
         * * city
         * * state
         * * languageid
         * * countryid
         * * telephone
         * * telefax
         * * email
         * * website
         *
         * @param array List of field keys
         * @since 2015.02
         * @see client/html/checkout/standard/address/delivery/disable-new
         * @see client/html/common/address/delivery/optional
         * @see client/html/common/address/delivery/hidden
         * @see client/html/common/address/salutations
         * @see client/html/common/address/validate
         * @see common/countries
         * @see common/states
         */
        $mandatory = $view->config('client/html/common/address/delivery/mandatory', []);
        /** client/html/common/address/delivery/optional
         * List of delivery address input fields that are optional
         *
         * You can configure the list of delivery address fields that
         * customers can fill but don't have to before they can
         * continue the checkout process. Available field keys are:
         *
         * * company
         * * vatid
         * * salutation
         * * firstname
         * * lastname
         * * address1
         * * address2
         * * address3
         * * postal
         * * city
         * * state
         * * languageid
         * * countryid
         * * telephone
         * * telefax
         * * email
         * * website
         * * nostore
         *
         * Using the "nostore" field displays the option to avoid storing the
         * delivery address permanently in the customer account.
         *
         * @param array List of field keys
         * @since 2015.02
         * @see client/html/checkout/standard/address/delivery/disable-new
         * @see client/html/common/address/delivery/mandatory
         * @see client/html/common/address/delivery/hidden
         * @see client/html/common/address/salutations
         * @see client/html/common/address/validate
         * @see common/countries
         * @see common/states
         */
        $optional = $view->config('client/html/common/address/delivery/optional', []);
        /** client/html/common/address/delivery/hidden
         * List of delivery address input fields that are optional
         *
         * You can configure the list of delivery address fields that
         * are hidden when a customer enters his delivery address.
         * Available field keys are:
         *
         * * company
         * * vatid
         * * salutation
         * * firstname
         * * lastname
         * * address1
         * * address2
         * * address3
         * * postal
         * * city
         * * state
         * * languageid
         * * countryid
         * * telephone
         * * telefax
         * * email
         * * website
         *
         * Caution: Only hide fields that don't require any input
         *
         * @param array List of field keys
         * @since 2015.02
         * @see client/html/checkout/standard/address/delivery/disable-new
         * @see client/html/common/address/delivery/mandatory
         * @see client/html/common/address/delivery/optional
         * @see client/html/common/address/salutations
         * @see common/countries
         * @see common/states
         */
        $hidden = $view->config('client/html/common/address/delivery/hidden', []);
        /** client/html/common/address/validate
         *
         * @see client/html/common/address/delivery/mandatory
         * @see client/html/common/address/delivery/optional
         */
        $all_fields = array_flip(array_merge($mandatory, $optional, $hidden));
        $invalid = $this->validate_fields($params, $all_fields);
        $this->check_salutation($params, $mandatory);
        $msg = $view->translate('client', 'Delivery address part "%1$s" is invalid');
        foreach ($invalid as $key => $name) {
            $invalid[$key] = sprintf($msg, $name);
        }
        $msg = $view->translate('client', 'Delivery address part "%1$s" is missing');
        foreach ($mandatory as $key) {
            if (!isset($params['order.address.' . $key]) || $params['order.address.' . $key] == '') {
                $invalid[$key] = sprintf($msg, $key);
            }
        }
        return $invalid;
    }
    /**
     * Additional checks for the salutation
     *
     * @param array $params Associative list of address keys (order.address.*) and their values
     * @param array &$mandatory List of mandatory field names
     * @since 2016.05
     */
    protected function check_salutation(array $params, array &$mandatory)
    {
        if (isset($params['order.address.salutation']) && $params['order.address.salutation'] === 'company' && in_array('company', $mandatory) === false) {
            $mandatory[] = 'company';
        }
    }
    /**
     * Returns the address as string
     *
     * @param \Aimeos\Base\View\Iface $view The view object which generates the HTML output
     * @param \Aimeos\MShop\Order\Item\Address\Iface $addr Order address item
     * @return string Address as string
     */
    protected function get_address_string(\Aimeos\Base\View\Iface $view, \Aimeos\M_Shop\Order\Item\Address\Iface $addr): ?string
    {
        return preg_replace("/\n+/m", "\n", trim(sprintf(
            /// Address format with company (%1$s), salutation (%2$s), title (%3$s), first name (%4$s), last name (%5$s),
            /// address part one (%6$s, e.g street), address part two (%7$s, e.g house number), address part three (%8$s, e.g additional information),
            /// postal/zip code (%9$s), city (%10$s), state (%11$s), country (%12$s), language (%13$s),
            /// e-mail (%14$s), phone (%15$s), facsimile/telefax (%16$s), web site (%17$s), vatid (%18$s)
            $view->translate('client', '%1$s
%2$s %3$s %4$s %5$s
%6$s %7$s
%8$s
%9$s %10$s
%11$s
%12$s
%13$s
%14$s
%15$s
%16$s
%17$s
%18$s
'),
            $addr->get_company(),
            $view->translate('mshop/code', (string) $addr->get_salutation()),
            $addr->get_title(),
            $addr->get_first_name(),
            $addr->get_last_name(),
            $addr->get_address1(),
            $addr->get_address2(),
            $addr->get_address3(),
            $addr->get_postal(),
            $addr->get_city(),
            $addr->get_state(),
            $view->translate('country', (string) $addr->get_country_id()),
            $view->translate('language', (string) $addr->get_language_id()),
            $addr->get_email(),
            $addr->get_telephone(),
            $addr->get_telefax(),
            $addr->get_website(),
            $addr->get_vat_id()
        )));
    }
    /**
     * Sets the new address
     *
     * @param \Aimeos\Base\View\Iface $view View object
     * @throws \Aimeos\Client\Html\Exception If an error occurs
     * @since 2016.05
     */
    protected function set_address(\Aimeos\Base\View\Iface $view)
    {
        $address = null;
        $context = $this->context();
        $ctrl = \Aimeos\Controller\Frontend::create($context, 'basket');
        /** client/html/checkout/standard/address/delivery/disable-new
         * Disables the option to enter a different delivery address for an order
         *
         * Besides the billing address, customers can usually enter a different
         * delivery address as well. To suppress displaying the form fields for
         * a delivery address, you can set this configuration option to "1".
         *
         * @param boolean A value of "1" to disable, "0" enables the delivery address form
         * @since 2015.02
         * @see client/html/common/address/salutations
         * @see client/html/common/address/delivery/mandatory
         * @see client/html/common/address/delivery/optional
         * @see client/html/common/address/delivery/hidden
         */
        $disable = $view->config('client/html/checkout/standard/address/delivery/disable-new', false);
        $type = \Aimeos\M_Shop\Order\Item\Address\Base::TYPE_DELIVERY;
        if (($option = $view->param('ca_deliveryoption', 'null')) === 'null' && $disable === false) {
            // new address
            $params = $view->param('ca_delivery', []);
            if (($view->address_delivery_error = $this->check_fields($params)) !== []) {
                throw new \Aimeos\Client\Html\Exception(sprintf('At least one delivery address part is missing or invalid'));
            }
            $ctrl->add_address($type, $params, 0);
        } elseif (($option = $view->param('ca_deliveryoption')) !== 'like') {
            // existing address
            $params = $view->param('ca_delivery_' . $option, []);
            if (!empty($params) && ($view->address_delivery_error = $this->check_fields($params)) !== []) {
                throw new \Aimeos\Client\Html\Exception(sprintf('At least one delivery address part is missing or invalid'));
            }
            $cust_cntl = \Aimeos\Controller\Frontend::create($context, 'customer');
            if (($address = $cust_cntl->uses(['customer/address'])->get()->get_address_item($option)) !== null) {
                $params = array_replace($address->to_array(), $params + ['order.address.addressid' => $option]);
                $addr = $ctrl->add_address($type, $params, 0)->get()->get_address($type, 0);
                // sanitize address first
                $cust_cntl->add_address_item($address->copy_from($addr), $option)->store();
                // update existing address
            } else {
                $ctrl->add_address($type, $params, 0);
            }
        } else {
            $ctrl->delete_address($type);
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
        $manager = \Aimeos\M_Shop::create($context, 'order');
        $basket_cntl = \Aimeos\Controller\Frontend::create($context, 'basket');
        $addr_strings = $addr_values = [];
        $addr_map = map($basket_cntl->get()->get_address('delivery'));
        foreach ($view->get('addressDeliveryItems', []) as $id => $address) {
            $params = $view->param('ca_delivery_' . $id, []);
            $basket_values = $addr_map->get($id, map())->to_array();
            $addr = $manager->create_address()->copy_from($address)->from_array($basket_values)->from_array($params);
            $addr_strings[$id] = $this->get_address_string($view, $addr);
            $addr_values[$id] = $addr->to_array();
        }
        $values = !$addr_map->is_empty() ? $addr_map->first()->to_array() : [];
        $values = array_merge($values, $view->param('ca_delivery', []));
        $addr_new = $manager->create_address()->from_array($values);
        $addr_string_new = $this->get_address_string($view, $addr_new);
        $option = $addr_new->get_address_id() ?: ($addr_map->is_empty() ? 'like' : 'null');
        $view->address_delivery_option = $view->param('ca_deliveryoption', $option);
        $view->address_delivery_values_new = $addr_new->to_array();
        $view->address_delivery_string_new = $addr_string_new;
        $view->address_delivery_strings = $addr_strings;
        $view->address_delivery_values = $addr_values;
        $view->address_delivery_css = $this->css_delivery();
        return parent::data($view, $tags, $expire);
    }
    /**
     * Returns the CSS classes for the delivery address fields
     *
     * @return array Associative list of CSS classes for the delivery address fields
     */
    protected function css_delivery(): array
    {
        $config = $this->context()->config();
        $mandatory = $config->get('client/html/common/address/delivery/mandatory', []);
        $optional = $config->get('client/html/common/address/delivery/optional', []);
        $hidden = $config->get('client/html/common/address/delivery/hidden', []);
        $css = [];
        foreach ($mandatory as $name) {
            $css[$name][] = 'mandatory';
        }
        foreach ($optional as $name) {
            $css[$name][] = 'optional';
        }
        foreach ($hidden as $name) {
            $css[$name][] = 'hidden';
        }
        return $css;
    }
    /**
     * Validate the address key/value pairs using regular expressions
     *
     * @param array &$params Associative list of address keys (order.address.*) and their values
     * @param array $fields List of field names to validate
     * @return array List of invalid address keys
     * @since 2016.05
     */
    protected function validate_fields(array $params, array $fields): array
    {
        $config = $this->context()->config();
        /** client/html/common/address/validate/company
         * Regular expression to check the "company" address value
         *
         * @see client/html/common/address/validate
         */
        /** client/html/common/address/validate/vatid
         * Regular expression to check the "vatid" address value
         *
         * @see client/html/common/address/validate
         */
        /** client/html/common/address/validate/salutation
         * Regular expression to check the "salutation" address value
         *
         * @see client/html/common/address/validate
         */
        /** client/html/common/address/validate/firstname
         * Regular expression to check the "firstname" address value
         *
         * @see client/html/common/address/validate
         */
        /** client/html/common/address/validate/lastname
         * Regular expression to check the "lastname" address value
         *
         * @see client/html/common/address/validate
         */
        /** client/html/common/address/validate/address1
         * Regular expression to check the "address1" address value
         *
         * @see client/html/common/address/validate
         */
        /** client/html/common/address/validate/address2
         * Regular expression to check the "address2" address value
         *
         * @see client/html/common/address/validate
         */
        /** client/html/common/address/validate/address3
         * Regular expression to check the "address3" address value
         *
         * @see client/html/common/address/validate
         */
        /** client/html/common/address/validate/postal
         * Regular expression to check the "postal" address value
         *
         * @see client/html/common/address/validate
         */
        /** client/html/common/address/validate/city
         * Regular expression to check the "city" address value
         *
         * @see client/html/common/address/validate
         */
        /** client/html/common/address/validate/state
         * Regular expression to check the "state" address value
         *
         * @see client/html/common/address/validate
         */
        /** client/html/common/address/validate/languageid
         * Regular expression to check the "languageid" address value
         *
         * @see client/html/common/address/validate
         */
        /** client/html/common/address/validate/countryid
         * Regular expression to check the "countryid" address value
         *
         * @see client/html/common/address/validate
         */
        /** client/html/common/address/validate/telephone
         * Regular expression to check the "telephone" address value
         *
         * @see client/html/common/address/validate
         */
        /** client/html/common/address/validate/telefax
         * Regular expression to check the "telefax" address value
         *
         * @see client/html/common/address/validate
         */
        /** client/html/common/address/validate/email
         * Regular expression to check the "email" address value
         *
         * @see client/html/common/address/validate
         */
        /** client/html/common/address/validate/website
         * Regular expression to check the "website" address value
         *
         * @see client/html/common/address/validate
         */
        $invalid = [];
        foreach ($params as $key => $value) {
            $name = ($pos = strrpos($key, '.')) ? substr($key, $pos + 1) : $key;
            if (isset($fields[$name])) {
                $regex = $config->get('client/html/common/address/validate/' . $name);
                if ($regex && preg_match('/' . $regex . '/', $value) !== 1) {
                    $invalid[$name] = $name;
                }
            }
        }
        return $invalid;
    }
    /** client/html/checkout/standard/address/delivery/template-body
     * Relative path to the HTML body template of the checkout standard address delivery client.
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
     * @see client/html/checkout/standard/address/delivery/template-header
     */
}