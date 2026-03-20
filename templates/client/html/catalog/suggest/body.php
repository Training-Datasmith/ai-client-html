<?php

declare (strict_types=1);
/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
$enc = $this->encoder();
$items = [];
$pricetype = 'price:default';
$pricefmt = $this->translate('client/code', $pricetype);
/// Price format with price value (%1$s) and currency (%2$s)
$price_format = $pricefmt !== 'price:default' ? $pricefmt : $this->translate('client', '%1$s %2$s');
foreach ($this->get('suggestItems', []) as $id => $product_item) {
    $price = '';
    $media = 'display: none';
    $name = strip_tags($product_item->get_name());
    $media_items = $product_item->get_ref_items('media', 'default', 'default');
    $price_items = $product_item->get_ref_items('price', 'default', 'default');
    if (($media_item = $media_items->first()) !== null) {
        $media = 'background-image: url(\'' . $enc->attr($this->content($media_item->get_preview(), $media_item->get_file_system())) . '\')';
    }
    if (($price_item = $price_items->first()) !== null) {
        $price = sprintf($price_format, $this->number($price_item->get_value(), $price_item->get_precision()), $this->translate('currency', $price_item->get_currency_id()));
    }
    $url = $product_item->get_name('url');
    $params = ['path' => $url, 'd_name' => $url, 'd_prodid' => $product_item->get_id(), 'd_pos' => ''];
    $items[] = ['label' => $name, 'html' => '
			<div class="aimeos catalog-suggest">
				<a class="suggest-item" href="' . $enc->attr($this->link('client/html/catalog/detail/url', $params)) . '">
					<div class="item-image" style="' . $media . '"></div>
					<div class="item-name">' . $enc->html($name) . '</div>
					<div class="item-price">' . $enc->html($price) . '</div>
				</a>
			</div>
		'];
}
echo json_encode($items);