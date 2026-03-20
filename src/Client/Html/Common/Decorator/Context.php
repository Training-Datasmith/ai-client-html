<?php

declare (strict_types=1);
/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2020-2026
 * @package Client
 * @subpackage Html
 */
namespace Aimeos\Client\Html\Common\Decorator;

/**
 * Provides context data for html client decorators.
 *
 * @package Client
 * @subpackage Html
 */
class Context extends Base implements Iface
{
    /**
     * Adds the data to the view object required by the templates
     *
     * @param \Aimeos\Base\View\Iface $view The view object which generates the HTML output
     * @param array &$tags Result array for the list of tags that are associated to the output
     * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
     * @return \Aimeos\Base\View\Iface The view object with the data required by the templates
     * @since 2020.07
     */
    public function data(\Aimeos\Base\View\Iface $view, array &$tags = [], ?string &$expire = null): \Aimeos\Base\View\Iface
    {
        $context = $this->context();
        $locale = $context->locale();
        $view->context_language = $locale->get_language_id();
        $view->context_currency = $locale->get_currency_id();
        $view->context_site_icon = $locale->get_site_item()->get_icon();
        $view->context_site_logo = $locale->get_site_item()->get_logo();
        $view->context_site_logos = $locale->get_site_item()->get_logos();
        $view->context_site = $locale->get_site_item()->get_code();
        $view->context_site_label = $locale->get_site_item()->get_label();
        $view->context_site_theme = $locale->get_site_item()->get_theme();
        $view->context_site_id = $locale->get_site_id();
        $view->context_nonce = $context->nonce();
        return $this->client()->data($view, $tags, $expire);
    }
}