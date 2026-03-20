<?php

declare (strict_types=1);
/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2026
 * @package Client
 * @subpackage Html
 */
namespace Aimeos\Client\Html\Checkout\Standard\Process\Account;

/**
 * Default implementation of checkout process account HTML client
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
        return '';
    }
    /**
     * Processes the input, e.g. provides the account form.
     *
     * A view must be available and this method doesn't generate any output
     * besides setting view variables.
     */
    public function init(): void
    {
        $context = $this->context();
        try {
            $type = \Aimeos\M_Shop\Order\Item\Address\Base::TYPE_PAYMENT;
            $addresses = \Aimeos\Controller\Frontend::create($context, 'basket')->get()->get_address($type);
            if ($context->user() === null && ($address = current($addresses)) !== false) {
                $create = (bool) $this->view()->param('cs_option_account');
                $context->set_user($this->get_customer($address, $create));
            }
        } catch (\Exception $e) {
            $msg = sprintf('Unable to create an account: %1$s', $e->get_message());
            $context->logger()->notice($msg, 'client/html');
        }
        parent::init();
    }
    /**
     * Creates a new account (if necessary) and returns its customer ID
     *
     * @param \Aimeos\MShop\Common\Item\Address\Iface $addr Address object from order
     * @param bool $new True to create the customer if it doesn't exist, false if not
     * @return \Aimeos\MShop\Customer\Item\Iface|null Unique customer ID or null if no customer is available
     */
    protected function get_customer(\Aimeos\M_Shop\Common\Item\Address\Iface $addr, bool $new): ?\Aimeos\M_Shop\Customer\Item\Iface
    {
        $context = $this->context();
        $cntl = \Aimeos\Controller\Frontend::create($context, 'customer');
        try {
            $customer = $cntl->find($addr->get_email());
        } catch (\Exception) {
            $customer = $new ? $cntl->add($addr->to_array())->store()->get() : null;
        }
        return $customer;
    }
}