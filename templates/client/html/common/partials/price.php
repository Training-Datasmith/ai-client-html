<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
/* Available data:
 * - prices : List of price items
 * - costsItem : Show "per item" for costs
 * - all: Show all properties including costs
 */
$prices = map();
$enc = $this->encoder();
$price_items = map($this->get('prices', []));
foreach ($price_items as $price_item) {
    $qty = (string) $price_item->get_quantity();
    if (!($p = $prices->get($qty)) || $p->get_value() === null || $price_item->get_value() !== null && $p->get_value() > $price_item->get_value()) {
        $prices[$qty] = $price_item;
    }
}
$prices->ksort();
$price = $prices->get_value()->first();
$format = [
    /// Price quantity format with quantity (%1$s)
    'quantity' => $this->translate('client', 'from %1$s'),
    /// Price shipping format with shipping / payment cost value (%1$s) and currency (%2$s)
    'costs' => $this->get('costsItem', true) ? $this->translate('client', '+ %1$s %2$s') : $this->translate('client', '%1$s %2$s'),
    /// Rebate format with rebate value (%1$s) and currency (%2$s)
    'rebate' => $this->translate('client', '%1$s %2$s off'),
    /// Rebate percent format with rebate percent value (%1$s)
    'rebate%' => $this->translate('client', '-%1$s%%'),
];
/// Tax rate format with tax rate in percent (%1$s)
$withtax = $this->translate('client', 'Incl. %1$s%% VAT');
/// Tax rate format with tax rate in percent (%1$s)
$notax = $this->translate('client', '+ %1$s%% VAT');
foreach ($prices as $price_item) {
    ?>
	<?php 
    if ($price_item->get_value() > $price) {
        continue;
        // Only show prices for higher quantities if they are lower then the first price
    }
    /// Price format with price value (%1$s) and currency (%2$s)
    $format['value'] = $this->translate('client/code', 'price:' . ($price_item->get_type() ?: 'default'), null, 0, false) ?: $this->translate('client', '%1$s %2$s');
    $currency = $this->translate('currency', $price_item->get_currency_id());
    ?>

	<div class="price-item <?php 
    echo $enc->attr($price_item->get_type());
    ?>" itemscope itemprop="priceSpecification" itemtype="http://schema.org/UnitPriceSpecification">

		<meta itemprop="valueAddedTaxIncluded" content="<?php 
    echo $price_item->get_tax_flag() ? 'true' : 'false';
    ?>">
		<meta itemprop="priceCurrency" content="<?php 
    echo $price_item->get_currency_id();
    ?>">
		<meta itemprop="price" content="<?php 
    echo $price_item->get_value();
    ?>">

		<span class="quantity" itemscope itemtype="http://schema.org/QuantitativeValue">
			<meta itemprop="minValue" content="<?php 
    echo $price_item->get_quantity();
    ?>">
			<?php 
    echo $enc->html(sprintf($format['quantity'], $price_item->get_quantity()), $enc::TRUST);
    ?>
		</span>

		<?php 
    if ($price_item->get_value() > 0 && $price_item->get_rebate() > 0) {
        ?>
			<span class="original">
				<?php 
        echo $enc->html(sprintf($format['value'], $this->number($price_item->get_value() + $price_item->get_rebate(), $price_item->get_precision()), $currency), $enc::TRUST);
        ?>
			</span>
		<?php 
    }
    ?>

		<span class="value" itemscope itemprop="priceType" itemtype="http://schema.org/SalePrice">
			<?php 
    if ($price_item->get_value() !== null) {
        ?>
				<?php 
        echo $enc->html(sprintf($format['value'], $this->number($price_item->get_value(), $price_item->get_precision()), $currency), $enc::TRUST);
        ?>
			<?php 
    } else {
        ?>
				<?php 
        echo $enc->html($this->translate('client', 'on request'));
        ?>
			<?php 
    }
    ?>
		</span>

		<?php 
    if ($price_item->get_value() > 0 && $price_item->get_rebate() > 0) {
        ?>
			<span class="rebate">
				<?php 
        echo $enc->html(sprintf($format['rebate'], $this->number($price_item->get_rebate()), $currency), $enc::TRUST);
        ?>
			</span>
			<span class="rebatepercent">
				<?php 
        echo $enc->html(sprintf($format['rebate%'], $this->number(round($price_item->get_rebate() * 100 / ($price_item->get_value() + $price_item->get_rebate())), 0)), $enc::TRUST);
        ?>
			</span>
		<?php 
    }
    ?>

		<?php 
    if ($this->get('all') || $price_item->get_costs() > 0) {
        ?>
			<span class="costs">
				<?php 
        echo $enc->html(sprintf($format['costs'], $this->number($price_item->get_costs(), $price_item->get_precision()), $currency), $enc::TRUST);
        ?>
			</span>
		<?php 
    }
    ?>

		<?php 
    if ($price_item->get_taxrate() > 0) {
        ?>
			<span class="taxrate">
				<?php 
        echo $enc->html(sprintf($price_item->get_tax_flag() ? $withtax : $notax, $this->number($price_item->get_taxrate())), $enc::TRUST);
        ?>
			</span>
		<?php 
    }
    ?>
	</div>

<?php 
}