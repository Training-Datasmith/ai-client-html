<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2020
 */
$enc = $this->encoder();
/** client/html/catalog/filter/tree/force-search
 * Use the current category in full text searches
 *
 * Normally, a full text search finds all products that match the entered string
 * regardless of the category the user is currently in. This is also the standard
 * behavior of other shops.
 *
 * If it's desired, setting this configuration option to "1" will limit the full
 * text search to the current category only, so only products that match the text
 * and which are in the current category are found.
 *
 * @param boolean True to enforce current category for search, false for full text search only
 * @since 2015.10
 */
$enforce = $this->config('client/html/catalog/filter/tree/force-search', false);
/** client/html/catalog/filter/tree/partial
 * Relative path to the category tree partial template file
 *
 * Partials are templates which are reused in other templates and generate
 * reoccuring blocks filled with data from the assigned values. The tree
 * partial creates an HTML block of nested lists for category trees.
 *
 * @param string Relative path to the template file
 * @since 2022.04
 */
$this->block()->start('catalog/filter/tree');
if (isset($this->tree_catalog_tree) && $this->tree_catalog_tree->get_status() > 0 && !$this->tree_catalog_tree->get_children()->is_empty()) {
    ?>

	<div class="section catalog-filter-tree <?php 
    echo $this->config('client/html/catalog/count/enable', true) ? 'catalog-filter-count' : '';
    ?>"
		aria-label="<?php 
    echo $enc->attr($this->translate('client', 'Product categories'));
    ?>"
		data-counturl="<?php 
    echo $enc->attr($this->link('client/html/catalog/count/url', ['count' => 'tree'] + $this->get('filterParams', [])));
    ?>">

		<div class="aimeos-overlay-offscreen"></div>
		<a class="menu" href="#" title="<?php 
    echo $enc->attr($this->translate('client', 'Categories'));
    ?>"><span class="icon"></span></a>
		<div class="zeynep list-container level-0 catcode-<?php 
    echo $enc->attr($this->tree_catalog_tree->get_code());
    ?> <?php 
    echo $enc->attr($this->tree_catalog_tree->get_config_value('css-class'));
    ?>">

			<div class="row header">
				<div class="col-2"></div>
				<div class="col-8 name"><?php 
    echo $enc->html($this->translate('client', 'Categories'), $enc::TRUST);
    ?></div>
				<div class="col-2 close" data-submenu-close="<?php 
    echo $enc->attr($this->tree_catalog_tree->get_id());
    ?>"></div>
			</div>

			<?php 
    if ($enforce) {
        ?>
				<input type="hidden"
					name="<?php 
        echo $enc->attr($this->formparam(['f_catid']));
        ?>"
					value="<?php 
        echo $enc->attr($this->param('f_catid'));
        ?>"
				>
				<input type="hidden"
					name="<?php 
        echo $enc->attr($this->formparam(['f_name']));
        ?>"
					value="<?php 
        echo $enc->attr($this->get('treeCatalogPath', map())->get_name()->get($this->param('f_catid')));
        ?>"
				>
			<?php 
    }
    ?>

			<?php 
    echo $this->partial($this->config('client/html/catalog/filter/tree/partial', 'catalog/filter/tree-partial'), ['nodes' => $this->tree_catalog_tree->get_children(), 'path' => $this->get('treeCatalogPath', map()), 'params' => [], 'level' => 1]);
    ?>
		</div>
	</div>

<?php 
}
$this->block()->stop();
echo $this->block()->get('catalog/filter/tree');