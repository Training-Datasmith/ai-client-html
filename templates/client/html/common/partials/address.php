<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
/* Available data:
 * - countries: List of two letter ISO country codes
 * - css: Associative list of item keys as keys and list of CSS classes as values, e.g. "salutation" => ["mandatory"]
 * - disabled: If form fields should be initially disabled
 * - error: Associative list of item keys as keys and error message as values , e.g. "salutation" => "Invalid salutation"
 * - formnames: List of names to build the name of the form fields, e.g. ['address', 'payment']
 * - id: ID of the address item or unset if not available
 * - languages: List of 2-5 letter ISO language codes
 * - languageid: Current 2-5 letter ISO language code
 * - prefix : Prefix of the item keys including the dots, e.g. "order.address."
 * - salutations: List of salutation codes
 * - states: Associative list of two letter ISO country code as keys and list of custom state codes as values
 * - type: Address type, i.e "payment", "delivery"
 */
$enc = $this->encoder();
$addr = $this->get('address', []);
$prefix = $this->get('prefix', '');
$disabled = $this->get('disabled', true);
$fnames = $this->get('formnames', []);
$error = $this->get('error', []);
$css = $this->get('css', []);
$id = $this->get('id');
?>
<div class="row form-item form-group salutation <?php 
echo $enc->attr(($this->value($error, 'salutation') ? 'error ' : '') . join(' ', $this->value($css, 'salutation', [])));
?>">
	<div class="col-md-5">
		<label for="address-<?php 
echo $this->get('type', 'payment');
?>-salutation-<?php 
echo $id;
?>">
			<?php 
echo $enc->html($this->translate('client', 'Salutation'), $enc::TRUST);
?>
		</label>
	</div>
	<div class="col-md-7">
		<select class="form-control" autocomplete="honorific-prefix"
			id="address-<?php 
echo $this->get('type', 'payment');
?>-salutation-<?php 
echo $id;
?>"
			name="<?php 
echo $enc->attr($this->formparam(array_merge($fnames, [$prefix . 'salutation'])));
?>"
			<?php 
echo !$disabled && $this->value($css, 'salutation') ? '' : 'disabled';
?> >

			<?php 
if (count($this->get('salutations', [])) > 1) {
    ?>
				<option value=""><?php 
    echo $enc->html($this->translate('client', 'Select salutation'), $enc::TRUST);
    ?></option>
			<?php 
}
?>

			<?php 
foreach ($this->get('salutations', []) as $salutation) {
    ?>
				<option value="<?php 
    echo $enc->attr($salutation);
    ?>" <?php 
    echo $this->value($addr, $prefix . 'salutation') == $salutation ? 'selected' : '';
    ?>>
					<?php 
    echo $enc->html($this->translate('mshop/code', $salutation));
    ?>
				</option>
			<?php 
}
?>

		</select>
	</div>
</div>


<div class="row form-item form-group firstname <?php 
echo $enc->attr(($this->value($error, 'firstname') ? 'error ' : '') . join(' ', $this->value($css, 'firstname', [])));
?>"
	data-regex="<?php 
echo $enc->attr($this->config('client/html/common/address/validate/firstname'));
?>">

	<div class="col-md-5">
		<label for="address-<?php 
echo $this->get('type', 'payment');
?>-firstname-<?php 
echo $id;
?>">
			<?php 
echo $enc->html($this->translate('client', 'First name'), $enc::TRUST);
?>
		</label>
	</div>
	<div class="col-md-7">
		<input class="form-control" type="text" autocomplete="given-name"
			id="address-<?php 
echo $this->get('type', 'payment');
?>-firstname-<?php 
echo $id;
?>"
			name="<?php 
echo $enc->attr($this->formparam(array_merge($fnames, [$prefix . 'firstname'])));
?>"
			value="<?php 
echo $enc->attr($this->value($addr, $prefix . 'firstname'));
?>"
			placeholder="<?php 
echo $enc->attr($this->translate('client', 'First name'));
?>"
			<?php 
echo !$disabled && $this->value($css, 'firstname') ? '' : 'disabled';
?>
		>
	</div>
</div>


<div class="row form-item form-group lastname <?php 
echo $enc->attr(($this->value($error, 'lastname') ? 'error ' : '') . join(' ', $this->value($css, 'lastname', [])));
?>"
	data-regex="<?php 
echo $enc->attr($this->config('client/html/common/address/validate/lastname'));
?>">

	<div class="col-md-5">
		<label for="address-<?php 
echo $this->get('type', 'payment');
?>-lastname-<?php 
echo $id;
?>">
			<?php 
echo $enc->html($this->translate('client', 'Last name'), $enc::TRUST);
?>
		</label>
	</div>
	<div class="col-md-7">
		<input class="form-control" type="text" autocomplete="family-name"
			id="address-<?php 
echo $this->get('type', 'payment');
?>-lastname-<?php 
echo $id;
?>"
			name="<?php 
echo $enc->attr($this->formparam(array_merge($fnames, [$prefix . 'lastname'])));
?>"
			value="<?php 
echo $enc->attr($this->value($addr, $prefix . 'lastname'));
?>"
			placeholder="<?php 
echo $enc->attr($this->translate('client', 'Last name'));
?>"
			<?php 
echo !$disabled && $this->value($css, 'lastname') ? '' : 'disabled';
?>
		>
	</div>
</div>


<div class="row form-item form-group title <?php 
echo $enc->attr(($this->value($error, 'title') ? 'error ' : '') . join(' ', $this->value($css, 'title', [])));
?>"
	data-regex="<?php 
echo $enc->attr($this->config('client/html/common/address/validate/title'));
?>">

	<div class="col-md-5">
		<label for="address-<?php 
echo $this->get('type', 'payment');
?>-title-<?php 
echo $id;
?>">
			<?php 
echo $enc->html($this->translate('client', 'Title'), $enc::TRUST);
?>
		</label>
	</div>
	<div class="col-md-7">
		<input class="form-control" type="text" autocomplete="given-name"
			id="address-<?php 
echo $this->get('type', 'payment');
?>-title-<?php 
echo $id;
?>"
			name="<?php 
echo $enc->attr($this->formparam(array_merge($fnames, [$prefix . 'title'])));
?>"
			value="<?php 
echo $enc->attr($this->value($addr, $prefix . 'title'));
?>"
			placeholder="<?php 
echo $enc->attr($this->translate('client', 'Title'));
?>"
			<?php 
echo !$disabled && $this->value($css, 'title') ? '' : 'disabled';
?>
		>
	</div>
</div>


<div class="row form-item form-group company <?php 
echo $enc->attr(($this->value($error, 'company') ? 'error ' : '') . join(' ', $this->value($css, 'company', [])));
?>"
	data-regex="<?php 
echo $enc->attr($this->config('client/html/common/address/validate/company'));
?>">

	<div class="col-md-5">
		<label for="address-<?php 
echo $this->get('type', 'payment');
?>-company-<?php 
echo $id;
?>">
			<?php 
echo $enc->html($this->translate('client', 'Company'), $enc::TRUST);
?>
		</label>
	</div>
	<div class="col-md-7">
		<input class="form-control" type="text" autocomplete="organization"
			id="address-<?php 
echo $this->get('type', 'payment');
?>-company-<?php 
echo $id;
?>"
			name="<?php 
echo $enc->attr($this->formparam(array_merge($fnames, [$prefix . 'company'])));
?>"
			value="<?php 
echo $enc->attr($this->value($addr, $prefix . 'company'));
?>"
			placeholder="<?php 
echo $enc->attr($this->translate('client', 'Company'));
?>"
			<?php 
echo !$disabled && $this->value($css, 'company') ? '' : 'disabled';
?>
		>
	</div>
</div>


<div class="row form-item form-group address1 <?php 
echo $enc->attr(($this->value($error, 'address1') ? 'error ' : '') . join(' ', $this->value($css, 'address1', [])));
?>"
	data-regex="<?php 
echo $enc->attr($this->config('client/html/common/address/validate/address1'));
?>">

	<div class="col-md-5">
		<label for="address-<?php 
echo $this->get('type', 'payment');
?>-address1-<?php 
echo $id;
?>">
			<?php 
echo $enc->html($this->translate('client', 'Street'), $enc::TRUST);
?>
		</label>
	</div>
	<div class="col-md-7">
		<input class="form-control" type="text" autocomplete="address-line1"
			id="address-<?php 
echo $this->get('type', 'payment');
?>-address1-<?php 
echo $id;
?>"
			name="<?php 
echo $enc->attr($this->formparam(array_merge($fnames, [$prefix . 'address1'])));
?>"
			value="<?php 
echo $enc->attr($this->value($addr, $prefix . 'address1'));
?>"
			placeholder="<?php 
echo $enc->attr($this->translate('client', 'Street'));
?>"
			<?php 
echo !$disabled && $this->value($css, 'address1') ? '' : 'disabled';
?>
		>
	</div>
</div>


<div class="row form-item form-group address2 <?php 
echo $enc->attr(($this->value($error, 'address2') ? 'error ' : '') . join(' ', $this->value($css, 'address2', [])));
?>"
	data-regex="<?php 
echo $enc->attr($this->config('client/html/common/address/validate/address2'));
?>">

	<div class="col-md-5">
		<label for="address-<?php 
echo $this->get('type', 'payment');
?>-address2-<?php 
echo $id;
?>">
			<?php 
echo $enc->html($this->translate('client', 'Additional'), $enc::TRUST);
?>
		</label>
	</div>
	<div class="col-md-7">
		<input class="form-control" type="text" autocomplete="address-line2"
			id="address-<?php 
echo $this->get('type', 'payment');
?>-address2-<?php 
echo $id;
?>"
			name="<?php 
echo $enc->attr($this->formparam(array_merge($fnames, [$prefix . 'address2'])));
?>"
			value="<?php 
echo $enc->attr($this->value($addr, $prefix . 'address2'));
?>"
			placeholder="<?php 
echo $enc->attr($this->translate('client', 'Additional'));
?>"
			<?php 
echo !$disabled && $this->value($css, 'address2') ? '' : 'disabled';
?>
		>
	</div>
</div>


<div class="row form-item form-group address3 <?php 
echo $enc->attr(($this->value($error, 'address3') ? 'error ' : '') . join(' ', $this->value($css, 'address3', [])));
?>"
	data-regex="<?php 
echo $enc->attr($this->config('client/html/common/address/validate/address3'));
?>">

	<div class="col-md-5">
		<label for="address-<?php 
echo $this->get('type', 'payment');
?>-address3-<?php 
echo $id;
?>">
			<?php 
echo $enc->html($this->translate('client', 'Additional 2'), $enc::TRUST);
?>
		</label>
	</div>
	<div class="col-md-7">
		<input class="form-control" type="text" autocomplete="address-line3"
			id="address-<?php 
echo $this->get('type', 'payment');
?>-address3-<?php 
echo $id;
?>"
			name="<?php 
echo $enc->attr($this->formparam(array_merge($fnames, [$prefix . 'address3'])));
?>"
			value="<?php 
echo $enc->attr($this->value($addr, $prefix . 'address3'));
?>"
			placeholder="<?php 
echo $enc->attr($this->translate('client', 'Additional 2'));
?>"
			<?php 
echo !$disabled && $this->value($css, 'address3') ? '' : 'disabled';
?>
		>
	</div>
</div>

<div class="row form-item form-group city <?php 
echo $enc->attr(($this->value($error, 'city') ? 'error ' : '') . join(' ', $this->value($css, 'city', [])));
?>"
	data-regex="<?php 
echo $enc->attr($this->config('client/html/common/address/validate/city'));
?>">

	<div class="col-md-5">
		<label for="address-<?php 
echo $this->get('type', 'payment');
?>-city-<?php 
echo $id;
?>">
			<?php 
echo $enc->html($this->translate('client', 'City'), $enc::TRUST);
?>
		</label>
	</div>
	<div class="col-md-7">
		<input class="form-control" type="text" autocomplete="address-level2"
			id="address-<?php 
echo $this->get('type', 'payment');
?>-city-<?php 
echo $id;
?>"
			name="<?php 
echo $enc->attr($this->formparam(array_merge($fnames, [$prefix . 'city'])));
?>"
			value="<?php 
echo $enc->attr($this->value($addr, $prefix . 'city'));
?>"
			placeholder="<?php 
echo $enc->attr($this->translate('client', 'City'));
?>"
			<?php 
echo !$disabled && $this->value($css, 'city') ? '' : 'disabled';
?>
		>
	</div>
</div>


<div class="row form-item form-group postal <?php 
echo $enc->attr(($this->value($error, 'postal') ? 'error ' : '') . join(' ', $this->value($css, 'postal', [])));
?>"
	data-regex="<?php 
echo $enc->attr($this->config('client/html/common/address/validate/postal'));
?>">

	<div class="col-md-5">
		<label for="address-<?php 
echo $this->get('type', 'payment');
?>-postal-<?php 
echo $id;
?>">
			<?php 
echo $enc->html($this->translate('client', 'Postal code'), $enc::TRUST);
?>
		</label>
	</div>
	<div class="col-md-7">
		<input class="form-control" type="text" autocomplete="postal-code"
			id="address-<?php 
echo $this->get('type', 'payment');
?>-postal-<?php 
echo $id;
?>"
			name="<?php 
echo $enc->attr($this->formparam(array_merge($fnames, [$prefix . 'postal'])));
?>"
			value="<?php 
echo $enc->attr($this->value($addr, $prefix . 'postal'));
?>"
			placeholder="<?php 
echo $enc->attr($this->translate('client', 'Postal code'));
?>"
			<?php 
echo !$disabled && $this->value($css, 'postal') ? '' : 'disabled';
?>
		>
	</div>
</div>


<?php 
if (count($this->get('states', [])) > 0) {
    ?>
	<div class="row form-item form-group state <?php 
    echo $enc->attr(($this->value($error, 'state') ? 'error ' : '') . join(' ', $this->value($css, 'state', [])));
    ?>">

		<div class="col-md-5">
			<label for="address-<?php 
    echo $this->get('type', 'payment');
    ?>-state-<?php 
    echo $id;
    ?>">
				<?php 
    echo $enc->html($this->translate('client', 'State'), $enc::TRUST);
    ?>
			</label>
		</div>
		<div class="col-md-7">
			<select class="form-control" autocomplete="address-level1"
				id="address-<?php 
    echo $this->get('type', 'payment');
    ?>-state-<?php 
    echo $id;
    ?>"
				name="<?php 
    echo $enc->attr($this->formparam(array_merge($fnames, [$prefix . 'state'])));
    ?>"
				<?php 
    echo !$disabled && $this->value($css, 'state') ? '' : 'disabled';
    ?> >

				<option value=""><?php 
    echo $enc->html($this->translate('client', 'Select state'), $enc::TRUST);
    ?></option>

				<?php 
    foreach ($this->get('states', []) as $regioncode => $state_list) {
        ?>

					<optgroup class="<?php 
        echo $regioncode;
        ?>" label="<?php 
        echo $enc->attr($this->translate('country', $regioncode));
        ?>">

						<?php 
        foreach ($state_list as $state_code => $state_name) {
            ?>
							<option value="<?php 
            echo $enc->attr($state_code);
            ?>" <?php 
            echo $this->value($addr, $prefix . 'state') == $state_code ? 'selected' : '';
            ?>>
								<?php 
            echo $enc->html($state_name);
            ?>
							</option>
						<?php 
        }
        ?>

					</optgroup>

				<?php 
    }
    ?>

			</select>
		</div>
	</div>
<?php 
}
?>


<?php 
if (count($this->get('countries', [])) > 0) {
    ?>
	<div class="row form-item form-group countryid <?php 
    echo $enc->attr(($this->value($error, 'countryid') ? 'error ' : '') . join(' ', $this->value($css, 'countryid', [])));
    ?>">

		<div class="col-md-5">
			<label for="address-<?php 
    echo $this->get('type', 'payment');
    ?>-countryid-<?php 
    echo $id;
    ?>">
				<?php 
    echo $enc->html($this->translate('client', 'Country'), $enc::TRUST);
    ?>
			</label>
		</div>
		<div class="col-md-7">
			<select class="form-control" autocomplete="country"
				id="address-<?php 
    echo $this->get('type', 'payment');
    ?>-countryid-<?php 
    echo $id;
    ?>"
				name="<?php 
    echo $enc->attr($this->formparam(array_merge($fnames, [$prefix . 'countryid'])));
    ?>"
				<?php 
    echo !$disabled && $this->value($css, 'countryid') ? '' : 'disabled';
    ?> >

				<?php 
    if (count($this->get('countries', [])) > 1) {
        ?>
					<option value=""><?php 
        echo $enc->html($this->translate('client', 'Select country'), $enc::TRUST);
        ?></option>
				<?php 
    }
    ?>

				<?php 
    foreach ($this->get('countries', []) as $country_id) {
        ?>
					<option value="<?php 
        echo $enc->attr($country_id);
        ?>" <?php 
        echo $this->value($addr, $prefix . 'countryid') == $country_id ? 'selected' : '';
        ?>>
						<?php 
        echo $enc->html($this->translate('country', $country_id));
        ?>
					</option>
				<?php 
    }
    ?>

			</select>
		</div>
	</div>
<?php 
}
?>


<?php 
if (count($this->get('languages', [])) > 0) {
    ?>
	<div class="row form-item form-group languageid <?php 
    echo $enc->attr(($this->value($error, 'languageid') ? 'error ' : '') . join(' ', $this->value($css, 'languageid', [])));
    ?>">

		<div class="col-md-5">
			<label for="address-<?php 
    echo $this->get('type', 'payment');
    ?>-languageid-<?php 
    echo $id;
    ?>">
				<?php 
    echo $enc->html($this->translate('client', 'Language'), $enc::TRUST);
    ?>
			</label>
		</div>
		<div class="col-md-7">
			<select class="form-control" autocomplete="language"
				id="address-<?php 
    echo $this->get('type', 'payment');
    ?>-languageid-<?php 
    echo $id;
    ?>"
				name="<?php 
    echo $enc->attr($this->formparam(array_merge($fnames, [$prefix . 'languageid'])));
    ?>"
				<?php 
    echo !$disabled && $this->value($css, 'languageid') ? '' : 'disabled';
    ?> >

				<?php 
    if (count($this->get('languages', [])) > 1) {
        ?>
					<option value=""><?php 
        echo $enc->html($this->translate('client', 'Select language'), $enc::TRUST);
        ?></option>
				<?php 
    }
    ?>

				<?php 
    foreach ($this->get('languages', []) as $language_id) {
        ?>
					<option value="<?php 
        echo $enc->attr($language_id);
        ?>" <?php 
        echo $this->value($addr, $prefix . 'languageid', $this->get('languageid')) == $language_id ? 'selected' : '';
        ?>>
						<?php 
        echo $enc->html($this->translate('language', $language_id));
        ?>
					</option>
				<?php 
    }
    ?>

			</select>
		</div>
	</div>
<?php 
}
?>


<div class="row form-item form-group vatid <?php 
echo $enc->attr(($this->value($error, 'vatid') ? 'error ' : '') . join(' ', $this->value($css, 'vatid', [])));
?>"
	data-regex="<?php 
echo $enc->attr($this->config('client/html/common/address/validate/vatid'));
?>">

	<div class="col-md-5">
		<label for="address-<?php 
echo $this->get('type', 'payment');
?>-vatid-<?php 
echo $id;
?>">
			<?php 
echo $enc->html($this->translate('client', 'Vat ID'), $enc::TRUST);
?>
		</label>
	</div>
	<div class="col-md-7">
		<input class="form-control" type="text"
			id="address-<?php 
echo $this->get('type', 'payment');
?>-vatid-<?php 
echo $id;
?>"
			name="<?php 
echo $enc->attr($this->formparam(array_merge($fnames, [$prefix . 'vatid'])));
?>"
			value="<?php 
echo $enc->attr($this->value($addr, $prefix . 'vatid'));
?>"
			placeholder="<?php 
echo $enc->attr($this->translate('client', 'GB999999973'));
?>"
			<?php 
echo !$disabled && $this->value($css, 'vatid') ? '' : 'disabled';
?>
		>
	</div>
</div>


<div class="row form-item form-group email <?php 
echo $enc->attr(($this->value($error, 'email') ? 'error ' : '') . join(' ', $this->value($css, 'email', [])));
?>"
	data-regex="<?php 
echo $enc->attr($this->config('client/html/common/address/validate/email', '^.+@[a-zA-Z0-9\-]+(\.[a-zA-Z0-9\-]+)*$'));
?>">

	<div class="col-md-5">
		<label for="address-<?php 
echo $this->get('type', 'payment');
?>-email-<?php 
echo $id;
?>">
			<?php 
echo $enc->html($this->translate('client', 'E-Mail'), $enc::TRUST);
?>
		</label>
	</div>
	<div class="col-md-7">
		<input class="form-control" type="email" autocomplete="email"
			id="address-<?php 
echo $this->get('type', 'payment');
?>-email-<?php 
echo $id;
?>"
			name="<?php 
echo $enc->attr($this->formparam(array_merge($fnames, [$prefix . 'email'])));
?>"
			value="<?php 
echo $enc->attr($this->value($addr, $prefix . 'email'));
?>"
			placeholder="name@example.com"
			<?php 
echo !$disabled && $this->value($css, 'email') ? '' : 'disabled';
?>
		>
	</div>
</div>


<div class="row form-item form-group telephone <?php 
echo $enc->attr(($this->value($error, 'telephone') ? 'error ' : '') . join(' ', $this->value($css, 'telephone', [])));
?>"
	data-regex="<?php 
echo $enc->attr($this->config('client/html/common/address/validate/telephone'));
?>">

	<div class="col-md-5">
		<label for="address-<?php 
echo $this->get('type', 'payment');
?>-telephone-<?php 
echo $id;
?>">
			<?php 
echo $enc->html($this->translate('client', 'Telephone'), $enc::TRUST);
?>
		</label>
	</div>
	<div class="col-md-7">
		<input class="form-control" type="tel" autocomplete="tel"
			id="address-<?php 
echo $this->get('type', 'payment');
?>-telephone-<?php 
echo $id;
?>"
			name="<?php 
echo $enc->attr($this->formparam(array_merge($fnames, [$prefix . 'telephone'])));
?>"
			value="<?php 
echo $enc->attr($this->value($addr, $prefix . 'telephone'));
?>"
			placeholder="<?php 
echo $enc->attr($this->translate('client', '+1 123 456 7890'));
?>"
			<?php 
echo !$disabled && $this->value($css, 'telephone') ? '' : 'disabled';
?>
		>
	</div>
</div>


<div class="row form-item form-group mobile <?php 
echo $enc->attr(($this->value($error, 'mobile') ? 'error ' : '') . join(' ', $this->value($css, 'mobile', [])));
?>"
	data-regex="<?php 
echo $enc->attr($this->config('client/html/common/address/validate/mobile'));
?>">

	<div class="col-md-5">
		<label for="address-<?php 
echo $this->get('type', 'payment');
?>-mobile-<?php 
echo $id;
?>">
			<?php 
echo $enc->html($this->translate('client', 'Mobile'), $enc::TRUST);
?>
		</label>
	</div>
	<div class="col-md-7">
		<input class="form-control" type="tel" autocomplete="tel"
			id="address-<?php 
echo $this->get('type', 'payment');
?>-mobile-<?php 
echo $id;
?>"
			name="<?php 
echo $enc->attr($this->formparam(array_merge($fnames, [$prefix . 'mobile'])));
?>"
			value="<?php 
echo $enc->attr($this->value($addr, $prefix . 'mobile'));
?>"
			placeholder="<?php 
echo $enc->attr($this->translate('client', '+1 123 456 7890'));
?>"
			<?php 
echo !$disabled && $this->value($css, 'mobile') ? '' : 'disabled';
?>
		>
	</div>
</div>


<div class="row form-item form-group telefax <?php 
echo $enc->attr(($this->value($error, 'telefax') ? 'error ' : '') . join(' ', $this->value($css, 'telefax', [])));
?>"
	data-regex="<?php 
echo $enc->attr($this->config('client/html/common/address/validate/telefax'));
?>">

	<div class="col-md-5">
		<label for="address-<?php 
echo $this->get('type', 'payment');
?>-telefax-<?php 
echo $id;
?>">
			<?php 
echo $enc->html($this->translate('client', 'Fax'), $enc::TRUST);
?>
		</label>
	</div>
	<div class="col-md-7">
		<input class="form-control" type="tel" autocomplete="tel"
			id="address-<?php 
echo $this->get('type', 'payment');
?>-telefax-<?php 
echo $id;
?>"
			name="<?php 
echo $enc->attr($this->formparam(array_merge($fnames, [$prefix . 'telefax'])));
?>"
			value="<?php 
echo $enc->attr($this->value($addr, $prefix . 'telefax'));
?>"
			placeholder="<?php 
echo $enc->attr($this->translate('client', '+1 123 456 7890'));
?>"
			<?php 
echo !$disabled && $this->value($css, 'telefax') ? '' : 'disabled';
?>
		>
	</div>
</div>


<div class="row form-item form-group website <?php 
echo $enc->attr(($this->value($error, 'website') ? 'error ' : '') . join(' ', $this->value($css, 'website', [])));
?>"
	data-regex="<?php 
echo $enc->attr($this->config('client/html/common/address/validate/website', '^([a-z]+://)?[a-zA-Z0-9\-]+(\.[a-zA-Z0-9\-]+)+(:[0-9]+)?(/.*)?$'));
?>">

	<div class="col-md-5">
		<label for="address-<?php 
echo $this->get('type', 'payment');
?>-website-<?php 
echo $id;
?>">
			<?php 
echo $enc->html($this->translate('client', 'Web site'), $enc::TRUST);
?>
		</label>
	</div>
	<div class="col-md-7">
		<input class="form-control" type="url" autocomplete="url"
			id="address-<?php 
echo $this->get('type', 'payment');
?>-website-<?php 
echo $id;
?>"
			name="<?php 
echo $enc->attr($this->formparam(array_merge($fnames, [$prefix . 'website'])));
?>"
			value="<?php 
echo $enc->attr($this->value($addr, $prefix . 'website'));
?>"
			placeholder="https://example.com"
			<?php 
echo !$disabled && $this->value($css, 'website') ? '' : 'disabled';
?>
		>
	</div>
</div>


<div class="row form-item form-group birthday <?php 
echo $enc->attr(($this->value($error, 'birthday') ? 'error ' : '') . join(' ', $this->value($css, 'birthday', [])));
?>">

	<div class="col-md-5">
		<label for="address-<?php 
echo $this->get('type', 'payment');
?>-birthday-<?php 
echo $id;
?>">
			<?php 
echo $enc->html($this->translate('client', 'Birthday'), $enc::TRUST);
?>
		</label>
	</div>
	<div class="col-md-7">
		<input class="form-control" type="date" autocomplete="bday"
			id="address-<?php 
echo $this->get('type', 'payment');
?>-birthday-<?php 
echo $id;
?>"
			name="<?php 
echo $enc->attr($this->formparam(array_merge($fnames, [$prefix . 'birthday'])));
?>"
			value="<?php 
echo $enc->attr($this->value($addr, $prefix . 'birthday'));
?>"
			<?php 
echo !$disabled && $this->value($css, 'birthday') ? '' : 'disabled';
?>
		>
	</div>
</div>
