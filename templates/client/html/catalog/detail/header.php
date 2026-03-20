<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
$enc = $this->encoder();
$detail_target = $this->config('client/html/catalog/detail/url/target');
$detail_controller = $this->config('client/html/catalog/detail/url/controller', 'catalog');
$detail_action = $this->config('client/html/catalog/detail/url/action', 'detail');
$detail_config = $this->config('client/html/catalog/detail/url/config', []);
$detail_filter = array_flip($this->config('client/html/catalog/detail/url/filter', ['d_prodid']));
/** client/html/catalog/detail/metatags
 * Adds the title, meta and link tags to the HTML header
 *
 * By default, each instance of the catalog list component adds some HTML meta
 * tags to the page head section, like page title, meta keywords and description
 * as well as some link tags to support browser navigation. If several instances
 * are placed on one page, this leads to adding several title and meta tags used
 * by search engine. This setting enables you to suppress these tags in the page
 * header and maybe add your own to the page manually.
 *
 * @param boolean True to display the meta tags, false to hide it
 * @since 2017.01
 * @see client/html/catalog/lists/metatags
 */
if ((bool) $this->config('client/html/catalog/detail/metatags', true) === true) {
    ?>
	<?php 
    if (isset($this->detail_product_item)) {
        ?>
		<?php 
        if ($title = $this->detail_product_item->get_ref_items('text', 'meta-title', 'default')->get_content()->first()) {
            ?>
			<title><?php 
            echo $enc->html($title);
            ?></title>
		<?php 
        } else {
            ?>
			<title><?php 
            echo $enc->html(strip_tags($this->detail_product_item->get_name()));
            ?>
				<?php 
            echo $enc->html(strip_tags(' ' . $this->detail_product_item->get_ref_items('supplier')->get_name()->first()));
            ?>
				<?php 
            echo $enc->html(' | ' . $this->get('contextSiteLabel', 'Aimeos'));
            ?>
			</title>
		<?php 
        }
        ?>

		<?php 
        $name = $this->detail_product_item->get_name('url');
        ?>
		<?php 
        $params = array_diff_key(['path' => $name, 'd_name' => $name, 'd_prodid' => $this->detail_product_item->get_id(), 'd_pos' => ''], $detail_filter);
        ?>
		<link rel="canonical" href="<?php 
        echo $enc->attr($this->url($this->detail_product_item->get_target() ?: $detail_target, $detail_controller, $detail_action, $params, [], $detail_config + ['absoluteUri' => true]));
        ?>">

		<meta property="og:type" content="product">
		<meta property="og:title" content="<?php 
        echo $enc->attr(strip_tags($this->detail_product_item->get_name()));
        ?>">
		<meta property="og:url" content="<?php 
        echo $enc->attr($this->url($this->detail_product_item->get_target() ?: $detail_target, $detail_controller, $detail_action, $params, [], $detail_config + ['absoluteUri' => true]));
        ?>">

		<?php 
        if ($media_item = $this->detail_product_item->get_ref_items('media', 'default', 'default')->first()) {
            ?>
			<meta property="og:image" content="<?php 
            echo $enc->attr($this->content($media_item->get_preview(true), $media_item->get_file_system()));
            ?>">
			<meta name="twitter:card" content="summary_large_image">
		<?php 
        }
        ?>

		<?php 
        if ($text_item = $this->detail_product_item->get_ref_items('text', 'meta-description', 'default')->first()) {
            ?>
			<meta property="og:description" content="<?php 
            echo $enc->attr(strip_tags($text_item->get_content()));
            ?>">
			<meta name="description" content="<?php 
            echo $enc->attr(strip_tags($text_item->get_content()));
            ?>">
		<?php 
        }
        ?>

		<?php 
        if ($text_item = $this->detail_product_item->get_ref_items('text', 'meta-keyword', 'default')->first()) {
            ?>
			<meta name="keywords" content="<?php 
            echo $enc->attr(strip_tags($text_item->get_content()));
            ?>">
		<?php 
        }
        ?>

		<?php 
        if (($price_item = $this->detail_product_item->get_ref_items('price', 'default', 'default')->first()) !== null) {
            ?>
			<meta property="product:price:amount" content="<?php 
            echo $enc->attr($price_item->get_value());
            ?>">
			<meta property="product:price:currency" content="<?php 
            echo $enc->attr($price_item->get_currency_id());
            ?>">
		<?php 
        }
        ?>

	<?php 
    }
    ?>

	<meta name="application-name" content="Aimeos">

<?php 
}
?>

<link rel="stylesheet" href="<?php 
echo $enc->attr($this->content($this->get('contextSiteTheme', 'default') . '/slider.css', 'fs-theme', true));
?>">
<link rel="stylesheet" href="<?php 
echo $enc->attr($this->content($this->get('contextSiteTheme', 'default') . '/catalog-detail.css', 'fs-theme', true));
?>">

<script defer src="<?php 
echo $enc->attr($this->content($this->get('contextSiteTheme', 'default') . '/slider.js', 'fs-theme', true));
?>"></script>
<script defer src="<?php 
echo $enc->attr($this->content($this->get('contextSiteTheme', 'default') . '/catalog-detail.js', 'fs-theme', true));
?>"></script>

<?php 
if (isset($this->detail_stock_url)) {
    ?>
	<?php 
    foreach ($this->detail_stock_url as $url) {
        ?>
		<script defer src="<?php 
        echo $enc->attr($url);
        ?>"></script>
	<?php 
    }
}
?>

<?php 
echo $this->get('detailHeader');