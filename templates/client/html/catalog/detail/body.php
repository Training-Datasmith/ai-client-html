<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
/* Available data:
 * - detailProductItem : Product item incl. referenced items
 */
$enc = $this->encoder();
/** client/html/basket/require-stock
 * Customers can order products only if there are enough products in stock
 *
 * Checks that the requested product quantity is in stock before
 * the customer can add them to his basket and order them. If there
 * are not enough products available, the customer will get a notice.
 *
 * @param boolean True if products must be in stock, false if products can be sold without stock
 * @since 2014.03
 */
$reqstock = (int) $this->config('client/html/basket/require-stock', true);
/** client/html/catalog/detail/basket-add
 * Display the "add to basket" button for each suggested/bought-together product item
 *
 * Enables the button for adding products to the basket for the related products
 * in the basket. This works for all type of products, even for selection products
 * with product variants and product bundles. By default, also optional attributes
 * are displayed if they have been associated to a product.
 *
 * To fetch the variant articles of selection products too, add this setting to
 * your configuration:
 *
 * mshop/common/manager/maxdepth = 3
 *
 * @param boolean True to display the button, false to hide it
 * @since 2021.04
 * @see client/html/catalog/home/basket-add
 * @see client/html/catalog/lists/basket-add
 * @see client/html/catalog/product/basket-add
 * @see client/html/basket/related/basket-add
 */
$var_attr = [];
if (isset($this->detail_product_item) && ($article_id = $this->param('d_articleid')) && ($list_item = $this->detail_product_item->get_list_item('product', 'default', $article_id)) && $article = $list_item->get_ref_item()) {
    $var_attr = $article->get_ref_items('attribute', null, 'variant')->col('attribute.id', 'attribute.type');
}
$attr_types = $this->get('detailAttributeTypes', []);
$prop_types = $this->get('detailPropertyTypes', []);
if (isset($this->detail_product_item)) {
    ?>

	<div class="aimeos catalog-detail" itemscope itemtype="http://schema.org/Product" data-jsonurl="<?php 
    echo $enc->attr($this->link('client/jsonapi/url'));
    ?>">
		<div class="container-xxl">

			<!-- catalog.detail.navigator -->
			<!-- navigator template added by client -->
			<!-- catalog.detail.navigator -->

			<article class="product row <?php 
    echo $enc->attr($this->detail_product_item->get_config_value('css-class'));
    ?>"
				data-id="<?php 
    echo $this->detail_product_item->get_id();
    ?>" data-reqstock="<?php 
    echo $reqstock;
    ?>"
				data-varattributes="<?php 
    echo $enc->attr($var_attr);
    ?>">

				<div class="col-sm-6">

					<?php 
    echo $this->partial($this->config('client/html/common/partials/badges', 'common/partials/badges'));
    ?>

					<?php 
    echo $this->partial(
        /** client/html/catalog/detail/partials/image
         * Relative path to the detail image partial template file
         *
         * Partials are templates which are reused in other templates and generate
         * reoccuring blocks filled with data from the assigned values. The image
         * partial creates an HTML block for the catalog detail images.
         *
         * @param string Relative path to the template file
         * @since 2017.01
         */
        $this->config('client/html/catalog/detail/partials/image', 'catalog/detail/image'),
        ['mediaItems' => $this->get('detailMediaItems', map()), 'params' => $this->param()]
    );
    ?>

				</div>

				<div class="col-sm-6">

					<div class="catalog-detail-basic" aria-label="<?php 
    echo $enc->attr($this->translate('client', 'Product information'));
    ?>">
						<?php 
    if (!($suppliers = $this->detail_product_item->get_ref_items('supplier'))->is_empty()) {
        $name = $suppliers->get_name()->first();
        ?>
							<p class="supplier">
								<a href="<?php 
        echo $enc->attr($this->link('client/html/supplier/detail/url', ['f_supid' => $suppliers->first_key(), 's_name' => $name]));
        ?>">
									<?php 
        echo $enc->html($name, $enc::TRUST);
        ?>
								</a>
							</p>
						<?php 
    } elseif ($site_item = $this->detail_product_item->get_site_item()) {
        ?>
							<p class="site"><?php 
        echo $enc->html($site_item->get_label());
        ?></p>
						<?php 
    }
    ?>

						<h1 class="name" itemprop="name"><?php 
    echo $enc->html($this->detail_product_item->get_name(), $enc::TRUST);
    ?></h1>

						<p class="code">
							<span class="name"><?php 
    echo $enc->html($this->translate('client', 'Article no.'), $enc::TRUST);
    ?>: </span>
							<span class="value" itemprop="sku"><?php 
    echo $enc->html($this->detail_product_item->get_code());
    ?></span>
						</p>

						<?php 
    if ($this->detail_product_item->get_rating() > 0) {
        ?>
							<div class="rating" itemscope itemprop="aggregateRating" itemtype="http://schema.org/AggregateRating">
								<span class="stars"><?php 
        echo str_repeat('★', (int) round($this->detail_product_item->get_rating()));
        ?></span>
								<span class="rating-value" itemprop="ratingValue"><?php 
        echo $enc->html($this->detail_product_item->get_rating());
        ?></span>
								<span class="ratings" itemprop="reviewCount"><?php 
        echo (int) $this->detail_product_item->get_ratings();
        ?></span>
							</div>
						<?php 
    }
    ?>

						<?php 
    foreach ($this->detail_product_item->get_ref_items('text', 'short', 'default') as $text_item) {
        ?>
							<div class="short" itemprop="description"><?php 
        echo $enc->html($text_item->get_content(), $enc::TRUST);
        ?></div>
						<?php 
    }
    ?>

					</div>


					<div class="catalog-detail-basket" itemscope itemprop="offers" itemtype="http://schema.org/Offer"
						aria-label="<?php 
    echo $enc->attr($this->translate('client', 'Product price'));
    ?>">

						<div class="price-list">
							<div class="articleitem price price-actual" data-prodid="<?php 
    echo $enc->attr($this->detail_product_item->get_id());
    ?>">
								<?php 
    echo $this->partial($this->config('client/html/common/partials/price', 'common/partials/price'), ['prices' => $this->detail_product_item->get_ref_items('price', null, 'default')]);
    ?>
							</div>

							<?php 
    if ($this->detail_product_item->get_type() === 'select') {
        ?>
								<?php 
        foreach ($this->detail_product_item->get_ref_items('product', 'default', 'default') as $prodid => $product) {
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


						<form class="basket" method="POST" action="<?php 
    echo $enc->attr($this->link('client/html/basket/standard/url'));
    ?>">
							<!-- catalog.detail.csrf -->
							<?php 
    echo $this->csrf()->formfield();
    ?>
							<!-- catalog.detail.csrf -->

							<?php 
    if ($this->detail_product_item->get_type() === 'select') {
        ?>

								<div class="catalog-detail-basket-selection items-selection">

									<?php 
        echo $this->partial(
            /** client/html/common/partials/selection
             * Relative path to the variant attribute partial template file
             *
             * Partials are templates which are reused in other templates and generate
             * reoccuring blocks filled with data from the assigned values. The selection
             * partial creates an HTML block for a list of variant product attributes
             * assigned to a selection product a customer must select from.
             *
             * The partial template files are usually stored in the templates/partials/ folder
             * of the core or the extensions. The configured path to the partial file must
             * be relative to the templates/ folder, e.g. "common/partials/selection".
             *
             * @param string Relative path to the template file
             * @since 2015.04
             * @see client/html/common/partials/attribute
             */
            $this->config('client/html/common/partials/selection', 'common/partials/selection'),
            ['productItems' => $this->detail_product_item->get_ref_items('product', null, 'default'), 'productItem' => $this->detail_product_item, 'attributeTypes' => $attr_types]
        );
        ?>

								</div>

							<?php 
    } elseif ($this->detail_product_item->get_type() === 'group') {
        ?>

								<div class="catalog-detail-basket-selection items-group">

									<?php 
        echo $this->partial(
            /** client/html/catalog/detail/partials/group
             * Relative path to the group product partial template file
             *
             * Partials are templates which are reused in other templates and generate
             * reoccuring blocks filled with data from the assigned values. The group
             * partial creates an HTML block for a list of sub-products assigned to a
             * group product a customer can select from.
             *
             * @param string Relative path to the template file
             * @since 2021.07
             * @see client/html/common/partials/attribute
             */
            $this->config('client/html/catalog/detail/partials/group', 'catalog/detail/group'),
            ['productItems' => $this->detail_product_item->get_ref_items('product', null, 'default'), 'productItem' => $this->detail_product_item, 'attributeTypes' => $attr_types]
        );
        ?>

								</div>

							<?php 
    }
    ?>

							<div class="catalog-detail-basket-attribute items-attribute">

								<?php 
    echo $this->partial(
        /** client/html/common/partials/attribute
         * Relative path to the product attribute partial template file
         *
         * Partials are templates which are reused in other templates and generate
         * reoccuring blocks filled with data from the assigned values. The attribute
         * partial creates an HTML block for a list of optional product attributes a
         * customer can select from.
         *
         * The partial template files are usually stored in the templates/partials/ folder
         * of the core or the extensions. The configured path to the partial file must
         * be relative to the templates/ folder, e.g. "partials/attribute.php".
         *
         * @param string Relative path to the template file
         * @since 2016.01
         * @see client/html/common/partials/selection
         */
        $this->config('client/html/common/partials/attribute', 'common/partials/attribute'),
        ['productItem' => $this->detail_product_item, 'attributeTypes' => $attr_types]
    );
    ?>

							</div>


							<div class="stock-list">
								<div class="articleitem <?php 
    echo !in_array($this->detail_product_item->get_type(), ['select', 'group']) ? 'stock-actual' : '';
    ?>"
									data-prodid="<?php 
    echo $enc->attr($this->detail_product_item->get_id());
    ?>">
								</div>

								<?php 
    foreach ($this->detail_product_item->get_ref_items('product', null, 'default') as $article_id => $article_item) {
        ?>
									<div class="articleitem" data-prodid="<?php 
        echo $enc->attr($article_id);
        ?>"></div>
								<?php 
    }
    ?>

							</div>


							<?php 
    if (!$this->detail_product_item->get_ref_items('price', 'default', 'default')->empty()) {
        ?>
								<div class="addbasket">
									<input type="hidden" value="add" name="<?php 
        echo $enc->attr($this->formparam('b_action'));
        ?>">
									<input type="hidden"
										name="<?php 
        echo $enc->attr($this->formparam(['b_prod', 0, 'stocktype']));
        ?>"
										value="<?php 
        echo $enc->attr($this->get('detailStockTypes', map())->first());
        ?>">
									<input type="hidden"
										name="<?php 
        echo $enc->attr($this->formparam(['b_prod', 0, 'prodid']));
        ?>"
										value="<?php 
        echo $enc->attr($this->detail_product_item->get_id());
        ?>">
									<div class="input-group">
										<?php 
        if ($this->detail_product_item->get_type() !== 'group') {
            ?>
											<input type="number" class="form-control input-lg" <?php 
            echo !$this->detail_product_item->is_available() ? 'disabled' : '';
            ?>
												name="<?php 
            echo $enc->attr($this->formparam(['b_prod', 0, 'quantity']));
            ?>"
												step="<?php 
            echo $enc->attr($this->detail_product_item->get_scale());
            ?>"
												min="<?php 
            echo $enc->attr($this->detail_product_item->get_scale());
            ?>" max="2147483647"
												value="<?php 
            echo $enc->attr($this->detail_product_item->get_scale());
            ?>" required="required"
												title="<?php 
            echo $enc->attr($this->translate('client', 'Quantity'));
            ?>"
											>
										<?php 
        }
        ?>
										<button class="btn btn-primary btn-lg btn-action" type="submit" value="" <?php 
        echo !$this->detail_product_item->is_available() ? 'disabled' : '';
        ?>>
											<?php 
        echo $enc->html($this->translate('client', 'Add to basket'), $enc::TRUST);
        ?>
										</button>
									</div>
								</div>
							<?php 
    }
    ?>

						</form>

					</div>


					<div class="catalog-detail-actions" aria-label="<?php 
    echo $enc->attr($this->translate('client', 'Product actions'));
    ?>">

						<?php 
    echo $this->partial(
        /** client/html/catalog/partials/actions
         * Relative path to the catalog actions partial template file
         *
         * Partials are templates which are reused in other templates and generate
         * reoccuring blocks filled with data from the assigned values. The actions
         * partial creates an HTML block for the product actions (pin, like and watch
         * products).
         *
         * @param string Relative path to the template file
         * @since 2017.04
         */
        $this->config('client/html/catalog/partials/actions', 'catalog/actions'),
        ['productItem' => $this->detail_product_item]
    );
    ?>


						<?php 
    echo $this->partial(
        /** client/html/catalog/partials/social
         * Relative path to the social partial template file
         *
         * Partials are templates which are reused in other templates and generate
         * reoccuring blocks filled with data from the assigned values. The social
         * partial creates an HTML block for links to social platforms in the
         * catalog components.
         *
         * @param string Relative path to the template file
         * @since 2017.04
         */
        $this->config('client/html/catalog/partials/social', 'catalog/social'),
        ['productItem' => $this->detail_product_item]
    );
    ?>

					</div>
				</div>

				<div class="col-sm-12">
					<div class="catalog-detail-additional content-block">
						<nav class="nav nav-tabs" id="nav-tab" role="tablist">

							<?php 
    if (!($text_items = $this->detail_product_item->get_ref_items('text', 'long'))->is_empty()) {
        ?>
								<a class="nav-link active" id="nav-description-tab" data-bs-toggle="tab" href="#nav-description" role="button" aria-controls="nav-description" aria-expanded="true">
									<?php 
        echo $enc->html($this->translate('client', 'Description'), $enc::TRUST);
        ?>
								</a>
							<?php 
    }
    ?>

							<?php 
    if (!$this->get('detailAttributeMap', map())->is_empty() || !$this->get('detailPropertyMap', map())->is_empty()) {
        ?>
								<a class="nav-link nav-attribute" id="nav-attribute-tab" data-bs-toggle="tab" href="#nav-attribute" role="button" aria-controls="nav-attribute" aria-expanded="false">
									<?php 
        echo $enc->html($this->translate('client', 'Characteristics'), $enc::TRUST);
        ?>
								</a>
							<?php 
    }
    ?>

							<?php 
    if (!($media_items = $this->detail_product_item->get_ref_items('media', 'download'))->is_empty()) {
        ?>
								<a class="nav-link nav-download" id="nav-download-tab" data-bs-toggle="tab" href="#nav-download" role="button" aria-controls="nav-download" aria-expanded="false">
									<?php 
        echo $enc->html($this->translate('client', 'Downloads'), $enc::TRUST);
        ?>
								</a>
							<?php 
    }
    ?>

							<a class="nav-link nav-review" id="nav-review-tab" data-bs-toggle="tab" href="#nav-review" role="button" aria-controls="nav-review" aria-expanded="false">
								<?php 
    echo $enc->html($this->translate('client', 'Reviews'), $enc::TRUST);
    ?>
								<span class="ratings"><?php 
    echo $enc->html($this->detail_product_item->get_ratings());
    ?></span>
							</a>

						</nav>

						<div class="tab-content" id="nav-tabContent">

							<?php 
    if (!($text_items = $this->detail_product_item->get_ref_items('text', 'long'))->is_empty()) {
        ?>

								<div class="tab-pane fade show active" id="nav-description" role="tabpanel" aria-labelledby="nav-description-tab"
									aria-label="<?php 
        echo $enc->attr($this->translate('client', 'Product description'));
        ?>">

									<div class="block description">

										<?php 
        foreach ($text_items as $text_item) {
            ?>
											<div class="long item"><?php 
            echo $enc->html($text_item->get_content(), $enc::TRUST);
            ?></div>
										<?php 
        }
        ?>

									</div>
								</div>

							<?php 
    }
    ?>

							<?php 
    if (!$this->get('detailAttributeMap', map())->is_empty() || !$this->get('detailPropertyMap', map())->is_empty()) {
        ?>

								<div class="tab-pane fade" id="nav-attribute" role="tabpanel" aria-labelledby="nav-attribute-tab"
									aria-label="<?php 
        echo $enc->attr($this->translate('client', 'Product attributes'));
        ?>">

									<div class="block attributes">
										<table class="attributes">
											<tbody>

												<?php 
        foreach ($this->get('detailAttributeMap', map()) as $type => $attr_items) {
            ?>
													<?php 
            foreach ($attr_items as $attr_item) {
                ?>

														<tr class="item <?php 
                echo ($ids = $attr_item->get('parent')) ? 'subproduct ' . map($ids)->prefix('subproduct-')->join(' ') : '';
                ?>">
															<td class="name"><?php 
                echo $enc->html($attr_types[$type]?->get_name() ?? $type);
                ?></td>
															<td class="value">
																<div class="media-list">

																	<?php 
                foreach ($attr_item->get_list_items('media', 'icon') as $list_item) {
                    ?>
																		<?php 
                    if (($refitem = $list_item->get_ref_item()) !== null) {
                        ?>
																			<?php 
                        echo $this->partial($this->config('client/html/common/partials/media', 'common/partials/media'), ['item' => $refitem, 'boxAttributes' => ['class' => 'media-item']]);
                        ?>
																		<?php 
                    }
                    ?>
																	<?php 
                }
                ?>

																</div><!--
																--><span class="attr-name"><?php 
                echo $enc->html($attr_item->get_name());
                ?></span>

																<?php 
                foreach ($attr_item->get_ref_items('text', 'short') as $text_item) {
                    ?>
																	<div class="attr-short"><?php 
                    echo $enc->html($text_item->get_content());
                    ?></div>
																<?php 
                }
                ?>

																<?php 
                foreach ($attr_item->get_ref_items('text', 'long') as $text_item) {
                    ?>
																	<div class="attr-long"><?php 
                    echo $enc->html($text_item->get_content());
                    ?></div>
																<?php 
                }
                ?>

															</td>
														</tr>

													<?php 
            }
            ?>
												<?php 
        }
        ?>

												<?php 
        foreach ($this->get('detailPropertyMap', map()) as $type => $prop_items) {
            ?>
													<?php 
            foreach ($prop_items as $prop_item) {
                ?>

														<tr class="item <?php 
                echo ($id = $prop_item->get('parent')) ? 'subproduct subproduct-' . $id : '';
                ?>">
															<td class="name"><?php 
                echo $enc->html($prop_types[$prop_item->get_type()]?->get_name() ?? $prop_item->get_type(), $enc::TRUST);
                ?></td>
															<td class="value"><?php 
                echo $enc->html($prop_item->get_value());
                ?></td>
														</tr>

													<?php 
            }
            ?>
												<?php 
        }
        ?>

											</tbody>
										</table>
									</div>
								</div>

							<?php 
    }
    ?>

							<?php 
    if (!($media_items = $this->detail_product_item->get_ref_items('media', 'download'))->is_empty()) {
        ?>

								<div class="tab-pane fade" id="nav-download" role="tabpanel" aria-labelledby="nav-download-tab"
									aria-label="<?php 
        echo $enc->attr($this->translate('client', 'Product downloads'));
        ?>">

									<?php 
        if (!($media_items = $this->detail_product_item->get_ref_items('media', 'download'))->is_empty()) {
            ?>

										<ul class="block downloads">

											<?php 
            foreach ($media_items as $id => $media_item) {
                ?>

												<li class="item">
													<a href="<?php 
                echo $this->content($media_item->get_url(true), $media_item->get_file_system());
                ?>"
														title="<?php 
                echo $enc->attr($media_item->get_properties('title')->first($media_item->get_label()));
                ?>">
														<img class="media-image"
															alt="<?php 
                echo $enc->attr($media_item->get_properties('title')->first($media_item->get_label()));
                ?>"
															src="<?php 
                echo $enc->attr($this->content($media_item->get_preview(), $media_item->get_file_system()));
                ?>"
															srcset="<?php 
                echo $enc->attr($this->imageset($media_item->get_previews(true), $media_item->get_file_system()));
                ?>"
														>
														<span class="media-name"><?php 
                echo $enc->html($media_item->get_properties('title')->first($media_item->get_label()));
                ?></span>
													</a>
												</li>

											<?php 
            }
            ?>

										</ul>

									<?php 
        }
        ?>

								</div>

							<?php 
    }
    ?>

							<div class="tab-pane fade" id="nav-review" role="tabpanel" aria-labelledby="nav-review-tab"
								aria-label="<?php 
    echo $enc->attr($this->translate('client', 'Product reviews'));
    ?>">

								<div class="reviews container-fluid block" data-productid="<?php 
    echo $enc->attr($this->detail_product_item->get_id());
    ?>">
									<div class="row">
										<div class="col-md-4 rating-list">
											<div class="rating-numbers">
												<div class="rating-num"><?php 
    echo number_format($this->detail_product_item->get_rating(), 1);
    ?>/5</div>
												<div class="rating-total"><?php 
    echo $enc->html(sprintf($this->translate('client', '%1$d review', '%1$d reviews', $this->detail_product_item->get_ratings()), $this->detail_product_item->get_ratings()));
    ?></div>
												<div class="rating-stars"><?php 
    echo str_repeat('★', (int) round($this->detail_product_item->get_rating()));
    ?></div>
											</div>
											<table class="rating-dist" data-ratings="<?php 
    echo $enc->attr($this->detail_product_item->get_ratings());
    ?>">
												<thead>
													<tr>
														<th class="rating-label" scope="col"><?php 
    echo $enc->html($this->translate('client', 'Stars'));
    ?></th>
														<th class="rating-percent" scope="col"><?php 
    echo $enc->html($this->translate('client', 'Percentage'));
    ?></th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="rating-label"><label for="rating-5">★★★★★</label></td>
														<td class="rating-percent"><progress id="rating-5" value="0" max="100">0</progress></td>
													</tr>
													<tr>
														<td class="rating-label"><label for="rating-4">★★★★</label></td>
														<td class="rating-percent"><progress id="rating-4" value="0" max="100">0</progress></td>
													</tr>
													<tr>
														<td class="rating-label"><label for="rating-3">★★★</label></td>
														<td class="rating-percent"><progress id="rating-3" value="0" max="100">0</progress></td>
													</tr>
													<tr>
														<td class="rating-label"><label for="rating-2">★★</label></td>
														<td class="rating-percent"><progress id="rating-2" value="0" max="100">0</progress></td>
													</tr>
													<tr>
														<td class="rating-label"><label for="rating-1">★</label></td>
														<td class="rating-percent"><progress id="rating-1" value="0" max="100">0</progress></td>
													</tr>
												</tbody>
											</table>
										</div>
										<div class="col-md-8 review-list">
											<div class="sort">
												<span><?php 
    echo $enc->html($this->translate('client', 'Sort by:'), $enc::TRUST);
    ?></span>
												<ul>
													<li>
														<a class="sort-option option-ctime active" href="<?php 
    echo $enc->attr($this->link('client/jsonapi/url', ['resource' => 'review', 'filter' => ['f_refid' => $this->detail_product_item->get_id()], 'sort' => '-ctime']));
    ?>">
															<?php 
    echo $enc->html($this->translate('client', 'Latest'), $enc::TRUST);
    ?>
														</a>
													</li>
													<li>
														<a class="sort-option option-rating" href="<?php 
    echo $enc->attr($this->link('client/jsonapi/url', ['resource' => 'review', 'filter' => ['f_refid' => $this->detail_product_item->get_id()], 'sort' => '-rating,-ctime']));
    ?>">
															<?php 
    echo $enc->html($this->translate('client', 'Rating'), $enc::TRUST);
    ?>
														</a>
													</li>
												</ul>
											</div>
											<div class="review-items">
												<div class="review-item prototype">
													<div class="review-name"></div>
													<div class="review-ctime"></div>
													<div class="review-rating">★</div>
													<div class="review-comment"></div>
													<div class="review-response">
														<div class="review-vendor"><?php 
    echo $enc->html($this->translate('client', 'Vendor response'));
    ?></div>
													</div>
													<div class="review-show"><a href="#"><?php 
    echo $enc->html($this->translate('client', 'Show'));
    ?></a></div><!--
												--></div>
											</div>
											<a class="btn btn-primary more" href="#"><?php 
    echo $enc->html($this->translate('client', 'More reviews'), $enc::TRUST);
    ?></a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>


					<?php 
    if ($this->detail_product_item->get_type() === 'bundle' && !($products = $this->detail_product_item->get_ref_items('product', null, 'default'))->is_empty()) {
        ?>

						<div class="section catalog-detail-bundle content-block">
							<h2 class="header"><?php 
        echo $this->translate('client', 'Bundled products');
        ?></h2>
							<div class="section">
								<?php 
        echo $this->partial($this->config('client/html/common/partials/products', 'common/partials/products'), ['products' => $products, 'itemprop' => 'isRelatedTo']);
        ?>
							</div>
						</div>

					<?php 
    }
    ?>


					<?php 
    if (!($products = $this->detail_product_item->get_ref_items('product', null, 'suggestion'))->is_empty()) {
        ?>

						<div class="section catalog-detail-suggest content-block">
							<h2 class="header"><?php 
        echo $this->translate('client', 'Suggested products');
        ?></h2>
							<?php 
        echo $this->partial($this->config('client/html/common/partials/products', 'common/partials/products'), ['basket-add' => $this->config('client/html/catalog/detail/basket-add', false), 'products' => $products, 'itemprop' => 'isRelatedTo']);
        ?>
						</div>

					<?php 
    }
    ?>


					<?php 
    if (!($products = $this->detail_product_item->get_ref_items('product', null, 'bought-together'))->is_empty()) {
        ?>

						<div class="section catalog-detail-bought content-block">
							<h2 class="header"><?php 
        echo $this->translate('client', 'Other customers also bought');
        ?></h2>
							<?php 
        echo $this->partial($this->config('client/html/common/partials/products', 'common/partials/products'), ['basket-add' => $this->config('client/html/catalog/detail/basket-add', false), 'products' => $products, 'itemprop' => 'isRelatedTo']);
        ?>
						</div>

					<?php 
    }
    ?>

					<?php 
    if (!($supplier_items = $this->detail_product_item->get_ref_items('supplier', null, 'default'))->is_empty()) {
        ?>
						<div class="catalog-detail-supplier content-block">
							<h2 class="header"><?php 
        echo $this->translate('client', 'Supplier information');
        ?></h2>

							<?php 
        foreach ($supplier_items as $supplier_item) {
            ?>

								<div class="supplier-content">

									<?php 
            if (($media_item = $supplier_item->get_ref_items('media', 'default', 'default')->first()) !== null) {
                ?>
										<div class="media-item">
											<img loading="lazy" class="supplier-image"
												alt="<?php 
                echo $enc->attr($media_item->get_properties('title')->first());
                ?>"
												src="<?php 
                echo $enc->attr($this->content($media_item->get_preview(), $media_item->get_file_system()));
                ?>"
												<?php 
                if (!empty($media_item->get_previews())) {
                    ?>
													srcset="<?php 
                    echo $enc->attr($this->imageset($media_item->get_previews(true), $media_item->get_file_system()));
                    ?>"
													sizes="<?php 
                    echo $enc->attr($this->config('client/html/common/imageset-sizes', '(min-width: 260px) 240px, 100vw'));
                    ?>"
												<?php 
                }
                ?>
											>
										</div>
									<?php 
            }
            ?>

									<h3 class="supplier-name">
										<?php 
            echo $enc->html($supplier_item->get_name(), $enc::TRUST);
            ?>

										<?php 
            if (($addr_item = $supplier_item->get_address_items()->first()) !== null) {
                ?>
											<span class="supplier-address">(<?php 
                echo $enc->html($addr_item->get_city());
                ?>, <?php 
                echo $enc->html($addr_item->get_country_id());
                ?>)</span>
										<?php 
            }
            ?>
									</h3>

									<?php 
            foreach ($supplier_item->get_ref_items('text', 'short', 'default') as $text_item) {
                ?>
										<p class="supplier-short"><?php 
                echo $enc->html($text_item->get_content(), $enc::TRUST);
                ?></p>
									<?php 
            }
            ?>

									<?php 
            foreach ($supplier_item->get_ref_items('text', 'long', 'default') as $text_item) {
                ?>
										<p class="supplier-long"><?php 
                echo $enc->html($text_item->get_content(), $enc::TRUST);
                ?></p>
									<?php 
            }
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
			</article>

		</div>
	</div>

<?php 
}