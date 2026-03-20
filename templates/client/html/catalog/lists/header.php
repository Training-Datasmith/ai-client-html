<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
$enc = $this->encoder();
/** client/html/catalog/lists/metatags
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
 * @see client/html/catalog/detail/metatags
 */
$multi = $this->config('client/html/catalog/multiroute', false);
$link_key = $multi && $this->param('path') || $this->param('f_catid') ? 'client/html/catalog/tree/url' : 'client/html/catalog/lists/url';
if ((bool) $this->config('client/html/catalog/lists/metatags', true) === true) {
    ?>
	<?php 
    if (($cat_item = $this->get('listCatPath', map())->last()) !== null) {
        ?>

		<?php 
        if ($title = $cat_item->get_ref_items('text', 'meta-title', 'default')->get_content()->first()) {
            ?>
			<title><?php 
            echo $enc->html($title);
            ?></title>
		<?php 
        } else {
            ?>
			<title><?php 
            echo $enc->html(strip_tags($cat_item->get_name()));
            ?> | <?php 
            echo $enc->html($this->get('contextSiteLabel', 'Aimeos'));
            ?></title>
		<?php 
        }
        ?>


		<link rel="canonical" href="<?php 
        echo $enc->attr($this->link($link_key, map($this->get('listParams', []))->except('f_sort')->all(), ['absoluteUri' => true]));
        ?>">

		<?php 
        foreach ($cat_item->get_ref_items('text', 'meta-keyword', 'default') as $text_item) {
            ?>
			<meta name="keywords" content="<?php 
            echo $enc->attr(strip_tags($text_item->get_content()));
            ?>">
		<?php 
        }
        ?>

		<?php 
        foreach ($cat_item->get_ref_items('text', 'meta-description', 'default') as $text_item) {
            ?>
			<meta name="description" content="<?php 
            echo $enc->attr(strip_tags($text_item->get_content()));
            ?>">
		<?php 
        }
        ?>

	<?php 
    } elseif ($search = $this->param('f_search', null)) {
        /// Product search hint with user provided search string (%1$s)
        ?>

		<title><?php 
        echo $enc->html(sprintf($this->translate('client', 'Result for "%1$s"'), strip_tags($search)));
        ?> | <?php 
        echo $enc->html($this->get('contextSiteLabel', 'Aimeos'));
        ?></title>

	<?php 
    } else {
        ?>

		<title><?php 
        echo $enc->html($this->translate('client', 'Found products'));
        ?> | <?php 
        echo $enc->html($this->get('contextSiteLabel', 'Aimeos'));
        ?></title>
		<link rel="canonical" href="<?php 
        echo $enc->attr($this->link('client/html/catalog/lists/url', [], ['absoluteUri' => true]));
        ?>">

	<?php 
    }
    ?>

	<?php 
    if ($this->param('f_search') || $this->param('f_sort')) {
        ?>
		<meta name="robots" content="noindex">
	<?php 
    }
    ?>

	<?php 
    if ($this->get('listPageCurr', 0) > 1) {
        ?>
		<link rel="prev" href="<?php 
        echo $enc->attr($this->link('client/html/catalog/lists/url', ['l_page' => $this->get('listPagePrev', 0)] + $this->get('listParams', []), ['absoluteUri' => true]));
        ?>">
	<?php 
    }
    ?>


	<?php 
    if ($this->get('listPageCurr', 0) > 1 && $this->get('listPageCurr', 0) < $this->get('listPageLast', 0)) {
        // Optimization to avoid loading next page while the user is still filtering
        ?>
		<link rel="next prefetch" href="<?php 
        echo $enc->attr($this->link('client/html/catalog/lists/url', ['l_page' => $this->get('listPageNext', 0)] + $this->get('listParams', []), ['absoluteUri' => true]));
        ?>">
	<?php 
    }
    ?>

	<meta name="application-name" content="Aimeos">

<?php 
}
?>

<link rel="stylesheet" href="<?php 
echo $enc->attr($this->content($this->get('contextSiteTheme', 'default') . '/catalog-lists.css', 'fs-theme', true));
?>">
<script defer src="<?php 
echo $enc->attr($this->content($this->get('contextSiteTheme', 'default') . '/catalog-lists.js', 'fs-theme', true));
?>"></script>

<?php 
foreach ($this->get('listStockUrl', []) as $url) {
    ?>
	<script class="items-stock" nonce="<?php 
    echo $enc->attr($this->get('contextNonce'));
    ?>" defer src="<?php 
    echo $enc->attr($url);
    ?>"></script>
<?php 
}