<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
$enc = $this->encoder();
$this->block()->start('checkout/standard/address/payment');
?>
<div class="checkout-standard-address-payment col-xs-12 col-xl">

	<fieldset class="address">
		<legend><?php 
echo $enc->html($this->translate('client', 'Billing address'), $enc::TRUST);
?></legend>

		<div id="address-payment-list" class="accordion">

			<?php 
if (isset($this->address_payment_item) && $id = $this->address_payment_item->get_address_id()) {
    ?>

				<div class="accordion-item address-payment item-address item-default">
					<div class="header" role="button"
						data-bs-toggle="collapse" data-bs-target="#address-payment"
						aria-controls="address-payment" aria-expanded="false">

						<input id="ca_paymentoption-<?php 
    echo $enc->attr($id);
    ?>" type="radio"
							name="<?php 
    echo $enc->attr($this->formparam(['ca_paymentoption']));
    ?>"
							value="<?php 
    echo $enc->attr($id);
    ?>"
							<?php 
    echo $this->get('addressPaymentOption') == $id ? 'checked="checked"' : '';
    ?>
						>
						<label for="ca_paymentoption-<?php 
    echo $enc->attr($id);
    ?>" class="values">
							<?php 
    echo nl2br($enc->html($this->get('addressPaymentString', '')));
    ?>
						</label>
					</div>
					<div class="address accordion-collapse collapse"
						id="address-payment" data-bs-parent="#address-payment-list">

						<div class="form-list">

							<?php 
    echo $this->partial(
        /** client/html/checkout/standard/partials/address
         * Relative path to the address partial template file
         *
         * Partials are templates which are reused in other templates and generate
         * reoccuring blocks filled with data from the assigned values. The address
         * partial creates an HTML block with input fields for address forms.
         *
         * @param string Relative path to the template file
         * @since 2017.01
         */
        $this->config('client/html/checkout/standard/partials/address', 'common/partials/address'),
        ['address' => $this->address_payment_item->to_array(), 'id' => $id, 'countries' => $this->get('addressCountries', []), 'css' => $this->get('addressPaymentCss', []), 'error' => $this->get('addressPaymentError', []), 'formnames' => ['ca_payment_' . $id], 'languages' => $this->get('addressLanguages', []), 'languageid' => $this->get('contextLanguage'), 'salutations' => $this->get('addressSalutations', []), 'states' => $this->get('addressStates', []), 'prefix' => 'order.address.', 'type' => 'payment']
    );
    ?>

						</div>
					</div>
				</div>

			<?php 
}
?>

			<?php 
if (!$this->config('client/html/checkout/standard/address/payment/disable-new', false)) {
    ?>

				<div class="accordion-item address-payment item-address item-new">
					<div class="header" role="button"
						data-bs-toggle="collapse" data-bs-target="#address-payment-new"
						aria-controls="address-payment-new"
						aria-expanded="<?php 
    echo isset($this->address_payment_item) ? 'false' : 'true';
    ?>">

						<input id="ca_paymentoption-new" type="radio" value="null"
							name="<?php 
    echo $enc->attr($this->formparam(['ca_paymentoption']));
    ?>"
							<?php 
    echo $this->get('addressPaymentOption') == 'null' ? 'checked="checked"' : '';
    ?>
						>
						<label for="ca_paymentoption-new" class="values value-new">
							<?php 
    echo $enc->html($this->translate('client', 'new address'), $enc::TRUST);
    ?>
						</label>
					</div>
					<div class="address accordion-collapse <?php 
    echo isset($this->address_payment_item) && $this->address_payment_item->get_address_id() ? 'collapse' : '';
    ?>"
						id="address-payment-new" data-bs-parent="#address-payment-list">

						<div class="form-list">

							<?php 
    echo $this->partial($this->config('client/html/checkout/standard/partials/address', 'common/partials/address'), ['id' => null, 'address' => $this->get('addressPaymentValuesNew', []), 'countries' => $this->get('addressCountries', []), 'css' => $this->get('addressPaymentCss', []), 'disabled' => isset($this->address_payment_item) && $this->address_payment_item->get_address_id() ? true : false, 'error' => $this->get('addressPaymentOption') == 'null' ? $this->get('addressPaymentError', []) : [], 'formnames' => ['ca_payment'], 'languages' => $this->get('addressLanguages', []), 'languageid' => $this->get('contextLanguage'), 'salutations' => $this->get('addressSalutations', []), 'states' => $this->get('addressStates', []), 'prefix' => 'order.address.', 'type' => 'payment']);
    ?>

						</div>
					</div>
				</div>

			<?php 
}
?>

		</div>
	</fieldset>
</div>
<?php 
$this->block()->stop();
echo $this->block()->get('checkout/standard/address/payment');