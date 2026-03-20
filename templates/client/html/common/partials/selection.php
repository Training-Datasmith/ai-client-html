<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2014-2026
 */
/* Available data:
 * - productItem : Selection product for the variant products
 * - productItems : List of product items including the referenced items like texts, attributes, etc.
 * - attributeTypes : List of attribute type items
 */
/** client/html/catalog/selection/preselect
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
 *  client/html/catalog/selection/preselect = array(
 *      'width' => false,
 *      'color' => true,
 *  )
 *
 * Similarly, you can set the pre-selection for a specific attribute only,
 * leaving the rest untouched:
 *
 *  client/html/catalog/selection/preselect/color = true
 *
 * @param boolean True to select the first option by default, false to display the select hint
 * @since 2016.07
 */
/** client/html/catalog/selection/type
 * List of layout types for the variant attributes
 *
 * Selection products will contain variant attributes and this configuration
 * setting allows you to change how these attributs will be displayed, either
 * as drop-down menu (value: "select") or as list of radio buttons (value:
 * "radio").
 *
 * The key for each value must be the type code of the attribute, e.g. "width",
 * "length", "color" or similar types. You can set the layout for all
 * attributes at once using e.g.
 *
 *  client/html/catalog/selection/type = array(
 *      'width' => 'select',
 *      'color' => 'radio',
 *  )
 *
 * Similarly, you can set the layout type for a specific attribute only,
 * leaving the rest untouched:
 *
 *  client/html/catalog/selection/type/color = radio
 *
 * Note: Up to 2016.10 this option was available as
 * client/html/catalog/detail/basket/selection/type
 *
 * @param array List of attribute types as key and layout types as value, e.g. "select" or "radio"
 * @since 2015.10
 * @see client/html/catalog/attribute/type
 */
/** client/html/catalog/selection/type/length
 * Layout types for the length selection
 *
 * @see client/html/catalog/selection/type
 */
/** client/html/catalog/selection/type/width
 * Layout types for the width selection
 *
 * @see client/html/catalog/selection/type
 */
$enc = $this->encoder();
$attr_deps = $prod_deps = [];
$attr_items = map();
$index = 0;
foreach ($this->get('productItems', []) as $prod_id => $product) {
    $attr_items->replace($product->get_ref_items('attribute', null, 'variant'));
    foreach ($product->get_ref_items('attribute', null, 'variant') as $attr_id => $attr_item) {
        $attr_deps[$attr_id][] = $prod_id;
        $prod_deps[$prod_id][] = $attr_id;
    }
}
$sortfcn = function ($item_a, $item_b) {
    return $item_a->get_position() <=> $item_b->get_position() ?: $item_a->get_name() <=> $item_b->get_name();
};
$attr_types = $this->get('attributeTypes', []);
?>
<ul class="selection"
	data-proddeps="<?php 
echo $enc->attr(json_encode($prod_deps));
?>"
	data-attrdeps="<?php 
echo $enc->attr(json_encode($attr_deps));
?>">

	<?php 
foreach ($attr_items->group_by('attribute.type') as $code => $list) {
    ?>
		<?php 
    $list = map($list)->uasort($sortfcn);
    ?>

		<li class="select-item <?php 
    echo $enc->attr($code . ' ' . $this->config('client/html/catalog/selection/type/' . $code, 'select'));
    ?>">
			<label class="select-name"><?php 
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
    if ($this->config('client/html/catalog/selection/type/' . $code, 'select') === 'radio') {
        ?>

					<ul id="select-<?php 
        echo $enc->attr($this->product_item->get_id() . '-' . $code);
        ?>" class="select-list"
						data-index="<?php 
        echo $index++;
        ?>" data-type="<?php 
        echo $enc->attr($code);
        ?>">

						<?php 
        $first = true;
        foreach ($list as $attr_id => $attr_item) {
            ?>

							<li class="select-entry">
								<input class="select-option" type="radio"
									id="option-<?php 
            echo $enc->attr($this->product_item->get_id() . '-' . $attr_id);
            ?>"
									name="<?php 
            echo $enc->attr($this->formparam(['b_prod', 0, 'attrvarid', $code]));
            ?>"
									value="<?php 
            echo $enc->attr($attr_id);
            ?>"
									data-code="<?php 
            echo $enc->attr($attr_item->get_code());
            ?>"
									<?php 
            echo $first && $this->config('client/html/catalog/selection/preselect/' . $code, false) ? 'checked="checked"' : '';
            ?>
								>
								<label class="select-label" for="option-<?php 
            echo $enc->attr($this->product_item->get_id() . '-' . $attr_id);
            ?>"><!--

									<?php 
            foreach ($attr_item->get_list_items('media', 'default', 'icon') as $list_item) {
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
            echo $enc->html($attr_item->get_name());
            ?></span><!--
								--></label>
							</li>

						<?php 
            $first = false;
        }
        ?>

					</ul>

				<?php 
    } else {
        ?>

					<select id="select-<?php 
        echo $enc->attr($this->product_item->get_id() . '-' . $code);
        ?>" class="form-select select-list"
						name="<?php 
        echo $enc->attr($this->formparam(['b_prod', 0, 'attrvarid', $code]));
        ?>"
						data-index="<?php 
        echo $index++;
        ?>" data-type="<?php 
        echo $enc->attr($code);
        ?>"
					>

						<?php 
        if (!$this->config('client/html/catalog/selection/preselect/' . $code, false)) {
            ?>

							<option class="select-option" value="">
								<?php 
            echo $enc->attr($this->translate('client', 'Please select'));
            ?>
							</option>

						<?php 
        }
        ?>

						<?php 
        foreach ($list as $attr_id => $attr_item) {
            ?>

							<option class="select-option" value="<?php 
            echo $enc->attr($attr_id);
            ?>" data-code="<?php 
            echo $enc->attr($attr_item->get_code());
            ?>">
								<?php 
            echo $enc->html($attr_item->get_name());
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
