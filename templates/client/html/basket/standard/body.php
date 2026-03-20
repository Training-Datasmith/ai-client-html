<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
$enc = $this->encoder();
if (isset($this->standard_basket)) {
    ?>

	<div class="section aimeos basket-standard" data-jsonurl="<?php 
    echo $enc->attr($this->link('client/jsonapi/url'));
    ?>">
		<div class="container-xxl">

			<form method="POST" action="<?php 
    echo $enc->attr($this->link('client/html/basket/standard/url'));
    ?>">
				<?php 
    echo $this->csrf()->formfield();
    ?>

				<div class="row header">
					<h2 class="col-12 col-md-6"><?php 
    echo $enc->html($this->translate('client', 'Basket'), $enc::TRUST);
    ?></h2>

					<div class="col-12 col-md-6">
						<div class="input-group basket-save">
							<input class="form-control basket-name" type="text" maxlength="255"
								placeholder="<?php 
    echo $enc->attr($this->translate('client', 'Basket name'));
    ?>"
								name="<?php 
    echo $enc->attr($this->formparam('b_name'));
    ?>"
							>
							<button class="btn" type="submit"
								formaction="<?php 
    echo $enc->attr($this->link('client/html/basket/standard/url', ['b_action' => 'save']));
    ?>">
								<?php 
    echo $enc->attr($this->translate('client', 'Save'));
    ?>
							</button>
						</div>
					</div>
				</div>

				<div class="common-summary-detail">
					<div class="header">
						<h2><?php 
    echo $enc->html($this->translate('client', 'Details'), $enc::TRUST);
    ?></h2>
					</div>

					<div class="basket">
						<?php 
    echo $this->partial(
        /** client/html/basket/standard/summary/detail
         * Location of the detail partial template for the basket standard component
         *
         * To configure an alternative template for the detail partial, you
         * have to configure its path relative to the template directory
         * (usually templates/client/html/). It's then used to display the
         * product detail block in the basket standard component.
         *
         * @param string Relative path to the detail partial
         * @since 2017.01
         */
        $this->config('client/html/basket/standard/summary/detail', 'common/summary/detail'),
        ['summaryEnableModify' => true, 'summaryBasket' => $this->standard_basket, 'summaryErrorCodes' => $this->get('standardErrorCodes', [])]
    );
    ?>
					</div>
				</div>

				<div class="basket-standard-coupon row">
					<div class="col-12 col-md-6 header">
						<h2><?php 
    echo $enc->html($this->translate('client', 'Coupon codes'));
    ?></h2>
					</div>

					<div class="col-12 col-md-6 content">

						<div class="input-group coupon-new">
							<input class="form-control coupon-code" type="text" maxlength="255"
								placeholder="<?php 
    echo $enc->attr($this->translate('client', 'Coupon codes'));
    ?>"
								name="<?php 
    echo $enc->attr($this->formparam('b_coupon'));
    ?>"
							><!--
							--><button class="btn btn-primary" type="submit"><?php 
    echo $enc->html($this->translate('client', 'Apply'));
    ?></button>
						</div>

						<?php 
    if (!($coupons = $this->standard_basket->get_coupons())->is_empty()) {
        ?>
							<div class="coupon-detail row">
								<div class="col-6">
									<div class="name"><?php 
        echo $enc->html($this->translate('client', 'Coupons'));
        ?>:</div>
								</div>
								<div class="col-6">
									<?php 
        foreach ($coupons as $code => $products) {
            $params = ['b_action' => 'coupon-delete', 'b_coupon' => $code];
            ?>
										<div class="coupon-codes">
											<span class="coupon-code"><?php 
            echo $enc->html($code);
            ?></span>
											<a class="minibutton delete" href="<?php 
            echo $enc->attr($this->link('client/html/basket/standard/url', $params));
            ?>"></a>
										</div>
									<?php 
        }
        ?>
								</div>
							</div>
						<?php 
    }
    ?>
					</div>
				</div>

				<div class="button-group">

					<?php 
    if (isset($this->standard_back_url)) {
        ?>
						<a class="btn btn-default btn-lg btn-back" href="<?php 
        echo $enc->attr($this->standard_back_url);
        ?>">
							<?php 
        echo $enc->html($this->translate('client', 'Back'), $enc::TRUST);
        ?>
						</a>
					<?php 
    }
    ?>

					<button class="btn btn-default btn-lg btn-update" type="submit">
						<?php 
    echo $enc->html($this->translate('client', 'Update'), $enc::TRUST);
    ?>
					</button>

					<?php 
    if ($this->get('standardCheckout', false) === true) {
        ?>
						<a class="btn btn-primary btn-lg btn-action"
							href="<?php 
        echo $enc->attr($this->link('client/html/checkout/standard/url'));
        ?>">
							<?php 
        echo $enc->html($this->translate('client', 'Checkout'), $enc::TRUST);
        ?>
						</a>
					<?php 
    }
    ?>

				</div>
			</form>
		</div>
	</div>

<?php 
}