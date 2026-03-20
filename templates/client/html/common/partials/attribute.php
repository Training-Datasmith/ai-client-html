<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
/* Available data:
 * - productItem : Product item the attributes are associated to
 * - attributeTypes : List of attribute type items
 */
/** client/html/catalog/attribute/preselect
 * Pre-select first item in selection list
 *
 * No option of the available selections for a product is pre-selected by
 * default. This setting removes the hint to select an option, so the first one
 * is selected by default.
 *
 * The key for each value must be the type code of the attribute, e.g. "width",
 * "length", "color" or similar types. You can set the layout for all
 * attributes at once using e.g.
 *
 *  client/html/catalog/attribute/preselect = array(
 *      'width' => false,
 *      'color' => true,
 *  )
 *
 * Similarly, you can set the pre-selection for a specific attribute only,
 * leaving the rest untouched:
 *
 *  client/html/catalog/attribute/preselect/color = true
 *
 * @param boolean True to select the first option by default, false to display the select hint
 * @since 2017.04
 */
/** client/html/catalog/attribute/type
 * List of layout types for the optional attributes
 *
 * Each product can contain optional attributes and this configuration setting
 * allows you to change how these attributs will be displayed, either as
 * drop-down menu (value: "select") or as list of radio buttons (value:
 * "radio").
 *
 * The key for each value must be the type code of the attribute, e.g. "width",
 * "length", "color" or similar types. You can set the layout for all
 * attributes at once using e.g.
 *
 *  client/html/catalog/attribute/type = array(
 *      'width' => 'select',
 *      'color' => 'radio',
 *  )
 *
 * Similarly, you can set the layout type for a specific attribute only,
 * leaving the rest untouched:
 *
 *  client/html/catalog/attribute/type/color = radio
 *
 * Note: Up to 2015.10 this option was available as
 * client/html/catalog/detail/basket/attribute/type
 *
 * @param array List of attribute types as key and layout types as value, e.g. "select" or "radio"
 * @since 2015.10
 * @see client/html/catalog/selection/type
 */
/** client/html/catalog/attribute/type/color
 * Layout types for the color attribute
 *
 * @see client/html/catalog/attribute/type
 */
/** client/html/catalog/attribute/type/size
 * Layout types for the size attribute
 *
 * @see client/html/catalog/attribute/type
 */
$enc = $this->encoder();
$attr_types = $this->get('attributeTypes', []);
?>
<ul class="selection">

	<?php 
foreach ($this->product_item->get_ref_items('attribute', null, 'config')->group_by('attribute.type') as $code => $attributes) {
    ?>
		<?php 
    $key = $this->product_item->get_id() . '-' . $code . '_' . rand(1, 1000);
    ?>
		<?php 
    $attributes = map($attributes)->uasort(fn($a, $b) => $a->get_position() <=> $b->get_position() ?: $a->get_name() <=> $b->get_name());
    ?>

		<li class="select-item <?php 
    echo $enc->attr($code . ' ' . $this->config('client/html/catalog/attribute/type/' . $code, 'select'));
    ?>">
			<label for="select-<?php 
    echo $enc->attr($key);
    ?>" class="select-name"><?php 
    echo $enc->html(isset($attr_types[$code]) ? $attr_types[$code]->get_name() : $this->translate('client/code', $code));
    ?></label>

			<?php 
    if ($hint = $this->translate('client/code', $code . '-hint', null, 0, false)) {
        ?>
				<div class="select-hint"><?php 
        echo $enc->html($hint);
        ?></div>
			<?php 
    }
    ?>

			<div class="select-value">

				<?php 
    if ($this->config('client/html/catalog/attribute/type/' . $code, 'select') === 'input') {
        ?>

					<ul id="select-<?php 
        echo $enc->attr($key);
        ?>" class="select-list">

						<?php 
        foreach ($attributes as $attr_id => $attribute) {
            ?>

							<li class="input-group select-entry">
								<input type="hidden"
									value="<?php 
            echo $enc->attr($attr_id);
            ?>"
									data-code="<?php 
            echo $enc->attr($attribute->get_code());
            ?>"
									name="<?php 
            echo $enc->attr($this->formparam(['b_prod', 0, 'attrconfid', 'id', '']));
            ?>"
								>
								<input class="form-control select-option" type="number" value="0" step="1" min="0"
									id="option-<?php 
            echo $enc->attr($this->product_item->get_id() . '-' . $attr_id);
            ?>"
									name="<?php 
            echo $enc->attr($this->formparam(['b_prod', 0, 'attrconfid', 'qty', '']));
            ?>"
								><!--
								--><label class="form-control select-label" for="option-<?php 
            echo $enc->attr($this->product_item->get_id() . '-' . $attr_id);
            ?>">
									<?php 
            echo $enc->html($this->attrname($attribute));
            ?>
								</label>
							</li>

						<?php 
        }
        ?>

					</ul>

				<?php 
    } elseif ($this->config('client/html/catalog/attribute/type/' . $code, 'select') === 'radio') {
        ?>

					<ul id="select-<?php 
        echo $enc->attr($key);
        ?>" class="select-list">

						<?php 
        foreach ($attributes as $attr_id => $attribute) {
            ?>

							<li class="select-entry">
								<input class="select-option" type="radio"
									id="option-<?php 
            echo $enc->attr($this->product_item->get_id() . '-' . $attr_id);
            ?>"
									name="<?php 
            echo $enc->attr($this->formparam(['b_prod', 0, 'attrconfid', 'id', '']));
            ?>"
									value="<?php 
            echo $enc->attr($attr_id);
            ?>"
									data-code="<?php 
            echo $enc->attr($attribute->get_code());
            ?>"
								>
								<label class="select-label" for="option-<?php 
            echo $enc->attr($this->product_item->get_id() . '-' . $attr_id);
            ?>"><!--

									<?php 
            foreach ($attribute->get_list_items('media', 'default', 'icon') as $list_item) {
                ?>
										<?php 
                if (($item = $list_item->get_ref_item()) !== null) {
                    ?>

											<?php 
                    echo '-->' . $this->partial($this->config('client/html/common/partials/media', 'common/partials/media'), ['item' => $item, 'boxAttributes' => ['class' => 'media-item']]) . '<!--';
                    ?>

										<?php 
                }
                ?>
									<?php 
            }
            ?>

									--><span><?php 
            echo $enc->html($this->attrname($attribute));
            ?></span><!--
								--></label>
							</li>

						<?php 
        }
        ?>

					</ul>

				<?php 
    } else {
        ?>

					<input type="hidden" value="1" name="<?php 
        echo $enc->attr($this->formparam(['b_prod', 0, 'attrconfid', 'qty', '']));
        ?>">
					<select id="select-<?php 
        echo $enc->attr($key);
        ?>" class="form-select select-list"
						name="<?php 
        echo $enc->attr($this->formparam(['b_prod', 0, 'attrconfid', 'id', '']));
        ?>">

						<?php 
        if ($this->config('client/html/catalog/attribute/preselect/' . $code, false) === false) {
            ?>
							<option class="select-option" value=""><?php 
            echo $enc->html($this->translate('client', 'none'));
            ?></option>
						<?php 
        }
        ?>

						<?php 
        foreach ($attributes as $id => $attribute) {
            ?>

							<option class="select-option" value="<?php 
            echo $enc->attr($id);
            ?>" data-code="<?php 
            echo $enc->attr($attribute->get_code());
            ?>">
								<?php 
            echo $enc->html($this->attrname($attribute));
            ?>
							</option>

						<?php 
        }
        ?>

					</select>

				<?php 
    }
    ?>

			</div>
		</li>

	<?php 
}
?>

</ul>


<ul class="selection">

	<?php 
foreach ($this->product_item->get_ref_items('attribute', null, 'custom') as $id => $attribute) {
    ?>
		<?php 
    $code = $attribute->get_type() . '-' . $attribute->get_code();
    ?>
		<?php 
    $key = $this->product_item->get_id() . '-' . $code . '_' . rand(1, 1000);
    ?>

		<li class="select-item <?php 
    echo $enc->attr($code);
    ?>">
			<label for="select-<?php 
    echo $enc->attr($key);
    ?>" class="select-name"><?php 
    echo $enc->html($this->translate('client/code', $attribute->get_name()));
    ?></label>

			<?php 
    if ($hint = $this->translate('client/code', $code . '-hint', null, 0, false)) {
        ?>
				<div class="select-hint"><?php 
        echo $enc->html($hint);
        ?></div>
			<?php 
    }
    ?>

			<div class="select-value">

				<?php 
    switch ($attribute->get_type()) {
        case 'price':
            ?>
					<input id="select-<?php 
            echo $enc->attr($key);
            ?>" class="form-control" type="number" min="0.01" step="0.01"
						name="<?php 
            echo $enc->attr($this->formparam(['b_prod', 0, 'attrcustid', $id]));
            ?>"
						<?php 
            if ($price = $this->product_item->get_ref_items('price', 'default', 'default')->first()) {
                ?>
							value="<?php 
                echo $enc->attr($price->get_value());
                ?>"
						<?php 
            }
            ?>
					>
				<?php 
            break;
        // no break
        case 'date':
            ?>
					<input id="select-<?php 
            echo $enc->attr($key);
            ?>" class="form-control" type="date" name="<?php 
            echo $enc->attr($this->formparam(['b_prod', 0, 'attrcustid', $id]));
            ?>">
				<?php 
            break;
        default:
            ?>
					<input id="select-<?php 
            echo $enc->attr($key);
            ?>" class="form-control" type="text" name="<?php 
            echo $enc->attr($this->formparam(['b_prod', 0, 'attrcustid', $id]));
            ?>">
				<?php 
    }
    ?>

			</div>
		</li>

	<?php 
}
?>

</ul>
