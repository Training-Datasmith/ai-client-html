<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
$enc = $this->encoder();
$multi = $this->config('client/html/catalog/multiroute', false);
$link_key = $multi && $this->param('path') || $this->param('f_catid') ? 'client/html/catalog/tree/url' : 'client/html/catalog/lists/url';
/** client/html/catalog/lists/pagination
 * Enables or disables pagination in list views
 *
 * Pagination is automatically hidden if there are not enough products in the
 * category or search result. But sometimes you don't want to show the pagination
 * at all, e.g. if you implement infinite scrolling by loading more results
 * dynamically using AJAX.
 *
 * @param boolean True for enabling, false for disabling pagination
 * @since 2019.04
 */
/** client/html/catalog/lists/basket-add
 * Display the "add to basket" button for each product item
 *
 * Enables the button for adding products to the basket from the list view.
 * This works for all type of products, even for selection products with product
 * variants and product bundles. By default, also optional attributes are
 * displayed if they have been associated to a product.
 *
 * **Note:** To fetch the necessary product variants, you have to extend the
 * list of domains for "client/html/catalog/lists/domains", e.g.
 *
 *  client/html/catalog/lists/domains = array( 'attribute', 'media', 'price', 'product', 'text' )
 *
 * @param boolean True to display the button, false to hide it
 * @since 2016.01
 * @see client/html/catalog/home/basket-add
 * @see client/html/catalog/detail/basket-add
 * @see client/html/catalog/product/basket-add
 * @see client/html/basket/related/basket-add
 * @see client/html/catalog/domains
 */
/** client/html/catalog/lists/infinite-scroll
 * Enables infinite scrolling in product catalog list
 *
 * If set to true, products from the next page are loaded via XHR request
 * and added to the product list when the user reaches the list bottom.
 *
 * @param boolean True to use infinite scrolling, false to disable it
 * @since 2019.10
 */
$infinite_scroll = $this->config('client/html/catalog/lists/infinite-scroll', false);
$url = '';
if ($infinite_scroll && $this->get('listPageNext', 0) > $this->get('listPageCurr', 0)) {
    $url = $this->link('client/html/catalog/lists/url', ['l_page' => $this->get('listPageNext')] + $this->get('listParams', []));
}
?>
<div class="section aimeos catalog-list <?php 
echo $enc->attr($this->get('listCatPath', map())->get_config_value('css-class', '')->join(' '));
?>"
	data-jsonurl="<?php 
echo $enc->attr($this->link('client/jsonapi/url'));
?>">

	<div class="container-xxl">

		<?php 
if (($cat_item = $this->get('listCatPath', map())->last()) !== null) {
    ?>

			<div class="catalog-list-head">

				<?php 
    foreach ($cat_item->get_ref_items('media', 'default', 'default') as $media_item) {
        ?>

					<div class="head-image">
						<img class="<?php 
        echo $enc->attr($media_item->get_type());
        ?>"
							src="<?php 
        echo $enc->attr($this->content($media_item->get_preview(true), $media_item->get_file_system()));
        ?>"
							srcset="<?php 
        echo $enc->attr($this->imageset($media_item->get_previews(true), $media_item->get_file_system()));
        ?>"
							alt="<?php 
        echo $enc->attr($media_item->get_properties('title')->first());
        ?>"
						>
					</div>

				<?php 
    }
    ?>

				<h1><?php 
    echo $enc->html($cat_item->get_name());
    ?></h1>

			</div>

		<?php 
}
?>


		<?php 
if ($this->get('listPageCurr', 0) <= 1 && ($cat_item = $this->get('listCatPath', map())->last()) !== null) {
    ?>
			<div class="catalog-list-head">
				<?php 
    foreach ($cat_item->get_ref_items('text', 'short', 'default') as $text_item) {
        ?>
					<div class="header-text">
						<?php 
        echo $enc->html($text_item->get_content(), $enc::TRUST);
        ?>
					</div>
				<?php 
    }
    ?>
			</div>
		<?php 
}
?>


		<?php 
if ($this->get('listProductTotal', 0) > 0) {
    ?>

			<div class="catalog-list-type">
				<a class="type-item type-grid" title="<?php 
    echo $enc->attr($this->translate('client', 'Grid view'));
    ?>"
					href="<?php 
    echo $enc->attr($this->link($link_key, map($this->get('listParams', []))->remove('l_type')->all()));
    ?>"></a>
				<a class="type-item type-list" title="<?php 
    echo $enc->attr($this->translate('client', 'List view'));
    ?>"
					href="<?php 
    echo $enc->attr($this->link($link_key, ['l_type' => 'list'] + $this->get('listParams', [])));
    ?>"></a>
			</div>

		<?php 
}
?>


		<?php 
if ($search_text = $this->param('f_search')) {
    ?>

			<div class="list-search">

				<?php 
    if (($total = $this->get('listProductTotal', 0)) > 0) {
        ?>
					<?php 
        echo $enc->html(sprintf($this->translate('client', 'Search result for <span class="searchstring">"%1$s"</span> (%2$d article)', 'Search result for <span class="searchstring">"%1$s"</span> (%2$d articles)', $total), $search_text, $total), $enc::TRUST);
        ?>
				<?php 
    } else {
        ?>
					<?php 
        echo $enc->html(sprintf($this->translate('client', 'No articles found for <span class="searchstring">"%1$s"</span>. Please try again with a different keyword.'), $search_text), $enc::TRUST);
        ?>
				<?php 
    }
    ?>

			</div>

		<?php 
}
?>


		<?php 
if ($this->get('listProductTotal', 0) > 0 && $this->config('client/html/catalog/lists/pagination', true)) {
    ?>
			<?php 
    echo $this->partial('catalog/lists/pagination', ['params' => $this->get('listParams', []), 'size' => $this->get('listPageSize', 48), 'total' => $this->get('listProductTotal', 0), 'current' => $this->get('listPageCurr', 0), 'prev' => $this->get('listPagePrev', 0), 'next' => $this->get('listPageNext', 0), 'last' => $this->get('listPageLast', 0)]);
    ?>
		<?php 
}
?>


		<?php 
echo $this->partial($this->get('listPartial', 'catalog/lists/items'), ['require-stock' => (int) $this->config('client/html/basket/require-stock', true), 'basket-add' => $this->config('client/html/catalog/lists/basket-add', false), 'products' => $this->get('listProductItems', map()), 'position' => $this->get('listPosition'), 'infinite-url' => $url]);
?>


		<?php 
if ($this->get('listProductTotal', 0) > 0 && $this->config('client/html/catalog/lists/pagination', true)) {
    ?>
			<?php 
    echo $this->partial('catalog/lists/pagination', ['params' => $this->get('listParams', []), 'size' => $this->get('listPageSize', 48), 'total' => $this->get('listProductTotal', 0), 'current' => $this->get('listPageCurr', 0), 'prev' => $this->get('listPagePrev', 0), 'next' => $this->get('listPageNext', 0), 'last' => $this->get('listPageLast', 0)]);
    ?>
		<?php 
}
?>


		<?php 
if ($this->get('listPageCurr', 0) <= 1 && ($cat_item = $this->get('listCatPath', map())->last()) !== null && !($text_items = $cat_item->get_ref_items('text', 'long', 'default'))->is_empty()) {
    ?>
			<div class="catalog-list-footer">
				<?php 
    foreach ($text_items as $text_item) {
        ?>
					<div class="footer-text">
						<?php 
        echo $enc->html($text_item->get_content(), $enc::TRUST);
        ?>
					</div>
				<?php 
    }
    ?>
			</div>
		<?php 
}
?>

	</div>
</div>
