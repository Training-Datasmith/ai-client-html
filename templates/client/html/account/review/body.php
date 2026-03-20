<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2020-2026
 */
/** client/html/account/review/url/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2020.10
 * @see client/html/account/review/url/controller
 * @see client/html/account/review/url/action
 * @see client/html/account/review/url/config
 */
/** client/html/account/review/url/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2020.10
 * @see client/html/account/review/url/target
 * @see client/html/account/review/url/action
 * @see client/html/account/review/url/config
 */
/** client/html/account/review/url/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2020.10
 * @see client/html/account/review/url/target
 * @see client/html/account/review/url/controller
 * @see client/html/account/review/url/config
 */
/** client/html/account/review/url/config
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
 * @since 2020.10
 * @see client/html/account/review/url/target
 * @see client/html/account/review/url/controller
 * @see client/html/account/review/url/action
 * @see client/html/url/config
 */
/** client/html/account/review/url/filter
 * Removes parameters for the detail page before generating the URL
 *
 * For SEO, it's nice to have URLs which contains only required parameters.
 * This setting removes the listed parameters from the URLs. Keep care to
 * remove no required parameters!
 *
 * @param array List of parameter names to remove
 * @since 2020.10
 * @see client/html/account/review/url/target
 * @see client/html/account/review/url/controller
 * @see client/html/account/review/url/action
 * @see client/html/account/review/url/config
 */
$enc = $this->encoder();
if (!($order_products = $this->get('reviewProductItems', map()))->is_empty()) {
    ?>

	<div class="section aimeos account-review" data-jsonurl="<?php 
    echo $enc->attr($this->link('client/jsonapi/url'));
    ?>">
		<div class="container-xxl">

			<h2 class="header"><?php 
    echo $this->translate('client', 'Reviews');
    ?></h2>

			<form method="POST" action="<?php 
    echo $enc->attr($this->link('client/html/account/review/url'));
    ?>">
				<?php 
    echo $this->csrf()->formfield();
    ?>

				<?php 
    foreach ($order_products as $prod_id => $order_product) {
        ?>
					<?php 
        if ($product_item = $order_product->get_product_item()) {
            ?>
						<?php 
            $images = $product_item?->get_ref_items('media', 'default', 'default');
            if ($images?->is_empty()) {
                $images = $order_product->get_parent_product_item()?->get_ref_items('media', 'default', 'default');
            }
            ?>

						<div class="review-item">
							<input type="hidden" value="<?php 
            echo $enc->attr($order_product->get_id());
            ?>"
								name="<?php 
            echo $enc->attr($this->formparam(['review', $prod_id, 'review.orderproductid']));
            ?>"
							>
							<div class="row">
								<div class="col-md-6">
									<div class="row">
										<div class="col-6">
											<?php 
            if ($image = $images?->first()) {
                ?>
												<img class="review-image"
													sizes="<?php 
                echo $enc->attr($this->config('client/html/common/imageset-sizes', '(min-width: 260px) 240px, 100vw'));
                ?>"
													src="<?php 
                echo $enc->attr($this->content($image->get_preview(), $image->get_file_system()));
                ?>"
													srcset="<?php 
                echo $enc->attr($this->imageset($image->get_previews(true), $image->get_file_system()));
                ?>"
													alt="<?php 
                echo $enc->attr($image->get_properties('title')->first());
                ?>"
												>
											<?php 
            }
            ?>
										</div>
										<div class="col-6">
											<h3 class="review-name"><?php 
            echo $enc->html($order_product->get_parent_product_item()?->get_name() ?: $product_item?->get_name());
            ?></h3>
											<h4><?php 
            echo $enc->html($this->translate('client', 'Your rating'));
            ?></h4>
											<div class="review-rating">
												<div class="rating-line">
													<input id="rating-<?php 
            echo $enc->attr($prod_id);
            ?>-5" class="rating rating-5" required
														type="radio" value="5" name="<?php 
            echo $this->formparam(['review', $prod_id, 'review.rating']);
            ?>"
														<?php 
            echo $this->param('review/' . $prod_id . '/review.rating') == 5 ? 'selected' : '';
            ?>
													>
													<label for="rating-<?php 
            echo $enc->attr($prod_id);
            ?>-5">★★★★★</label>
												</div>
												<div class="rating-line">
													<input id="rating-<?php 
            echo $enc->attr($prod_id);
            ?>-4" class="rating rating-4" required
														type="radio" value="4" name="<?php 
            echo $this->formparam(['review', $prod_id, 'review.rating']);
            ?>"
														<?php 
            echo $this->param('review/' . $prod_id . '/review.rating') == 4 ? 'selected' : '';
            ?>
													>
													<label for="rating-<?php 
            echo $enc->attr($prod_id);
            ?>-4">★★★★</label>
													</div>
												<div class="rating-line">
													<input id="rating-<?php 
            echo $enc->attr($prod_id);
            ?>-3" class="rating rating-3" required
														type="radio" value="3" name="<?php 
            echo $this->formparam(['review', $prod_id, 'review.rating']);
            ?>"
														<?php 
            echo $this->param('review/' . $prod_id . '/review.rating') == 3 ? 'selected' : '';
            ?>
													>
													<label for="rating-<?php 
            echo $enc->attr($prod_id);
            ?>-3">★★★</label>
													</div>
												<div class="rating-line">
													<input id="rating-<?php 
            echo $enc->attr($prod_id);
            ?>-2" class="rating rating-2" required
														type="radio" value="2" name="<?php 
            echo $this->formparam(['review', $prod_id, 'review.rating']);
            ?>"
														<?php 
            echo $this->param('review/' . $prod_id . '/review.rating') == 2 ? 'selected' : '';
            ?>
													>
													<label for="rating-<?php 
            echo $enc->attr($prod_id);
            ?>-2">★★</label>
													</div>
												<div class="rating-line">
													<input id="rating-<?php 
            echo $enc->attr($prod_id);
            ?>-1" class="rating rating-1" required
														type="radio" value="1" name="<?php 
            echo $this->formparam(['review', $prod_id, 'review.rating']);
            ?>"
														<?php 
            echo $this->param('review/' . $prod_id . '/review.rating') == 1 ? 'selected' : '';
            ?>
													>
													<label for="rating-<?php 
            echo $enc->attr($prod_id);
            ?>-1">★</label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<h4><?php 
            echo $enc->html($this->translate('client', 'Your review'));
            ?></h4>
									<textarea class="review-comment" maxlength="1024"
										name="<?php 
            echo $this->formparam(['review', $prod_id, 'review.comment']);
            ?>"
										placeholder="<?php 
            echo $enc->attr($this->translate('client', 'What do you think about the product'));
            ?>">
										<?php 
            echo $enc->html($this->param('review/' . $prod_id . '/review.comment'));
            ?>
									</textarea>
								</div>
							</div>
						</div>

					<?php 
        }
        ?>
				<?php 
    }
    ?>

				<button type="submit" class="btn btn-primary"><?php 
    echo $enc->html($this->translate('client', 'Submit'));
    ?></button>

			</form>
		</div>
	</div>

<?php 
}