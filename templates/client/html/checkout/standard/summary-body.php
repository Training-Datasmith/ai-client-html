<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
$enc = $this->encoder();
$checkout_address_url = $this->link('client/html/checkout/standard/url', ['c_step' => 'address']);
$checkout_delivery_url = $this->link('client/html/checkout/standard/url', ['c_step' => 'delivery']);
$checkout_payment_url = $this->link('client/html/checkout/standard/url', ['c_step' => 'payment']);
$basket_url = $this->link('client/html/basket/standard/url');
$this->block()->start('checkout/standard/summary');
?>
<div class="section checkout-standard-summary common-summary">
	<input type="hidden" name="<?php 
echo $enc->attr($this->formparam(['cs_order']));
?>" value="1">

	<h1><?php 
echo $enc->html($this->translate('client', 'summary'), $enc::TRUST);
?></h1>
	<p class="note"><?php 
echo $enc->html($this->translate('client', 'Please check your order'), $enc::TRUST);
?></p>


	<div class="common-summary-address row">
		<div class="item payment <?php 
echo !$this->value($this->get('summaryErrorCodes', []), 'address/payment') ?: 'error';
?> col-sm-6">
			<div class="header">
				<h3><?php 
echo $enc->html($this->translate('client', 'Billing address'), $enc::TRUST);
?></h3>
				<a class="minibutton modify" href="<?php 
echo $enc->attr($checkout_address_url);
?>"
					title="<?php 
echo $enc->attr($this->translate('client', 'Change address'), $enc::TRUST);
?>"></a>
			</div>

			<div class="content">
				<?php 
if ($addresses = $this->standard_basket->get_address('payment')) {
    ?>
					<?php 
    echo $this->partial(
        /** client/html/checkout/standard/summary/address
         * Location of the address partial template for the checkout summary
         *
         * To configure an alternative template for the address partial, you
         * have to configure its path relative to the template directory
         * (usually templates/client/html/). It's then used to display the
         * payment or delivery address block on the summary page during the
         * checkout process.
         *
         * @param string Relative path to the address partial
         * @since 2017.01
         * @see client/html/checkout/standard/summary/detail
         * @see client/html/checkout/standard/summary/options
         * @see client/html/checkout/standard/summary/service
         */
        $this->config('client/html/checkout/standard/summary/address', 'common/summary/address'),
        ['addresses' => $addresses]
    );
    ?>
				<?php 
}
?>
			</div>
		</div><!--

		--><div class="item delivery <?php 
echo !$this->value($this->get('summaryErrorCodes', []), 'address/delivery') ?: 'error';
?> col-sm-6">
			<div class="header">
				<h3><?php 
echo $enc->html($this->translate('client', 'Delivery address'), $enc::TRUST);
?></h3>
				<a class="minibutton modify" href="<?php 
echo $enc->attr($checkout_address_url);
?>"
					title="<?php 
echo $enc->attr($this->translate('client', 'Change address'), $enc::TRUST);
?>"></a>
			</div>

			<div class="content">
				<?php 
if ($addresses = $this->standard_basket->get_address('delivery')) {
    ?>
					<?php 
    echo $this->partial($this->config('client/html/checkout/standard/summary/address', 'common/summary/address'), ['addresses' => $addresses]);
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
		<div class="item delivery <?php 
echo !$this->value($this->get('summaryErrorCodes', []), 'service/delivery') ?: 'error';
?> col-sm-6">
			<div class="header">
				<h3><?php 
echo $enc->html($this->translate('client', 'delivery'), $enc::TRUST);
?></h3>
				<a class="minibutton modify" href="<?php 
echo $enc->attr($checkout_delivery_url);
?>"
					title="<?php 
echo $enc->attr($this->translate('client', 'Change delivery'), $enc::TRUST);
?>"></a>
			</div>

			<div class="content">
				<?php 
if ($services = $this->standard_basket->get_service('delivery')) {
    ?>
					<?php 
    echo $this->partial(
        /** client/html/checkout/standard/summary/service
         * Location of the service partial template for the checkout summary
         *
         * To configure an alternative template for the service partial, you
         * have to configure its path relative to the template directory
         * (usually templates/client/html/). It's then used to display the
         * payment or delivery service block on the summary page during the
         * checkout process.
         *
         * @param string Relative path to the service partial
         * @since 2017.01
         * @see client/html/checkout/standard/summary/address
         * @see client/html/checkout/standard/summary/detail
         * @see client/html/checkout/standard/summary/options
         */
        $this->config('client/html/checkout/standard/summary/service', 'common/summary/service'),
        ['service' => $services, 'type' => 'delivery']
    );
    ?>
				<?php 
}
?>
			</div>
		</div><!--

		--><div class="item payment <?php 
echo !$this->value($this->get('summaryErrorCodes', []), 'service/payment') ?: 'error';
?> col-sm-6">
			<div class="header">
				<h3><?php 
echo $enc->html($this->translate('client', 'payment'), $enc::TRUST);
?></h3>
				<a class="minibutton modify" href="<?php 
echo $enc->attr($checkout_payment_url);
?>"
					title="<?php 
echo $enc->attr($this->translate('client', 'Change payment'), $enc::TRUST);
?>"></a>
			</div>

			<div class="content">
				<?php 
if ($services = $this->standard_basket->get_service('payment')) {
    ?>
					<?php 
    echo $this->partial($this->config('client/html/checkout/standard/summary/service', 'common/summary/service'), ['service' => $services, 'type' => 'payment']);
    ?>
				<?php 
}
?>
			</div>
		</div>

	</div>


	<div class="common-summary-additional row">
		<div class="item coupon <?php 
echo !$this->value($this->get('summaryErrorCodes', []), 'coupon') ?: 'error';
?> col-sm-4">
			<div class="header">
				<h3><?php 
echo $enc->html($this->translate('client', 'Coupon codes'), $enc::TRUST);
?></h3>
				<a class="minibutton modify" href="<?php 
echo $enc->attr($basket_url);
?>"
					title="<?php 
echo $enc->attr($this->translate('client', 'Change coupons'), $enc::TRUST);
?>"></a>
			</div>

			<div class="content">
				<?php 
if (!($coupons = $this->standard_basket->get_coupons())->is_empty()) {
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
echo $enc->html($this->translate('client', 'Your reference'), $enc::TRUST);
?></h3>
			</div>

			<div class="content">
				<input class="customerref-value"
					title="<?php 
echo $enc->attr($this->translate('client', 'Your reference'), $enc::TRUST);
?>"
					name="<?php 
echo $enc->attr($this->formparam(['cs_customerref']));
?>"
					value="<?php 
echo $enc->attr($this->standard_basket->get_customer_reference());
?>">
			</div>
		</div><!--

		--><div class="item comment col-sm-4">
			<div class="header">
				<h3><?php 
echo $enc->html($this->translate('client', 'Your comment'), $enc::TRUST);
?></h3>
			</div>

			<div class="content">
				<textarea class="comment-value" name="<?php 
echo $enc->attr($this->formparam(['cs_comment']));
?>"
					title="<?php 
echo $enc->attr($this->translate('client', 'Your comment'), $enc::TRUST);
?>">
					<?php 
echo $enc->html($this->standard_basket->get_comment());
?>
				</textarea>
			</div>
		</div>
	</div>


	<div class="checkout-standard-summary-option row">
		<?php 
echo $this->partial(
    /** client/html/checkout/standard/summary/options
     * Location of the options partial template for the checkout summary
     *
     * To configure an alternative template for the options partial, you
     * have to configure its path relative to the template directory
     * (usually templates/client/html/). It's then used to display the
     * options block on the summary page during the checkout process.
     *
     * @param string Relative path to the options partial
     * @since 2017.01
     * @see client/html/checkout/standard/summary/address
     * @see client/html/checkout/standard/summary/detail
     * @see client/html/checkout/standard/summary/service
     */
    $this->config('client/html/checkout/standard/summary/options', 'checkout/standard/option-partial'),
    ['standardBasket' => $this->standard_basket, 'errors' => $this->get('summaryErrorCodes', [])]
);
?>
	</div>


	<div class="common-summary-detail row">
		<div class="header">
			<h2><?php 
echo $enc->html($this->translate('client', 'Details'), $enc::TRUST);
?></h2>
			<a class="minibutton modify" href="<?php 
echo $enc->attr($basket_url);
?>"
				title="<?php 
echo $enc->attr($this->translate('client', 'Change products'), $enc::TRUST);
?>"></a>
		</div>

		<div class="basket table-responsive">
			<?php 
echo $this->partial(
    /** client/html/checkout/standard/summary/detail
     * Location of the detail partial template for the checkout summary
     *
     * To configure an alternative template for the detail partial, you
     * have to configure its path relative to the template directory
     * (usually templates/client/html/). It's then used to display the
     * product detail block on the summary page during the checkout process.
     *
     * @param string Relative path to the detail partial
     * @since 2017.01
     * @see client/html/checkout/standard/summary/address
     * @see client/html/checkout/standard/summary/options
     * @see client/html/checkout/standard/summary/service
     */
    $this->config('client/html/checkout/standard/summary/detail', 'common/summary/detail'),
    ['summaryBasket' => $this->standard_basket]
);
?>
		</div>
	</div>


	<div class="button-group">
		<a class="btn btn-default btn-lg btn-back" href="<?php 
echo $enc->attr($this->get('standardUrlBack'));
?>">
			<?php 
echo $enc->html($this->translate('client', 'Back'), $enc::TRUST);
?>
		</a>
		<button type="submit" class="btn btn-primary btn-lg btn-action">
			<?php 
echo $enc->html($this->translate('client', 'Buy now'), $enc::TRUST);
?>
		</button>
	</div>

</div>
<?php 
$this->block()->stop();
echo $this->block()->get('checkout/standard/summary');