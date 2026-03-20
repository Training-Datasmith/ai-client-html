<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
$enc = $this->encoder();
$this->block()->start('checkout/standard/delivery');
?>
<div class="section checkout-standard-delivery">

	<h1><?php 
echo $enc->html($this->translate('client', 'delivery'), $enc::TRUST);
?></h1>
	<p class="note"><?php 
echo $enc->html($this->translate('client', 'Please choose your delivery method'), $enc::TRUST);
?></p>

	<?php 
foreach ($this->get('deliveryServices', []) as $id => $service) {
    ?>
		<div id="c_delivery-<?php 
    echo $enc->attr($id);
    ?>" class="item item-service row">

			<div class="col-sm-6">
				<label class="description" for="c_deliveryoption-<?php 
    echo $enc->attr($id);
    ?>">

					<input class="option" type="radio"
						id="c_deliveryoption-<?php 
    echo $enc->attr($id);
    ?>"
						name="<?php 
    echo $enc->attr($this->formparam(['c_deliveryoption']));
    ?>"
						value="<?php 
    echo $enc->attr($id);
    ?>"
						<?php 
    echo $id != $this->get('deliveryOption') ?: 'checked="checked"';
    ?>
					>

					<div class="icons">
						<?php 
    foreach ($service->get_ref_items('media', 'icon', 'default') as $media_item) {
        ?>
							<?php 
        echo $this->partial($this->config('client/html/common/partials/media', 'common/partials/media'), ['item' => $media_item, 'boxAttributes' => ['class' => 'icon']]);
        ?>
						<?php 
    }
    ?>
					</div>

					<h2><?php 
    echo $enc->html($service->get_name(), $enc::TRUST);
    ?></h2>

					<?php 
    if ($price = $service->price) {
        ?>

						<?php 
        if ($price->get_value() > 0) {
            ?>
							<div class="price-value">
								<?php 
            echo $enc->html(sprintf(
                /// Service fee value (%1$s) and shipping cost value (%2$s) with currency (%3$s)
                $this->translate('client', '%1$s%3$s + %2$s%3$s'),
                $this->number($price->get_value(), $price->get_precision()),
                $this->number($price->get_costs() > 0 ? $price->get_costs() : 0, $price->get_precision()),
                $this->translate('currency', $price->get_currency_id())
            ));
            ?>
							</div>
						<?php 
        } else {
            ?>
							<div class="price-value">
								<?php 
            $pricetype = 'price:default';
            ?>
								<?php 
            echo $enc->html(sprintf(
                /// Price format with price value (%1$s) and currency (%2$s)
                $this->translate('client/code', $pricetype, null, 0, false) ?: $this->translate('client', '%1$s %2$s'),
                $this->number($price->get_costs() > 0 ? $price->get_costs() : 0, $price->get_precision()),
                $this->translate('currency', $price->get_currency_id())
            ));
            ?>
							</div>
						<?php 
        }
        ?>

					<?php 
    }
    ?>

					<div class="text">
						<?php 
    foreach ($service->get_ref_items('text', null, 'default') as $text_item) {
        ?>
							<?php 
        if (($type = $text_item->get_type()) !== 'name') {
            ?>
								<p class="<?php 
            echo $enc->attr($type);
            ?>"><?php 
            echo $enc->html($text_item->get_content(), $enc::TRUST);
            ?></p>
							<?php 
        }
        ?>
						<?php 
    }
    ?>
					</div>

				</label>
			</div>


			<div class="col-sm-6">
				<?php 
    if ($attributes = $service->attributes) {
        ?>
					<?php 
        echo $this->partial(
            /** client/html/checkout/standard/partials/serviceattr
             * Relative path to the checkout service attribute partial template file
             *
             * Partials are templates which are reused in other templates and generate reoccuring
             * blocks filled with data from the assigned values. The service attribute partial creates
             * an HTML block for the checkout delivery/payment option input/select fields.
             *
             * This is a very generic way to generate the list of service attribute pairs that will be
             * added as order service attributes in the basket. Depending on the type of the attribute,
             * it will create an input field, a select box or a list of selectable items. What attributes
             * are available to the customer depends on the definitions in the service providers and the
             * decorators wrapped around them.
             *
             * If you want to adapt the output to your own project and you know you only have a specific
             * list of attributes, you can create the input and selections in a non-generic, straight
             * forward way. The $serviceAttributes[$id] array contains an associative list of codes as
             * keys (e.g. "time.hourminute") and items implementing \Aimeos\Base\Criteria\Attribute\Iface
             * as values, e.g.
             *   time.hourminute => \Aimeos\Base\Criteria\Attribute\Iface (
             *	   code => 'time.hourminute',
             *	   internalcode => 'hourminute',
             *	   label => 'Delivery time',
             *	   type => 'time',
             *	   internaltype => 'time',
             *	   default => '',
             *	   required => true
             *   )
             *
             * @param string Relative path to the template file
             * @since 2017.07
             */
            $this->config('client/html/checkout/standard/partials/serviceattr', 'checkout/standard/serviceattr-partial'),
            ['attributes' => $attributes, 'type' => 'delivery', 'id' => $id]
        );
        ?>
				<?php 
    }
    ?>

			</div>
		</div>

	<?php 
}
?>


	<div class="button-group">
		<a class="btn btn-default btn-lg btn-back" href="<?php 
echo $enc->attr($this->get('standardUrlBack'));
?>">
			<?php 
echo $enc->html($this->translate('client', 'Previous'), $enc::TRUST);
?>
		</a>
		<button type="submit" class="btn btn-primary btn-lg btn-action">
			<?php 
echo $enc->html($this->translate('client', 'Next'), $enc::TRUST);
?>
		</button>
	</div>

</div>
<?php 
$this->block()->stop();
echo $this->block()->get('checkout/standard/delivery');