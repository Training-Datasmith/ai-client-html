<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2019-2026
 */
$enc = $this->encoder();
?>
<link rel="stylesheet" href="<?php 
echo $enc->attr($this->content($this->get('contextSiteTheme', 'default') . '/catalog-product.css', 'fs-theme', true));
?>">
<script defer src="<?php 
echo $enc->attr($this->content($this->get('contextSiteTheme', 'default') . '/catalog-product.js', 'fs-theme', true));
?>"></script>

<?php 
if (isset($this->items_stock_url)) {
    ?>
	<?php 
    foreach ($this->items_stock_url as $url) {
        ?>
		<script defer src="<?php 
        echo $enc->attr($url);
        ?>"></script>
	<?php 
    }
}