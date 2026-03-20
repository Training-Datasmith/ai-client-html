<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
$enc = $this->encoder();
?>
<div class="section aimeos checkout-confirm" data-jsonurl="<?php 
echo $enc->attr($this->link('client/jsonapi/url'));
?>">
	<div class="container-xxl">

		<h1><?php 
echo $enc->html($this->translate('client', 'Confirmation'), $enc::TRUST);
?></h1>

		<div class="checkout-confirm-intro">
			<?php 
switch ($this->confirm_order_item->get_status_payment()) {
    case \Aimeos\M_Shop\Order\Item\Base::PAY_CANCELED:
        ?>
				<p class="note"><?php 
        echo nl2br($enc->html($this->translate('client', 'The order was canceled.
Do you wish to retry your order?'), $enc::TRUST));
        break;
        ?></p>
			<?php 
    case \Aimeos\M_Shop\Order\Item\Base::PAY_REFUSED:
        ?>
				<p class="note"><?php 
        echo nl2br($enc->html($this->translate('client', 'Unfortunately, the payment for your order was refused.
Do you wish to retry?'), $enc::TRUST));
        break;
        ?></p>
			<?php 
    case \Aimeos\M_Shop\Order\Item\Base::PAY_PENDING:
        ?>
				<p class="note"><?php 
        echo nl2br($enc->html($this->translate('client', 'The payment confirmation for your order is still pending.
You will get an e-mail as soon as the payment is authorized.'), $enc::TRUST));
        break;
        ?></p>
			<?php 
    case \Aimeos\M_Shop\Order\Item\Base::PAY_AUTHORIZED:
        ?>
				<p class="note"><?php 
        echo nl2br($enc->html($this->translate('client', 'Thank you for your order and authorizing the payment.
An e-mail with the order details will be sent to you within the next few minutes.'), $enc::TRUST));
        break;
        ?></p>
			<?php 
    case \Aimeos\M_Shop\Order\Item\Base::PAY_RECEIVED:
        ?>
				<p class="note"><?php 
        echo nl2br($enc->html($this->translate('client', 'Thank you for your order.
We received your payment and an e-mail with the order details will be sent to you within the next few minutes.'), $enc::TRUST));
        break;
        ?></p>
			<?php 
}
?>
		</div>

		<div class="checkout-confirm-basic">
			<h2><?php 
echo $enc->html($this->translate('client', 'Order status'), $enc::TRUST);
?></h2>

			<?php 
if (isset($this->confirm_order_item)) {
    ?>
				<ul class="attr-list">
					<li class="form-item">
						<span class="name">
							<?php 
    echo $enc->html($this->translate('client', 'Order ID'), $enc::TRUST);
    ?>
						</span>
						<span class="value">
							<?php 
    echo $enc->html($this->confirm_order_item->get_invoice_number());
    ?>
						</span>
					</li>
					<li class="form-item">
						<span class="name">
							<?php 
    echo $enc->html($this->translate('client', 'Payment status'), $enc::TRUST);
    ?>
						</span>
						<span class="value">
							<?php 
    $code = 'pay:' . $this->confirm_order_item->get_status_payment();
    ?>
							<?php 
    echo $enc->html($this->translate('mshop/code', $code));
    ?>
						</span>
					</li>
				</ul>
			<?php 
}
?>

		</div>

		<div class="checkout-confirm-retry">

			<?php 
if (isset($this->confirm_order_item) && $this->confirm_order_item->get_status_payment() < \Aimeos\M_Shop\Order\Item\Base::PAY_REFUND) {
    ?>
				<div class="button-group">
					<a class="btn btn-default btn-lg" href="<?php 
    echo $enc->attr($this->link('client/html/checkout/standard/url', ['c_step' => 'payment']));
    ?>">
						<?php 
    echo $enc->html($this->translate('client', 'Change payment'), $enc::TRUST);
    ?>
					</a>
					<a class="btn btn-primary btn-lg" href="<?php 
    echo $enc->attr($this->link('client/html/checkout/standard/url', ['c_step' => 'process', 'cs_option_terms' => 1, 'cs_option_terms_value' => 1, 'cs_order' => 1]));
    ?>">
						<?php 
    echo $enc->html($this->translate('client', 'Try again'), $enc::TRUST);
    ?>
					</a>
				</div>
			<?php 
}
?>

		</div>


		<div class="checkout-confirm-detail common-summary">
			<h2 class="header"><?php 
echo $enc->html($this->translate('client', 'Order details'), $enc::TRUST);
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
if (($addresses = $this->summary_basket->get_address('payment')) !== []) {
    ?>
							<?php 
    echo $this->partial(
        /** client/html/checkout/confirm/summary/address
         * Location of the address partial template for the confirmation component
         *
         * To configure an alternative template for the address partial, you
         * have to configure its path relative to the template directory
         * (usually templates/client/html/). It's then used to display the
         * payment or delivery address block on the confirm page during the
         * checkout process.
         *
         * @param string Relative path to the address partial
         * @since 2017.01
         * @see client/html/checkout/confirm/summary/detail
         * @see client/html/checkout/confirm/summary/service
         */
        $this->config('client/html/checkout/confirm/summary/address', 'common/summary/address'),
        ['addresses' => $addresses]
    );
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
if (($addresses = $this->summary_basket->get_address('delivery')) !== []) {
    ?>
							<?php 
    echo $this->partial($this->config('client/html/checkout/confirm/summary/address', 'common/summary/address'), ['addresses' => $addresses]);
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
if (($services = $this->summary_basket->get_service('delivery')) !== []) {
    ?>
							<?php 
    echo $this->partial(
        /** client/html/checkout/confirm/summary/service
         * Location of the service partial template for the confirmation component
         *
         * To configure an alternative template for the service partial, you
         * have to configure its path relative to the template directory
         * (usually templates/client/html/). It's then used to display the
         * payment or delivery service block on the confirm page during the
         * checkout process.
         *
         * @param string Relative path to the service partial
         * @since 2017.01
         * @see client/html/checkout/confirm/summary/address
         * @see client/html/checkout/confirm/summary/detail
         */
        $this->config('client/html/checkout/confirm/summary/service', 'common/summary/service'),
        ['service' => $services, 'type' => 'delivery']
    );
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
if (($services = $this->summary_basket->get_service('payment')) !== []) {
    ?>
							<?php 
    echo $this->partial($this->config('client/html/checkout/confirm/summary/service', 'common/summary/service'), ['service' => $services, 'type' => 'payment']);
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
if (!($coupons = $this->summary_basket->get_coupons())->is_empty()) {
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
echo $enc->html($this->summary_basket->get_customer_reference());
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
echo $enc->html($this->summary_basket->get_comment());
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
echo $this->partial(
    /** client/html/checkout/confirm/summary/detail
     * Location of the detail partial template for the confirmation component
     *
     * To configure an alternative template for the detail partial, you
     * have to configure its path relative to the template directory
     * (usually templates/client/html/). It's then used to display the
     * product detail block on the confirm page during the checkout process.
     *
     * @param string Relative path to the detail partial
     * @since 2017.01
     * @see client/html/checkout/confirm/summary/address
     * @see client/html/checkout/confirm/summary/service
     */
    $this->config('client/html/checkout/confirm/summary/detail', 'common/summary/detail'),
    ['orderItem' => $this->confirm_order_item, 'summaryBasket' => $this->summary_basket]
);
?>
				</div>
			</div>

		</div>
	</div>
</div>
