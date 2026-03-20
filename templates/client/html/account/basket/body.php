<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2022-2026
 */
/** client/html/account/basket/url/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2022.10
 * @see client/html/account/basket/url/controller
 * @see client/html/account/basket/url/action
 * @see client/html/account/basket/url/config
 * @see client/html/account/basket/url/filter
 */
/** client/html/account/basket/url/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2022.10
 * @see client/html/account/basket/url/target
 * @see client/html/account/basket/url/action
 * @see client/html/account/basket/url/config
 * @see client/html/account/basket/url/filter
 */
/** client/html/account/basket/url/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2022.10
 * @see client/html/account/basket/url/target
 * @see client/html/account/basket/url/controller
 * @see client/html/account/basket/url/config
 * @see client/html/account/basket/url/filter
 */
/** client/html/account/basket/url/config
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
 * @since 2022.10
 * @see client/html/account/basket/url/target
 * @see client/html/account/basket/url/controller
 * @see client/html/account/basket/url/action
 * @see client/html/account/basket/url/filter
 */
/** client/html/account/basket/url/filter
 * Removes parameters for the detail page before generating the URL
 *
 * This setting removes the listed parameters from the URLs. Keep care to
 * remove no required parameters!
 *
 * @param array List of parameter names to remove
 * @since 2022.10
 * @see client/html/account/basket/url/target
 * @see client/html/account/basket/url/controller
 * @see client/html/account/basket/url/action
 * @see client/html/account/basket/url/config
 */
/** client/html/account/basket/summary/address
 * Relative path to the HTML template of the account basket address partial.
 *
 * The template file contains the HTML code and processing instructions
 * to generate the result shown in the body of the frontend. The
 * configuration string is the path to the template file relative
 * to the templates directory (usually in templates/client/html).
 *
 * You can overwrite the template file configuration in extensions and
 * provide alternative templates. These alternative templates should be
 * named like the default one but suffixed by
 * an unique name. You may use the name of your project for this. If
 * you've implemented an alternative client class as well, it
 * should be suffixed by the name of the new class.
 *
 * @param string Relative path to the template creating the HTML fragment
 * @since 2022.10
 */
/** client/html/account/basket/summary/service
 * Relative path to the HTML template of the account basket service partial.
 *
 * The template file contains the HTML code and processing instructions
 * to generate the result shown in the body of the frontend. The
 * configuration string is the path to the template file relative
 * to the templates directory (usually in templates/client/html).
 *
 * You can overwrite the template file configuration in extensions and
 * provide alternative templates. These alternative templates should be
 * named like the default one but suffixed by
 * an unique name. You may use the name of your project for this. If
 * you've implemented an alternative client class as well, it
 * should be suffixed by the name of the new class.
 *
 * @param string Relative path to the template creating the HTML fragment
 * @since 2022.10
 */
/** client/html/account/basket/summary/detail
 * Relative path to the HTML template of the account basket details partial.
 *
 * The template file contains the HTML code and processing instructions
 * to generate the result shown in the body of the frontend. The
 * configuration string is the path to the template file relative
 * to the templates directory (usually in templates/client/html).
 *
 * You can overwrite the template file configuration in extensions and
 * provide alternative templates. These alternative templates should be
 * named like the default one but suffixed by
 * an unique name. You may use the name of your project for this. If
 * you've implemented an alternative client class as well, it
 * should be suffixed by the name of the new class.
 *
 * @param string Relative path to the template creating the HTML fragment
 * @since 2022.10
 */
$enc = $this->encoder();
if (!$this->get('basketItems', map())->is_empty()) {
    ?>

	<div class="section aimeos account-basket" data-jsonurl="<?php 
    echo $enc->attr($this->link('client/jsonapi/url'));
    ?>">
		<div class="container-xxl">

			<h2 class="header"><?php 
    echo $enc->html($this->translate('client', 'Saved baskets'), $enc::TRUST);
    ?></h2>

			<div class="basket-list">

				<?php 
    foreach ($this->get('basketItems', []) as $basket_item) {
        ?>
					<?php 
        if ($basket = $basket_item->get_item()) {
            ?>

						<div class="basket-item row">

							<div class="col-12 col-md-5">
								<h2 class="basket-basic">
									<span class="name">
										<?php 
            echo $enc->html($this->translate('client', 'Name'), $enc::TRUST);
            ?>
									</span>
									<span class="value">
										<?php 
            echo $enc->html($basket_item->get_name());
            ?>
									</span>
								</h2>
							</div>

							<div class="col-6 col-md-5">
								<a class="btn btn-primary delete" href="<?php 
            echo $enc->attr($this->link('client/html/account/basket/url', ['bas_action' => 'delete', 'bas_id' => $basket_item->get_id()]));
            ?>">
									<?php 
            echo $enc->html($this->translate('client', 'Delete'));
            ?>
								</a>
							</div>

							<div class="action col-6 col-md-2">
								<a class="btn btn-secondary show" href="#"><?php 
            echo $enc->html($this->translate('client', 'Show'));
            ?></a>
								<a class="btn btn-secondary close hidden" href="#"><?php 
            echo $enc->html($this->translate('client', 'Close'));
            ?></a>
							</div>

							<div class="account-basket-detail common-summary col-12" style="display: none">

								<h2 class="header"><?php 
            echo $enc->html($this->translate('client', 'Basket details'), $enc::TRUST);
            ?></h2>

								<div class="common-summary-address row">
									<div class="item payment col-sm-6">
										<div class="header">
											<h3><?php 
            echo $enc->html($this->translate('client', 'Billing address'), $enc::TRUST);
            ?></h3>
										</div>

										<div class="content">
											<?php 
            if (!empty($basket->get_address('payment'))) {
                ?>
												<?php 
                echo $this->partial($this->config('client/html/account/basket/summary/address', 'common/summary/address'), ['addresses' => $basket->get_address('payment')]);
                ?>
											<?php 
            }
            ?>
										</div>
									</div><!--

									--><div class="item delivery col-sm-6">
										<div class="header">
											<h3><?php 
            echo $enc->html($this->translate('client', 'Delivery address'), $enc::TRUST);
            ?></h3>
										</div>

										<div class="content">
											<?php 
            if (!empty($basket->get_address('delivery'))) {
                ?>
												<?php 
                echo $this->partial($this->config('client/html/account/basket/summary/address', 'common/summary/address'), ['addresses' => $basket->get_address('delivery')]);
                ?>
											<?php 
            } else {
                ?>
												<?php 
                echo $enc->html($this->translate('client', 'like billing address'), $enc::TRUST);
                ?>
											<?php 
            }
            ?>
										</div>
									</div>
								</div>


								<div class="common-summary-service row">
									<div class="item delivery col-sm-6">
										<div class="header">
											<h3><?php 
            echo $enc->html($this->translate('client', 'delivery'), $enc::TRUST);
            ?></h3>
										</div>

										<div class="content">
											<?php 
            if (!empty($basket->get_service('delivery'))) {
                ?>
												<?php 
                echo $this->partial($this->config('client/html/account/basket/summary/service', 'common/summary/service'), ['service' => $basket->get_service('delivery'), 'type' => 'delivery']);
                ?>
											<?php 
            }
            ?>
										</div>
									</div><!--

									--><div class="item payment col-sm-6">
										<div class="header">
											<h3><?php 
            echo $enc->html($this->translate('client', 'payment'), $enc::TRUST);
            ?></h3>
										</div>

										<div class="content">
											<?php 
            if (!empty($basket->get_service('payment'))) {
                ?>
												<?php 
                echo $this->partial($this->config('client/html/account/basket/summary/service', 'common/summary/service'), ['service' => $basket->get_service('payment'), 'type' => 'payment']);
                ?>
											<?php 
            }
            ?>
										</div>
									</div>

								</div>


								<div class="common-summary-additional row">
									<div class="item coupon col-sm-4">
										<div class="header">
											<h3><?php 
            echo $enc->html($this->translate('client', 'Coupon codes'), $enc::TRUST);
            ?></h3>
										</div>

										<div class="content">
											<?php 
            if (!($coupons = $basket->get_coupons())->is_empty()) {
                ?>
												<ul class="attr-list">
													<?php 
                foreach ($coupons as $code => $products) {
                    ?>
														<li class="attr-item"><?php 
                    echo $enc->html($code);
                    ?></li>
													<?php 
                }
                ?>
												</ul>
											<?php 
            }
            ?>
										</div>
									</div><!--

									--><div class="item customerref col-sm-4">
										<div class="header">
											<h3><?php 
            echo $enc->html($this->translate('client', 'Your reference number'), $enc::TRUST);
            ?></h3>
										</div>

										<div class="content">
											<?php 
            echo $enc->html($basket->get_customer_reference());
            ?>
										</div>
									</div><!--

									--><div class="item comment col-sm-4">
										<div class="header">
											<h3><?php 
            echo $enc->html($this->translate('client', 'Your comment'), $enc::TRUST);
            ?></h3>
										</div>

										<div class="content">
											<?php 
            echo $enc->html($basket->get_comment());
            ?>
										</div>
									</div>
								</div>


								<div class="common-summary-detail row">
									<div class="header col-sm-12">
										<h2><?php 
            echo $enc->html($this->translate('client', 'Details'), $enc::TRUST);
            ?></h2>
									</div>

									<div class="basket col-sm-12">
										<?php 
            echo $this->partial($this->config('client/html/account/basket/summary/detail', 'common/summary/detail'), ['summaryBasket' => $basket]);
            ?>
									</div>
								</div>


								<form method="POST" action="<?php 
            echo $enc->attr($this->link('client/html/basket/standard/url'));
            ?>">
									<?php 
            echo $this->csrf()->formfield();
            ?>

									<input type="hidden" value="add" name="<?php 
            echo $enc->attr($this->formparam('b_action'));
            ?>">

									<?php 
            foreach ($basket->get_products() as $pos => $order_product) {
                ?>
										<input type="hidden" name="<?php 
                echo $enc->attr($this->formparam(['b_prod', $pos, 'prodid']));
                ?>" value="<?php 
                echo $enc->attr($order_product->get_parent_product_id() ?: $order_product->get_product_id());
                ?>">
										<input type="hidden" name="<?php 
                echo $enc->attr($this->formparam(['b_prod', $pos, 'quantity']));
                ?>" value="<?php 
                echo $enc->attr($order_product->get_quantity());
                ?>">

										<?php 
                foreach ($order_product->get_attribute_items('variant') as $attr_item) {
                    ?>
											<input type="hidden" value="<?php 
                    echo $enc->attr($attr_item->get_attribute_id());
                    ?>"
												name="<?php 
                    echo $enc->attr($this->formparam(['b_prod', $pos, 'attrvarid', $attr_item->get_code()]));
                    ?>"
											>
										<?php 
                }
                ?>

										<?php 
                foreach ($order_product->get_attribute_items('custom') as $attr_item) {
                    ?>
											<input type="hidden" value="<?php 
                    echo $enc->attr($attr_item->get_value());
                    ?>"
												name="<?php 
                    echo $enc->attr($this->formparam(['b_prod', $pos, 'attrcustid', $attr_item->get_attribute_id()]));
                    ?>"
											>
										<?php 
                }
                ?>

										<?php 
                foreach ($order_product->get_attribute_items('config') as $attr_item) {
                    ?>
											<input type="hidden" value="<?php 
                    echo $enc->attr($attr_item->get_attribute_id());
                    ?>"
												name="<?php 
                    echo $enc->attr($this->formparam(['b_prod', $pos, 'attrconfid', 'id', '']));
                    ?>"
											>
											<input type="hidden" value="<?php 
                    echo $enc->attr($attr_item->get_quantity());
                    ?>"
												name="<?php 
                    echo $enc->attr($this->formparam(['b_prod', $pos, 'attrconfid', 'qty', '']));
                    ?>"
											>
										<?php 
                }
                ?>
									<?php 
            }
            ?>

									<div class="button-group">
										<a class="btn btn-secondary close" href="<?php 
            echo $enc->attr($this->link('client/html/account/basket/url', [], ['account-basket']));
            ?>">
											<?php 
            echo $enc->html($this->translate('client', 'Close'), $enc::TRUST);
            ?>
										</a>
										<button class="btn btn-primary btn-action">
											<?php 
            echo $enc->html($this->translate('client', 'Add to basket'), $enc::TRUST);
            ?>
										</button>
									</div>
								</form>

							</div>
						</div>

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
}