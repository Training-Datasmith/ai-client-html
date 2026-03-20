<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2015-2020
 */
$enc = $this->encoder();
/** client/html/basket/standard/url/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2014.03
 * @see client/html/basket/standard/url/controller
 * @see client/html/basket/standard/url/action
 * @see client/html/basket/standard/url/config
 * @see client/html/basket/standard/url/filter
 */
/** client/html/basket/standard/url/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2014.03
 * @see client/html/basket/standard/url/target
 * @see client/html/basket/standard/url/action
 * @see client/html/basket/standard/url/config
 * @see client/html/basket/standard/url/filter
 */
/** client/html/basket/standard/url/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2014.03
 * @see client/html/basket/standard/url/target
 * @see client/html/basket/standard/url/controller
 * @see client/html/basket/standard/url/config
 * @see client/html/basket/standard/url/filter
 */
/** client/html/basket/standard/url/config
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
 * @see client/html/basket/standard/url/target
 * @see client/html/basket/standard/url/controller
 * @see client/html/basket/standard/url/action
 * @see client/html/basket/standard/url/filter
 */
/** client/html/basket/standard/url/filter
 * Removes parameters for the detail page before generating the URL
 *
 * This setting removes the listed parameters from the URLs. Keep care to
 * remove no required parameters!
 *
 * @param array List of parameter names to remove
 * @since 2022.10
 * @see client/html/basket/standard/url/target
 * @see client/html/basket/standard/url/controller
 * @see client/html/basket/standard/url/action
 * @see client/html/basket/standard/url/config
 */
$pricetype = 'price:default';
$pricefmt = $this->translate('client/code', $pricetype);
/// Price format with price value (%1$s) and currency (%2$s)
$price_format = $pricefmt !== 'price:default' ? $pricefmt : $this->translate('client', '%1$s %2$s');
?>

<div class="section aimeos basket-mini" data-jsonurl="<?php 
echo $enc->attr($this->link('client/jsonapi/url'));
?>">

	<?php 
if (isset($this->mini_basket)) {
    ?>
		<?php 
    $price_item = $this->mini_basket->get_price();
    $price_currency = $this->translate('currency', $price_item->get_currency_id());
    $quantity = $this->mini_basket->get_products()->sum('order.product.quantity');
    ?>

		<div class="aimeos-overlay-offscreen"></div>

		<a class="basket-mini-main menu" href="#">
			<span class="quantity"><?php 
    echo $enc->html($quantity);
    ?></span>
			<span class="value"><?php 
    echo $enc->html(sprintf($price_format, $this->number($price_item->get_value() + $price_item->get_costs(), $price_item->get_precision()), $price_currency));
    ?></span>
		</a>

		<div class="basket-mini-product zeynep">
			<div class="header row">
				<div class="col-2"></div>
				<div class="col-8 name"><?php 
    echo $enc->html($this->translate('client', 'Basket'), $enc::TRUST);
    ?></div>
				<a class="col-2 close" href="#" title="<?php 
    echo $enc->attr($this->translate('client', 'Close'));
    ?>"></a>
			</div>

			<div class="basket">
				<div class="basket-header row">
						<div class="col-5 name"><?php 
    echo $enc->html($this->translate('client', 'Product'), $enc::TRUST);
    ?></div>
						<div class="col-2 quantity"><?php 
    echo $enc->html($this->translate('client', 'Qty'), $enc::TRUST);
    ?></div>
						<div class="col-4 price"><?php 
    echo $enc->html($this->translate('client', 'Price'), $enc::TRUST);
    ?></div>
						<div class="col-1 action"></div>
				</div>
				<div class="basket-body">
					<?php 
    foreach ($this->mini_basket->get_products() as $pos => $product) {
        ?>
						<?php 
        $param = ['resource' => 'basket', 'id' => 'default', 'related' => 'product', 'relatedid' => $pos];
        $param[$this->csrf()->name()] = $this->csrf()->value();
        ?>
						<div class="product-item row" data-url="<?php 
        echo $enc->attr($this->link('client/jsonapi/url', $param));
        ?>">
							<div class="col-5 name">
								<?php 
        echo $enc->html($product->get_name());
        ?>
							</div>
							<div class="col-2 quantity">
								<?php 
        echo $enc->html($product->get_quantity());
        ?>
							</div>
							<div class="col-4 price">
								<?php 
        echo $enc->html(sprintf($price_format, $this->number($product->get_price()->get_value(), $product->get_price()->get_precision()), $price_currency));
        ?>
							</div>
							<div class="col-1 action">
								<?php 
        if (($product->get_flags() & \Aimeos\M_Shop\Order\Item\Product\Base::FLAG_IMMUTABLE) == 0) {
            ?>
									<a class="delete" href="#" title="<?php 
            echo $enc->attr($this->translate('client', 'Delete'));
            ?>"></a>
								<?php 
        }
        ?>
							</div>
						</div>
					<?php 
    }
    ?>
					<div class="product-item row prototype">
						<div class="col-5 name"></div>
						<div class="col-2 quantity"></div>
						<div class="col-4 price"></div>
						<div class="col-1 action"><a class="delete" href="#" title="<?php 
    echo $enc->attr($this->translate('client', 'Delete'));
    ?>"></a></div>
					</div>
				</div>
				<div class="basket-footer">
					<div class="delivery row">
						<div class="col-7 name">
							<?php 
    echo $enc->html($this->translate('client', 'Shipping'), $enc::TRUST);
    ?>
						</div>
						<div class="col-4 price">
							<?php 
    echo $enc->html(sprintf($price_format, $this->number($price_item->get_costs(), $price_item->get_precision()), $price_currency));
    ?>
						</div>
						<div class="col-1 action"></div>
					</div>
					<div class="total row">
						<div class="col-7 name">
							<?php 
    echo $enc->html($this->translate('client', 'Total'), $enc::TRUST);
    ?>
								</div>
						<div class="col-4 price">
							<?php 
    echo $enc->html(sprintf($price_format, $this->number($price_item->get_value() + $price_item->get_costs(), $price_item->get_precision()), $price_currency));
    ?>
						</div>
						<div class="col-1 action"></div>
					</div>
				</div>
			</div>
			<div class="to-basket">
				<a class="btn btn-primary" href="<?php 
    echo $enc->attr($this->link('client/html/basket/standard/url'));
    ?>">
					<?php 
    echo $enc->html($this->translate('client', 'Basket'), $enc::TRUST);
    ?>
				</a>
			</div>
		</div>
	<?php 
}
?>
</div>
