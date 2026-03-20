<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
$enc = $this->encoder();
/** client/html/catalog/session/pinned/url/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2014.09
 * @see client/html/catalog/session/pinned/url/controller
 * @see client/html/catalog/session/pinned/url/action
 * @see client/html/catalog/session/pinned/url/config
 * @see client/html/catalog/session/url/filter
 */
/** client/html/catalog/session/pinned/url/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2014.09
 * @see client/html/catalog/session/pinned/url/target
 * @see client/html/catalog/session/pinned/url/action
 * @see client/html/catalog/session/pinned/url/config
 * @see client/html/catalog/session/url/filter
 */
/** client/html/catalog/session/pinned/url/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2014.09
 * @see client/html/catalog/session/pinned/url/target
 * @see client/html/catalog/session/pinned/url/controller
 * @see client/html/catalog/session/pinned/url/config
 * @see client/html/catalog/session/url/filter
 */
/** client/html/catalog/session/pinned/url/config
 * Associative list of configuration options used for generating the URL
 *
 * You can specify additional options as key/value pairs used when generating
 * the URLs, like
 *
 *  client/html/<clientname>/url/config = array( 'absoluteUri' => true )
 *
 * The available key/value pairs depend on the application that embeds the e-commerce
 * framework. This is because the infrastructure of the application is used for
 * generating the URLs. The full list of available config options is referenced
 * in the "see also" section of this page.
 *
 * @param string Associative list of configuration options
 * @since 2014.09
 * @see client/html/catalog/session/pinned/url/target
 * @see client/html/catalog/session/pinned/url/controller
 * @see client/html/catalog/session/pinned/url/action
 * @see client/html/catalog/session/url/filter
 */
/** client/html/catalog/session/pinned/url/filter
 * Removes parameters for the detail page before generating the URL
 *
 * This setting removes the listed parameters from the URLs. Keep care to
 * remove no required parameters!
 *
 * @param array List of parameter names to remove
 * @since 2022.10
 * @see client/html/catalog/session/pinned/url/target
 * @see client/html/catalog/session/pinned/url/controller
 * @see client/html/catalog/session/pinned/url/action
 * @see client/html/catalog/session/pinned/url/config
 */
/** client/html/catalog/session/pinned/count/enable
 * Displays the number of pinned products in the header of the pinned list
 *
 * This configuration option enables or disables displaying the total number
 * of pinned products in the header of section. This increases the usability if
 * more than the shown products are available in the list but this depends on
 * the design of the site.
 *
 * @param integer Zero to disable the counter, one to enable it
 * @since 2014.09
 * @see client/html/catalog/session/seen/count/enable
 */
$this->block()->start('catalog/session/pinned');
?>
<div class="section catalog-session-pinned">
	<div class="container-xxl">
		<h2 class="header pinned">
			<?php 
echo $this->translate('client', 'Pinned products');
?>
			<?php 
if ($this->config('client/html/catalog/session/pinned/count/enable', true)) {
    ?>
				<span class="count"><?php 
    echo count($this->get('pinnedProductItems', []));
    ?></span>
			<?php 
}
?>
		</h2>

		<ul class="pinned-items">
			<?php 
foreach ($this->get('pinnedProductItems', []) as $id => $product_item) {
    ?>
				<?php 
    $name = $product_item->get_name('url');
    ?>
				<?php 
    $pin_params = ['pin_action' => 'delete', 'pin_id' => $id, 'd_name' => $name] + $this->get('pinnedParams', []);
    ?>
				<?php 
    $detail_params = ['path' => $name, 'd_name' => $name, 'd_prodid' => $id, 'd_pos' => ''];
    ?>

				<li class="pinned-item product" data-prodid="<?php 
    echo $enc->attr($id);
    ?>">
					<form method="POST" action="<?php 
    echo $enc->attr($this->link('client/html/catalog/session/pinned/url', $pin_params));
    ?>">
						<button type="submit" class="minibutton delete" title="<?php 
    echo $this->translate('client', 'Delete item');
    ?>"></button>
						<?php 
    echo $this->csrf()->formfield();
    ?>
					</form>

					<a href="<?php 
    echo $enc->attr($this->link('client/html/catalog/detail/url', $detail_params));
    ?>">

						<?php 
    $media_items = $product_item->get_ref_items('media', 'default', 'default');
    ?>
						<?php 
    if (($media_item = $media_items->first()) !== null) {
        ?>
							<div class="media-item" style="background-image: url('<?php 
        echo $enc->attr($this->content($media_item->get_preview(), $media_item->get_file_system()));
        ?>')"></div>
						<?php 
    } else {
        ?>
							<div class="media-item"></div>
						<?php 
    }
    ?>

						<h2 class="name"><?php 
    echo $enc->html($product_item->get_name(), $enc::TRUST);
    ?></h2>
						<div class="price-list">
							<?php 
    echo $this->partial($this->config('client/html/common/partials/price', 'common/partials/price'), ['prices' => $product_item->get_ref_items('price', null, 'default')]);
    ?>
						</div>

					</a>
				</li>

			<?php 
}
?>
		</ul>
	</div>
</div>
<?php 
$this->block()->stop();
echo $this->block()->get('catalog/session/pinned');