<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2026
 */
/* Expected data:
 * - products : List of products incl. referenced items
 * - basket-add : True to display "add to basket" button, false if not (optional)
 * - require-stock : True if the stock level should be displayed (optional)
 * - itemprop : Schema.org property for the product items (optional)
 * - position : Position is product list to start from (optional)
 */
$enc = $this->encoder();
$position = $this->get('position');
$attr_types = $this->get('attributeTypes', map());
/** client/html/catalog/detail/url/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2014.03
 * @see client/html/catalog/detail/url/controller
 * @see client/html/catalog/detail/url/action
 * @see client/html/catalog/detail/url/config
 * @see client/html/catalog/detail/url/filter
 */
$detail_target = $this->config('client/html/catalog/detail/url/target');
/** client/html/catalog/detail/url/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2014.03
 * @see client/html/catalog/detail/url/target
 * @see client/html/catalog/detail/url/action
 * @see client/html/catalog/detail/url/config
 * @see client/html/catalog/detail/url/filter
 */
$detail_controller = $this->config('client/html/catalog/detail/url/controller', 'catalog');
/** client/html/catalog/detail/url/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2014.03
 * @see client/html/catalog/detail/url/target
 * @see client/html/catalog/detail/url/controller
 * @see client/html/catalog/detail/url/config
 * @see client/html/catalog/detail/url/filter
 */
$detail_action = $this->config('client/html/catalog/detail/url/action', 'detail');
/** client/html/catalog/detail/url/config
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
 * @since 2014.03
 * @see client/html/catalog/detail/url/target
 * @see client/html/catalog/detail/url/controller
 * @see client/html/catalog/detail/url/action
 * @see client/html/catalog/detail/url/filter
 * @see client/html/url/config
 */
$detail_config = $this->config('client/html/catalog/detail/url/config', []);
/** client/html/catalog/detail/url/filter
 * Removes parameters for the detail page before generating the URL
 *
 * For SEO, it's nice to have product URLs which contains the product names only.
 * Usually, product names are unique so exactly one product is found when resolving
 * the product by its name. If two or more products share the same name, it's not
 * possible to refer to the correct product and in this case, the product ID is
 * required as unique identifier.
 *
 * This setting removes the listed parameters from the URLs of the detail pages.
 *
 * @param array List of parameter names to remove
 * @since 2019.04
 * @see client/html/catalog/detail/url/target
 * @see client/html/catalog/detail/url/controller
 * @see client/html/catalog/detail/url/action
 * @see client/html/catalog/detail/url/config
 */
$detail_filter = array_flip($this->config('client/html/catalog/detail/url/filter', ['d_prodid']));
/** client/html/common/imageset-sizes
 * Size hints for loading the appropriate catalog home image sizes
 *
 * Modern browsers can load images of different sizes depending on their viewport
 * size. This is also known as serving "responsive images" because on small
 * smartphone screens, only small images are loaded while full width images are
 * loaded on large desktop screens.
 *
 * A responsive image contains additional "srcset" and "sizes" attributes:
 *
 *  <img src="img.jpg"
 *  	srcset="img-small.jpg 240w, img-large.jpg 720w"
 *  	sizes="(max-width: 320px) 240px, 720px"
 *  >
 *
 * The images and their width in the "srcset" attribute are automatically added
 * based on the sizes of the generated preview images. The value of the "sizes"
 * attribute can't be determined by Aimeos because it depends on the used frontend
 * theme and the size of the images defined in the CSS file. This config setting
 * adds the required value for the "sizes" attribute.
 *
 * It's value consists of one or more comma separated rules with
 * - an optional CSS media query for the view port size
 * - the (max) width the image will be displayed within this viewport size
 *
 * Rules without a media query are independent of the view port size and must be
 * always at last because the rules are evaluated from left to right and the first
 * matching rule is used.
 *
 * The above example tells the browser:
 * - Up to 320px view port width use img-small.jpg
 * - Above 320px view port width use img-large.jpg
 *
 * For more information about the "sizes" attribute of the "img" HTML tag read:
 * {@link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/img#attr-sizes}
 *
 * @param string HTML image "sizes" attribute
 * @since 2021.04
 * @see client/html/common/imageset-sizes
 */
foreach ($this->get('products', []) as $id => $product_item) {
    ?>
	<?php 
    $name = $product_item->get_name('url');
    $params = array_diff_key(['path' => $name, 'd_name' => $name, 'd_prodid' => $product_item->get_id(), 'd_pos' => $position !== null ? $position++ : ''], $detail_filter);
    $url = $this->url($product_item->get_target() ?: $detail_target, $detail_controller, $detail_action, $params, [], $detail_config);
    $media_items = $product_item->get_ref_items('media', 'default', 'default');
    $item_prop = $this->get('itemprop');
    ?>

	<div class="product row <?php 
    echo $enc->attr($product_item->get_config_value('css-class'));
    ?>"
		data-prodid="<?php 
    echo $enc->attr($id);
    ?>" data-reqstock="<?php 
    echo (int) $this->get('require-stock', true);
    ?>"
		<?php 
    echo $item_prop ? 'itemprop="' . $item_prop . '"' : '';
    ?> itemscope itemtype="http://schema.org/Product">

		<div class="list-column col-12">

			<?php 
    echo $this->partial(
        /** client/html/common/partials/badges
         * Relative path to the badges partial template file
         *
         * Partials are templates which are reused in other templates and generate
         * reoccuring blocks filled with data from the assigned values. The badges
         * partial creates an HTML block for the product badges.
         *
         * The partial template files are usually stored in the templates/common/partials/ folder
         * of the core or the extensions. The configured path to the partial file must
         * be relative to the templates/ folder, e.g. "common/partials/badges.php".
         *
         * @param string Relative path to the template file
         * @since 2022.04
         */
        $this->config('client/html/common/partials/badges', 'common/partials/badges')
    );
    ?>

			<a class="media-list <?php 
    echo $media_items->count() > 1 ? 'multiple' : '';
    ?>"
				href="<?php 
    echo $enc->attr($url);
    ?>" title="<?php 
    echo $enc->attr($product_item->get_name(), $enc::TRUST);
    ?>">

				<?php 
    if ($media_item = $media_items->first()) {
        ?>

					<noscript>
						<div class="media-item">
							<img loading="lazy" itemprop="image"
								src="<?php 
        echo $enc->attr($this->content($media_item->get_preview(), $media_item->get_file_system()));
        ?>"
								srcset="<?php 
        echo $enc->attr($this->imageset($media_item->get_previews(true), $media_item->get_file_system()));
        ?>"
								sizes="<?php 
        echo $enc->attr($this->config('client/html/common/imageset-sizes', '(min-width: 260px) 240px, 100vw'));
        ?>"
								alt="<?php 
        echo $enc->attr($media_item->get_properties('title')->first() ?: $product_item->get_name());
        ?>"
							>
						</div>
					</noscript>

					<?php 
        foreach ($media_items->take(2) as $media_item) {
            ?>

						<div class="media-item">
							<img loading="lazy" itemprop="image"
								src="<?php 
            echo $enc->attr($this->content($media_item->get_preview(), $media_item->get_file_system()));
            ?>"
								srcset="<?php 
            echo $enc->attr($this->imageset($media_item->get_previews(true), $media_item->get_file_system()));
            ?>"
								sizes="<?php 
            echo $enc->attr($this->config('client/html/common/imageset-sizes', '(min-width: 260px) 240px, 100vw'));
            ?>"
								alt="<?php 
            echo $enc->attr($media_item->get_properties('title')->first() ?: $product_item->get_name());
            ?>"
							>
						</div>

					<?php 
        }
        ?>
				<?php 
    }
    ?>

			</a>
		</div>

		<div class="list-column col-12">
			<a href="<?php 
    echo $enc->attr($url);
    ?>">

				<div class="product-info">
					<?php 
    if ($supplier = $product_item->get_ref_items('supplier')->get_name()->first()) {
        ?>
						<div class="supplier"><?php 
        echo $enc->html($supplier);
        ?></div>
					<?php 
    } elseif ($site_item = $product_item->get_site_item()) {
        ?>
						<div class="supplier"><?php 
        echo $enc->html($site_item->get_label());
        ?></div>
					<?php 
    }
    ?>

					<div class="rating"><!--
						--><span class="stars"><?php 
    echo str_repeat('★', (int) round($product_item->get_rating()));
    ?></span><!--
					--></div>
				</div>

				<div class="text-list">
					<h2 class="name" itemprop="name"><?php 
    echo $enc->html($product_item->get_name(), $enc::TRUST);
    ?></h2>

					<?php 
    foreach ($product_item->get_ref_items('text', 'short', 'default') as $text_item) {
        ?>

						<div class="text-item" itemprop="description">
							<?php 
        echo $enc->html($text_item->get_content(), $enc::TRUST);
        ?>
						</div>

					<?php 
    }
    ?>

				</div>
			</a>

			<div class="offer" itemscope itemprop="offers" itemtype="http://schema.org/Offer">

				<div class="section">
					<div class="stock-list">
						<div class="articleitem <?php 
    echo !in_array($product_item->get_type(), ['group']) ? 'stock-actual' : '';
    ?>"
							data-prodid="<?php 
    echo $enc->attr($product_item->get_id());
    ?>">
						</div>

						<?php 
    foreach ($product_item->get_ref_items('product', null, 'default') as $article_id => $article_item) {
        ?>

							<div class="articleitem" data-prodid="<?php 
        echo $enc->attr($article_id);
        ?>"></div>

						<?php 
    }
    ?>

					</div>

					<div class="price-list">
						<div class="articleitem price price-actual" data-prodid="<?php 
    echo $enc->attr($product_item->get_id());
    ?>">

							<?php 
    echo $this->partial(
        /** client/html/common/partials/price
         * Relative path to the price partial template file
         *
         * Partials are templates which are reused in other templates and generate
         * reoccuring blocks filled with data from the assigned values. The price
         * partial creates an HTML block for a list of price items.
         *
         * The partial template files are usually stored in the templates/partials/ folder
         * of the core or the extensions. The configured path to the partial file must
         * be relative to the templates/ folder, e.g. "partials/price.php".
         *
         * @param string Relative path to the template file
         * @since 2015.04
         */
        $this->config('client/html/common/partials/price', 'common/partials/price'),
        ['prices' => $product_item->get_ref_items('price', null, 'default')]
    );
    ?>

						</div>

						<?php 
    if ($this->get('basket-add', false) && $product_item->get_type() === 'select') {
        ?>
							<?php 
        foreach ($product_item->get_ref_items('product', 'default', 'default') as $prodid => $product) {
            ?>
								<?php 
            if (!($prices = $product->get_ref_items('price', null, 'default'))->is_empty()) {
                ?>

									<div class="articleitem price" data-prodid="<?php 
                echo $enc->attr($prodid);
                ?>">
										<?php 
                echo $this->partial($this->config('client/html/common/partials/price', 'common/partials/price'), ['prices' => $prices]);
                ?>
									</div>

								<?php 
            }
            ?>
							<?php 
        }
        ?>
						<?php 
    }
    ?>
					</div>

				</div>

				<?php 
    if ($this->get('basket-add', false)) {
        ?>

					<form class="basket" method="POST" action="<?php 
        echo $enc->attr($this->link('client/html/basket/standard/url'));
        ?>">
						<!-- catalog.lists.items.csrf -->
						<?php 
        echo $this->csrf()->formfield();
        ?>
						<!-- catalog.lists.items.csrf -->

						<?php 
        if ($product_item->get_type() === 'select') {
            ?>

							<div class="items-selection">
								<?php 
            echo $this->partial($this->config('client/html/common/partials/selection', 'common/partials/selection'), ['productItems' => $product_item->get_ref_items('product', 'default', 'default'), 'productItem' => $product_item, 'attributeTypes' => $attr_types]);
            ?>
							</div>

						<?php 
        }
        ?>

						<div class="items-attribute">

							<?php 
        echo $this->partial($this->config('client/html/common/partials/attribute', 'common/partials/attribute'), ['productItem' => $product_item, 'attributeTypes' => $attr_types]);
        ?>

						</div>

						<?php 
        if (!$product_item->get_ref_items('price', 'default', 'default')->empty()) {
            ?>
							<div class="addbasket">
								<input type="hidden" value="add"
									name="<?php 
            echo $enc->attr($this->formparam('b_action'));
            ?>"
								>
								<input type="hidden" value="<?php 
            echo $id;
            ?>"
									name="<?php 
            echo $enc->attr($this->formparam(['b_prod', 0, 'prodid']));
            ?>"
								>
								<div class="input-group">
									<input type="number" max="2147483647"
										value="<?php 
            echo $enc->attr($product_item->get_scale());
            ?>"
										min="<?php 
            echo $enc->attr($product_item->get_scale());
            ?>"
										step="<?php 
            echo $enc->attr($product_item->get_scale());
            ?>"
										required="required" <?php 
            echo !$product_item->is_available() ? 'disabled' : '';
            ?>
										name="<?php 
            echo $enc->attr($this->formparam(['b_prod', 0, 'quantity']));
            ?>"
										title="<?php 
            echo $enc->attr($this->translate('client', 'Quantity'), $enc::TRUST);
            ?>"
									><!--
									--><button class="btn btn-primary btn-action" type="submit"
										title="<?php 
            echo $enc->attr($this->translate('client', 'Add to basket'), $enc::TRUST);
            ?>"
										<?php 
            echo !$product_item->is_available() ? 'disabled' : '';
            ?> >
									</button><!--
									--><a class="btn-pin"
										href="<?php 
            echo $enc->attr($this->link('client/html/catalog/session/pinned/url', ['pin_action' => 'add', 'pin_id' => $id, 'd_name' => $product_item->get_name('url')]));
            ?>"
										data-rmurl="<?php 
            echo $enc->attr($this->link('client/html/catalog/session/pinned/url', ['pin_action' => 'delete', 'pin_id' => $id, 'd_name' => $product_item->get_name('url')]));
            ?>"
										title="<?php 
            echo $enc->attr($this->translate('client', 'Pin product'), $enc::TRUST);
            ?>">
									</a>
								</div>
							</div>
						<?php 
        }
        ?>

					</form>

				<?php 
    }
    ?>

			</div>
		</div>
	</div>

<?php 
}