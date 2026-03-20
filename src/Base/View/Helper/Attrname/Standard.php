<?php

declare (strict_types=1);
/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2020-2026
 * @package MW
 * @subpackage View
 */
namespace Aimeos\Base\View\Helper\Attrname;

/**
 * View helper class for creating an HTML image tag
 *
 * @package MW
 * @subpackage View
 */
class Standard extends \Aimeos\Base\View\Helper\Base implements \Aimeos\Base\View\Helper\Attrname\Iface
{
    /**
     * Returns the attribute name with price if available
     *
     * @param \Aimeos\MShop\Attribute\Item\Iface $item Attribute item
     * @return string Attribute name with price (optional)
     */
    public function transform(\Aimeos\M_Shop\Attribute\Item\Iface $item): string
    {
        if ($price_item = $item->get_ref_items('price', 'default', 'default')->first()) {
            /// Configurable product attribute name (%1$s) with sign (%4$s, +/-), price value (%2$s) and currency (%3$s)
            $str = $this->translate('client', '%1$s (%4$s%2$s%3$s)');
            $value = $price_item->get_value() + $price_item->get_costs();
            $view = $this->view();
            return sprintf($str, $item->get_name(), $view->number(abs($value), $price_item->get_precision()), $view->translate('currency', $price_item->get_currency_id()), $value < 0 ? '−' : '+');
        }
        return $item->get_name();
    }
}