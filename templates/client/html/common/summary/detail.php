<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
/* Available data:
 * - orderItem : Order item (optional, only available after checkout)
 * - summaryBasket : Order base item (basket) including products, addresses, services, etc.
 * - summaryEnableModify : True if users are allowed to change the basket content, false if not (optional)
 * - summaryErrorCodes : List of error codes including those for the products (optional)
 */
$total_quantity = 0;
$enc = $this->encoder();
$detail_target = $this->config('client/html/catalog/detail/url/target');
$detail_controller = $this->config('client/html/catalog/detail/url/controller', 'catalog');
$detail_action = $this->config('client/html/catalog/detail/url/action', 'detail');
$detail_config = $this->config('client/html/catalog/detail/url/config', ['absoluteUri' => 1]);
$detail_filter = array_flip($this->config('client/html/catalog/detail/url/filter', []));
unset($detail_filter['d_prodid']);
// product name isn't suitable because different from URL
/** client/html/account/download/url/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2016.02
 * @see client/html/account/download/url/controller
 * @see client/html/account/download/url/action
 * @see client/html/account/download/url/config
 */
/** client/html/account/download/url/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2016.02
 * @see client/html/account/download/url/target
 * @see client/html/account/download/url/action
 * @see client/html/account/download/url/config
 */
/** client/html/account/download/url/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2016.02
 * @see client/html/account/download/url/target
 * @see client/html/account/download/url/controller
 * @see client/html/account/download/url/config
 */
/** client/html/account/download/url/config
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
 * @since 2016.02
 * @see client/html/account/download/url/target
 * @see client/html/account/download/url/controller
 * @see client/html/account/download/url/action
 */
/** client/html/common/summary/detail/product/attribute/types
 * List of attribute type codes that should be displayed in the basket along with their product
 *
 * The products in the basket can store attributes that exactly define an ordered
 * product or which are important for the back office. By default, the product
 * variant attributes are always displayed and the configurable product attributes
 * are displayed separately.
 *
 * Additional attributes for each ordered product can be added by basket plugins.
 * Depending on the attribute types and if they should be shown to the customers,
 * you need to extend the list of displayed attribute types ab adding their codes
 * to the configurable list.
 *
 * @param array List of attribute type codes
 * @since 2014.09
 */
$attr_types = $this->config('client/html/common/summary/detail/product/attribute/types', ['variant', 'config', 'custom']);
$price = $this->summary_basket->get_price();
$precision = $price->get_precision();
$price_taxflag = $price->get_tax_flag();
$price_currency = $this->translate('currency', $price->get_currency_id());
$pricetype = 'price:default';
$pricefmt = $this->translate('client/code', $pricetype);
/// Price format with price value (%1$s) and currency (%2$s)
$price_format = $this->translate('client/code', 'price:default', null, 0, false) ?: $this->translate('client', '%1$s %2$s');
/// Tax format with tax rate (%1$s) and tax name (%2$s)
$tax_format_incl = $this->translate('client', 'Incl. %1$s%% %2$s');
/// Tax format with tax rate (%1$s) and tax name (%2$s)
$tax_format_excl = $this->translate('client', '+ %1$s%% %2$s');
$modify = $this->get('summaryEnableModify', false);
$errors = $this->get('summaryErrorCodes', []);
?>
<div>
	<div class="headline row g-0">
		<div class="col-7 offset-5 col-sm-6 offset-sm-6">
			<div class="row g-0">
				<div class="quantity col-10 col-sm-5"><?php 
echo $enc->html($this->translate('client', 'Quantity'), $enc::TRUST);
?></div>
				<div class="unitprice col-md-3"><?php 
echo $enc->html($this->translate('client', 'Price'), $enc::TRUST);
?></div>
				<div class="price col-sm-5 col-md-3"><?php 
echo $enc->html($this->translate('client', 'Sum'), $enc::TRUST);
?></div>
				<?php 
if ($modify) {
    ?>
					<div class="action col-2 col-sm-2 col-md-1">
						<?php 
    $pos = [];
    foreach ($this->summary_basket->get_products() as $position => $product) {
        if (($product->get_flags() & \Aimeos\M_Shop\Order\Item\Product\Base::FLAG_IMMUTABLE) == 0) {
            $pos[] = $position;
        }
    }
    ?>
						<a class="minibutton delete" title="<?php 
    echo $enc->attr($this->translate('client', 'Remove'));
    ?>"
							href="<?php 
    echo $enc->attr($this->link('client/html/basket/standard/url', ['b_action' => 'delete', 'b_position' => $pos]));
    ?>">
						</a>
					</div>
				<?php 
}
?>
			</div>
		</div>
	</div>

	<?php 
foreach ($this->summary_basket->get_products()->group_by('order.product.vendor')->ksort() as $vendor => $list) {
    ?>

		<?php 
    if ($vendor) {
        ?>
			<div class="supplier">
				<h3 class="supplier-name"><?php 
        echo $enc->html($vendor);
        ?></h3>
			</div>
		<?php 
    }
    ?>

		<?php 
    foreach ($list as $position => $product) {
        $total_quantity += $product->get_quantity();
        ?>
			<div class="product-item row g-0 <?php 
        echo isset($errors['product'][$position]) ? 'error' : '';
        ?>">
				<div class="col-5 col-sm-6">
					<div class="row g-0">
						<div class="status col-1">
							<?php 
        if (($status = $product->get_status_delivery()) >= 0) {
            $key = 'stat:' . $status;
            ?>
								<?php 
            echo $enc->html($this->translate('mshop/code', $key));
            ?>
							<?php 
        }
        ?>
						</div>
						<div class="image col-11 col-lg-3">
							<?php 
        if (($url = $product->get_media_url()) != '') {
            ?>
								<img class="detail" src="<?php 
            echo $enc->attr($this->content($url));
            ?>">
							<?php 
        }
        ?>
						</div>
						<div class="details col-12 col-lg-8">
							<?php 
        $url = '#';
        if (($product->get_flags() & \Aimeos\M_Shop\Order\Item\Product\Base::FLAG_IMMUTABLE) == 0) {
            $name = $product->get_name('url');
            $params = array_diff_key(['path' => $name, 'd_name' => $name, 'd_prodid' => $product->get_parent_product_id() ?: $product->get_product_id(), 'd_pos' => ''], $detail_filter);
            $url = $this->url($product->get_target() ?: $detail_target, $detail_controller, $detail_action, $params, [], $detail_config);
        }
        ?>
							<a href="<?php 
        echo $enc->attr($url);
        ?>">
								<p class="product-name"><?php 
        echo $enc->html($product->get_name(), $enc::TRUST);
        ?></p>
								<p class="code">
									<span class="name"><?php 
        echo $enc->html($this->translate('client', 'Article no.'), $enc::TRUST);
        ?></span>
									<span class="value"><?php 
        echo $product->get_product_code();
        ?></span>
								</p>
								<?php 
        if (($desc = $product->get_description()) !== '') {
            ?>
									<p class="product-description"><?php 
            echo $enc->html($desc);
            ?></p>
								<?php 
        }
        ?>
								<?php 
        foreach ($attr_types as $attr_type) {
            ?>
									<?php 
            if (!($attributes = $product->get_attribute_items($attr_type))->is_empty()) {
                ?>
										<ul class="attr-list attr-type-<?php 
                echo $enc->attr($attr_type);
                ?>">
											<?php 
                foreach ($product->get_attribute_items($attr_type) as $attribute) {
                    ?>
												<li class="attr-item attr-code-<?php 
                    echo $enc->attr($attribute->get_code());
                    ?>">
													<span class="name"><?php 
                    echo $enc->html($this->translate('client/code', $attribute->get_code()));
                    ?></span>
													<span class="value">
														<?php 
                    if ($attribute->get_quantity() > 1) {
                        ?>
															<?php 
                        echo $enc->html($attribute->get_quantity());
                        ?>×
														<?php 
                    }
                    ?>
														<?php 
                    echo $enc->html($attr_type !== 'custom' && $attribute->get_name() ? $attribute->get_name() : $attribute->get_value());
                    ?>
													</span>
												</li>
											<?php 
                }
                ?>
										</ul>
									<?php 
            }
            ?>
								<?php 
        }
        ?>
							</a>
							<?php 
        if (isset($this->order_item) && $this->order_item->get_status_payment() >= \Aimeos\M_Shop\Order\Item\Base::PAY_RECEIVED && ($product->get_status_payment() < 0 || $product->get_status_payment() >= \Aimeos\M_Shop\Order\Item\Base::PAY_RECEIVED) && $attribute = $product->get_attribute_item('download', 'hidden')) {
            ?>
								<ul class="attr-list attr-list-hidden">
									<li class="attr-item attr-code-<?php 
            echo $enc->attr($attribute->get_code());
            ?>">
										<span class="name"><?php 
            echo $enc->html($this->translate('client/code', $attribute->get_code()));
            ?></span>
										<span class="value">
											<a href="<?php 
            echo $enc->attr($this->link('client/html/account/download/url', ['dl_id' => $attribute->get_id()]));
            ?>">
												<?php 
            echo $enc->html($attribute->get_name());
            ?>
											</a>
										</span>
									</li>
								</ul>
							<?php 
        }
        ?>
							<?php 
        if (($timeframe = $product->get_timeframe()) !== '') {
            ?>
								<p class="timeframe">
									<span class="name"><?php 
            echo $enc->html($this->translate('client', 'Delivery within'));
            ?></span>
									<span class="value"><?php 
            echo $enc->html($timeframe);
            ?></span>
								</p>
							<?php 
        }
        ?>
						</div>
					</div>
				</div>
				<div class="col-7 col-sm-6">
					<div class="order row g-0">
						<div class="quantity col-10 col-sm-5">

							<?php 
        if ($modify && ($product->get_flags() & \Aimeos\M_Shop\Order\Item\Product\Base::FLAG_IMMUTABLE) == 0) {
            ?>

								<?php 
            if ($product->get_quantity() > 1) {
                ?>
									<?php 
                $basket_params = ['b_action' => 'edit', 'b_position' => $position, 'b_quantity' => $product->get_quantity() - 1];
                ?>
									<a class="minibutton change down" href="<?php 
                echo $enc->attr($this->link('client/html/basket/standard/url', $basket_params));
                ?>" title="<?php 
                echo $enc->attr($this->translate('client', 'Decrease'));
                ?>">−</a>
								<?php 
            } else {
                ?>
									&nbsp;&nbsp;&nbsp;
								<?php 
            }
            ?>

								<input class="value" type="number" required="required"
									name="<?php 
            echo $enc->attr($this->formparam(['b_prod', $position, 'quantity']));
            ?>"
									value="<?php 
            echo $enc->attr($product->get_quantity());
            ?>"
									step="<?php 
            echo $enc->attr($product->get_scale());
            ?>"
									min="<?php 
            echo $enc->attr($product->get_scale());
            ?>"
									max="2147483647" autocomplete="off"
									title="<?php 
            echo $enc->attr($this->translate('client', 'Quantity'));
            ?>"
								>
								<input type="hidden" type="text"
									name="<?php 
            echo $enc->attr($this->formparam(['b_prod', $position, 'position']));
            ?>"
									value="<?php 
            echo $enc->attr($position);
            ?>"
								>

								<?php 
            $basket_params = ['b_action' => 'edit', 'b_position' => $position, 'b_quantity' => $product->get_quantity() + 1];
            ?>
								<a class="minibutton change up" href="<?php 
            echo $enc->attr($this->link('client/html/basket/standard/url', $basket_params));
            ?>" title="<?php 
            echo $enc->attr($this->translate('client', 'Increase'));
            ?>">+</a>

							<?php 
        } else {
            ?>
								<?php 
            echo $enc->html($product->get_quantity());
            ?>
							<?php 
        }
        ?>
						</div>
						<div class="unitprice col-md-3"><?php 
        echo $enc->html(sprintf($price_format, $this->number($product->get_price()->get_value(), $precision), $price_currency));
        ?></div>
						<div class="price col-sm-5 col-md-3"><?php 
        echo $enc->html(sprintf($price_format, $this->number($product->get_price()->get_value() * $product->get_quantity(), $precision), $price_currency));
        ?></div>
						<?php 
        if ($modify) {
            ?>
						<div class="action col-2 col-sm-2 col-md-1">
							<?php 
            if (($product->get_flags() & \Aimeos\M_Shop\Order\Item\Product\Base::FLAG_IMMUTABLE) == 0) {
                ?>
								<?php 
                $basket_params = ['b_action' => 'delete', 'b_position' => $position];
                ?>
								<a class="minibutton delete" href="<?php 
                echo $enc->attr($this->link('client/html/basket/standard/url', $basket_params));
                ?>" title="<?php 
                echo $enc->attr($this->translate('client', 'Remove'));
                ?>"></a>
							<?php 
            }
            ?>
						</div>
						<?php 
        }
        ?>

					</div>
				</div>
			</div>

		<?php 
    }
    ?>
	<?php 
}
?>


	<?php 
foreach ($this->summary_basket->get_service('delivery') as $service) {
    ?>
		<?php 
    if ($service->get_price()->get_value() > 0) {
        $price_item = $service->get_price();
        ?>
			<?php 
        $price = $enc->html(sprintf($price_format, $this->number($price_item->get_value(), $price_item->get_precision()), $price_item->get_currency_id()));
        ?>
			<div class="delivery row g-0">
				<div class="col-7 col-md-6">
					<div class="row g-0">
						<div class="status col-1"></div>
						<div class="image col-11 col-lg-3">
							<?php 
        if (($url = $service->get_media_url()) != '') {
            ?>
								<img class="detail" src="<?php 
            echo $enc->attr($this->content($url));
            ?>">
							<?php 
        }
        ?>
						</div>
						<div class="details col-12 col-lg-8"><?php 
        echo $enc->html($service->get_name());
        ?></div>
					</div>
				</div>
				<div class="col-5 col-md-6">
					<div class="row g-0">
						<div class="quantity col-5">1</div>
						<div class="unitprice col-3"><?php 
        echo $price;
        ?></div>
						<div class="price col-3"><?php 
        echo $price;
        ?></div>
						<?php 
        if ($modify) {
            ?>
							<div class="action col-1"></div>
						<?php 
        }
        ?>
					</div>
				</div>
			</div>
		<?php 
    }
    ?>
	<?php 
}
?>

	<?php 
foreach ($this->summary_basket->get_service('payment') as $service) {
    ?>
		<?php 
    if ($service->get_price()->get_value() > 0) {
        $price_item = $service->get_price();
        ?>
			<?php 
        $price = $enc->html(sprintf($price_format, $this->number($price_item->get_value(), $price_item->get_precision()), $price_item->get_currency_id()));
        ?>
			<div class="payment row g-0">
				<div class="col-8 col-md-6">
					<div class="row g-0">
						<div class="status col-1"></div>
						<div class="image col-11 col-lg-3">
						<?php 
        if (($url = $service->get_media_url()) != '') {
            ?>
							<img class="detail" src="<?php 
            echo $enc->attr($this->content($url));
            ?>">
						<?php 
        }
        ?>
						</div>
						<div class="details col-12 col-lg-8"><?php 
        echo $enc->html($service->get_name());
        ?></div>
					</div>
				</div>
				<div class="col-4 col-md-6">
					<div class="row g-0">
						<div class="quantity col-5">1</div>
						<div class="unitprice col-3"><?php 
        echo $price;
        ?></div>
						<div class="price col-3"><?php 
        echo $price;
        ?></div>
						<?php 
        if ($modify) {
            ?>
							<div class="action col-1"></div>
						<?php 
        }
        ?>
					</div>
				</div>
			</div>

		<?php 
    }
    ?>
	<?php 
}
?>

	<?php 
if ($price_taxflag === false || $this->summary_basket->get_costs('delivery') + $this->summary_basket->get_costs('payment') > 0) {
    ?>
		<div class="subtotal row g-0">
			<div class="col-12 col-md-6 offset-md-6">
				<div class="row g-0">
					<div class="col-8"><?php 
    echo $enc->html($this->translate('client', 'Sub-total'));
    ?></div>
					<div class="price col-3"><?php 
    echo $enc->html(sprintf($price_format, $this->number($this->summary_basket->get_price()->get_value(), $precision), $price_currency));
    ?></div>
					<?php 
    if ($modify) {
        ?>
						<div class="action col-1"></div>
					<?php 
    }
    ?>
				</div>
			</div>
		</div>
	<?php 
}
?>

	<?php 
if (($costs = $this->summary_basket->get_costs('delivery')) > 0) {
    ?>
		<div class="delivery row g-0">
			<div class="col-12 col-md-6 offset-md-6">
				<div class="row g-0">
					<div class="col-8"><?php 
    echo $enc->html($this->translate('client', 'Shipping'));
    ?></div>
					<div class="price col-3"><?php 
    echo $enc->html(sprintf($price_format, $this->number($costs, $precision), $price_currency));
    ?></div>
					<?php 
    if ($modify) {
        ?>
					<div class="action col-1"></div>
					<?php 
    }
    ?>
				</div>
			</div>
		</div>
	<?php 
}
?>

	<?php 
if (($costs = $this->summary_basket->get_costs('payment')) > 0) {
    ?>
		<div class="payment row g-0">
			<div class="col-12 col-md-6 offset-md-6">
				<div class="row g-0">
					<div class="col-8"><?php 
    echo $enc->html($this->translate('client', 'Payment costs'));
    ?></div>
					<div class="price col-3"><?php 
    echo $enc->html(sprintf($price_format, $this->number($costs, $precision), $price_currency));
    ?></div>
					<?php 
    if ($modify) {
        ?>
						<div class="action col-1"></div>
					<?php 
    }
    ?>
				</div>
			</div>
		</div>
	<?php 
}
?>

	<?php 
if ($price_taxflag === true) {
    ?>
		<div class="total row g-0">
			<div class="col-12 col-md-6 offset-md-6">
				<div class="price-total row g-0">
					<div class="quantity col-5"><?php 
    echo $enc->html(sprintf($this->translate('client', '%1$d article', '%1$d articles', ceil($total_quantity)), round($total_quantity, 3)));
    ?></div>
					<div class="total-text col-3"><?php 
    echo $enc->html($this->translate('client', 'Total'));
    ?></div>
					<div class="price col-3"><?php 
    echo $enc->html(sprintf($price_format, $this->number($this->summary_basket->get_price()->get_value() + $this->summary_basket->get_price()->get_costs(), $precision), $price_currency));
    ?></div>
					<?php 
    if ($modify) {
        ?>
						<div class="action col-1"></div>
					<?php 
    }
    ?>
				</div>
			</div>
		</div>
	<?php 
}
?>

	<?php 
foreach ($this->summary_basket->get_taxes() as $tax_name => $map) {
    ?>
		<?php 
    foreach ($map as $tax_rate => $price_item) {
        ?>
			<?php 
        if (($tax_value = $price_item->get_tax_value()) > 0) {
            ?>
				<div class="tax row g-0">
					<div class="col-12 col-md-6 offset-md-6">
						<div class="row g-0">
							<div class="col-8"><?php 
            echo $enc->html(sprintf($price_taxflag ? $tax_format_incl : $tax_format_excl, $this->number($tax_rate), $this->translate('client/code', $tax_name)));
            ?></div>
							<div class="price col-3"><?php 
            echo $enc->html(sprintf($price_format, $this->number($tax_value, $precision), $price_currency));
            ?></div>
							<?php 
            if ($modify) {
                ?>
								<div class="action col-1"></div>
							<?php 
            }
            ?>
						</div>
					</div>
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

	<?php 
if ($price_taxflag === false) {
    ?>
		<div class="total row g-0">
			<div class="col-12 col-md-6 offset-md-6">
				<div class="row g-0">
					<div class="quantity col-5"><?php 
    echo $enc->html(sprintf($this->translate('client', '%1$d article', '%1$d articles', $total_quantity), $total_quantity));
    ?></div>
					<div class="unitprice col-3"><?php 
    echo $enc->html($this->translate('client', 'Total'));
    ?></div>
					<div class="price col-3"><?php 
    echo $enc->html(sprintf($price_format, $this->number($this->summary_basket->get_price()->get_value() + $this->summary_basket->get_price()->get_costs() + $this->summary_basket->get_price()->get_tax_value(), $precision), $price_currency));
    ?></div>
					<?php 
    if ($modify) {
        ?>
						<div class="action col-1"></div>
					<?php 
    }
    ?>
				</div>
			</div>
		</div>
	<?php 
}
?>

	<?php 
if ($this->summary_basket->get_price()->get_rebate() > 0) {
    ?>
		<div class="rebate row g-0">
			<div class="col-12 col-md-6 offset-md-6">
				<div class="row g-0">
					<div class="quantity col-8"><?php 
    echo $enc->html($this->translate('client', 'Included rebates'));
    ?></div>
					<div class="price col-3"><?php 
    echo $enc->html(sprintf($price_format, $this->number($this->summary_basket->get_price()->get_rebate(), $precision), $price_currency));
    ?></div>
					<?php 
    if ($modify) {
        ?>
						<div class="action col-1"></div>
					<?php 
    }
    ?>
				</div>
			</div>
		</div>
	<?php 
}
?>

</div>
