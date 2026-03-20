<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2026
 */
$enc = $this->encoder();
$position = $this->get('position');
$attr_types = $this->get('attributeTypes', map());
$detail_target = $this->config('client/html/catalog/detail/url/target');
$detail_controller = $this->config('client/html/catalog/detail/url/controller', 'catalog');
$detail_action = $this->config('client/html/catalog/detail/url/action', 'detail');
$detail_config = $this->config('client/html/catalog/detail/url/config', []);
$detail_filter = array_flip($this->config('client/html/catalog/detail/url/filter', ['d_prodid']));
?>
<div class="catalog-list-items list"
	data-infiniteurl="<?php 
echo $enc->attr($this->get('infinite-url'));
?>"
	data-pinned="<?php 
echo $enc->attr($this->session('aimeos/catalog/session/pinned/list', []));
?>"><!--

	<?php 
foreach ($this->get('products', []) as $id => $product_item) {
    $first_image = true;
    ?>
		<?php 
    $name = $product_item->get_name('url');
    $params = array_diff_key(['path' => $name, 'd_name' => $name, 'd_prodid' => $product_item->get_id(), 'd_pos' => $position !== null ? $position++ : ''], $detail_filter);
    $url = $this->url($product_item->get_target() ?: $detail_target, $detail_controller, $detail_action, $params, [], $detail_config);
    ?>

		--><div class="product row <?php 
    echo $enc->attr($product_item->get_config_value('css-class'));
    ?>"
			itemscope itemtype="http://schema.org/Product" data-prodid="<?php 
    echo $enc->attr($id);
    ?>"
			data-reqstock="<?php 
    echo (int) $this->config('client/html/basket/require-stock', true);
    ?>">

			<div class="list-column col-6">

				<?php 
    echo $this->partial($this->config('client/html/common/partials/badges', 'common/partials/badges'));
    ?>

				<a class="media-list" href="<?php 
    echo $url;
    ?>" title="<?php 
    echo $enc->attr($product_item->get_name(), $enc::TRUST);
    ?>">
					<?php 
    if (($media_item = $product_item->get_ref_items('media', 'default', 'default')->first()) !== null) {
        ?>
						<noscript>
							<div class="media-item" itemscope itemtype="http://schema.org/ImageObject">
								<img alt="<?php 
        echo $enc->attr($media_item->get_name());
        ?>"
									src="<?php 
        echo $enc->attr($this->content($media_item->get_preview(), $media_item->get_file_system()));
        ?>"
									srcset="<?php 
        echo $enc->attr($this->imageset($media_item->get_previews(true), $media_item->get_file_system()));
        ?>"
									alt="<?php 
        echo $enc->attr($media_item->get_properties('title')->first());
        ?>"
								>
								<meta itemprop="contentUrl" content="<?php 
        echo $enc->attr($this->content($media_item->get_preview(), $media_item->get_file_system()));
        ?>">
							</div>
						</noscript>

						<?php 
        foreach ($product_item->get_ref_items('media', 'default', 'default')->take(2) as $media_item) {
            ?>
							<div class="media-item">
								<img class="lazy-image"
									src="data:image/gif;base64,R0lGODlhAQABAIAAAP///////yH5BAEEAAEALAAAAAABAAEAAAICTAEAOw=="
									sizes="<?php 
            echo $enc->attr($this->config('client/html/common/imageset-sizes', '(min-width: 260px) 240px, 100vw'));
            ?>"
									data-src="<?php 
            echo $enc->attr($this->content($media_item->get_preview(), $media_item->get_file_system()));
            ?>"
									data-srcset="<?php 
            echo $enc->attr($this->imageset($media_item->get_previews(true), $media_item->get_file_system()));
            ?>"
									alt="<?php 
            echo $enc->attr($media_item->get_properties('title')->first());
            ?>"
								>
								<meta itemprop="contentUrl" content="<?php 
            echo $enc->attr($this->content($media_item->get_preview(), $media_item->get_file_system()));
            ?>">
							</div>
						<?php 
        }
        ?>
					<?php 
    }
    ?>
				</a>
			</div><!--

			--><div class="list-column col-6">
				<div class="rating">
					<?php 
    if ($product_item->get_rating() > 0) {
        ?>
						<span class="stars"><?php 
        echo str_repeat('★', (int) round($product_item->get_rating()));
        ?></span>
					<?php 
    }
    ?>
				</div>


				<a class="text-list" href="<?php 
    echo $url;
    ?>">
					<?php 
    if (!($suppliers = $product_item->get_ref_items('supplier'))->is_empty()) {
        ?>
						<h3 class="supplier"><?php 
        echo $enc->html($suppliers->get_name()->first(), $enc::TRUST);
        ?></h3>
					<?php 
    }
    ?>

					<h2 itemprop="name"><?php 
    echo $enc->html($product_item->get_name(), $enc::TRUST);
    ?></h2>

					<?php 
    foreach ($product_item->get_ref_items('text', 'short', 'default') as $text_item) {
        ?>
						<div class="text-item" itemprop="description">
							<?php 
        echo $enc->html($text_item->get_content(), $enc::TRUST);
        ?><br>
						</div>
					<?php 
    }
    ?>
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
    echo $enc->attr($id);
    ?>">
								<?php 
    echo $this->partial($this->config('client/html/common/partials/price', 'common/partials/price'), ['prices' => $product_item->get_ref_items('price', null, 'default')]);
    ?>
							</div>

							<?php 
    if ($product_item->get_type() === 'select') {
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
				</div>


				<?php 
    if ($this->config('client/html/catalog/lists/basket-add', false)) {
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
								<div class="input-group">
									<input type="hidden"
										name="<?php 
            echo $enc->attr($this->formparam('b_action'));
            ?>"
										value="add"
									>
									<input type="hidden"
										name="<?php 
            echo $enc->attr($this->formparam(['b_prod', 0, 'prodid']));
            ?>"
										value="<?php 
            echo $id;
            ?>"
									>
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
									>
									<button class="btn btn-primary btn-action" type="submit"
										title="<?php 
            echo $enc->attr($this->translate('client', 'Add to basket'), $enc::TRUST);
            ?>"
										<?php 
            echo !$product_item->is_available() ? 'disabled' : '';
            ?> >
									</button>
									<a class="btn-pin"
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
		</div><!--

	<?php 
}
?>
--></div>
