<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2019-2026
 */
/* Available data:
 * - productItem : Selection product for the variant products
 * - productItems : List of variant product items with referenced items
 * - attributeTypes : List of attribute type items
 */
$enc = $this->encoder();
$attr_types = $this->get('attributeTypes', []);
?>
<div class="selection">

	<div class="row">
		<div class="col-3 select-media"></div>
		<div class="col-3 select-name"><?php 
echo $enc->html($this->translate('client', 'Name'));
?></div>
		<div class="col-3 select-attr"><?php 
echo $enc->html($this->translate('client', 'Variant'));
?></div>
		<div class="col-1 select-stock"><?php 
echo $enc->html($this->translate('client', 'Stock'));
?></div>
		<div class="col-2 select-quantity"><?php 
echo $enc->html($this->translate('client', 'Quantity'));
?></div>
	</div>

	<?php 
foreach ($this->get('productItems', []) as $id => $product) {
    ?>

		<div class="row select-item">

			<div class="col-3">
				<?php 
    if (($media_item = $product->get_ref_items('media', 'default', 'default')->first()) !== null) {
        ?>
					<div class="select-media">
						<img class="media-image"
							src="<?php 
        echo $enc->attr($this->content($media_item->get_preview(), $media_item->get_file_system()));
        ?>"
							srcset="<?php 
        echo $enc->attr($this->imageset($media_item->get_previews(true), $media_item->get_file_system()));
        ?>"
							alt="<?php 
        echo $enc->attr($media_item->get_properties('title')->first());
        ?>"
							sizes="160px"
						>
					</div>
				<?php 
    }
    ?>
			</div>

			<div class="col-3 select-name">
				<h2><?php 
    echo $enc->html($product->get_name());
    ?></h2>

				<input type="hidden"
					name="<?php 
    echo $enc->attr($this->formparam(['b_prod', $id, 'prodid']));
    ?>"
					value="<?php 
    echo $enc->attr($id);
    ?>"
				>
			</div>

			<div class="col-3 select-attr">
				<ul class="attr-list">
					<?php 
    foreach ($product->get_ref_items('attribute', null, 'variant') as $attr_item) {
        ?>
						<li class="attr-item">
							<span class="name"><?php 
        echo $enc->html($attr_types[$attr_item->get_type()]?->get_name() ?? $attr_item->get_type());
        ?></span>
							<span class="value"><?php 
        echo $enc->html($attr_item->get_name());
        ?></span>

							<input type="hidden" value="<?php 
        echo $enc->attr($attr_item->get_id());
        ?>"
								name="<?php 
        echo $enc->attr($this->formparam(['b_prod', $id, 'attrvarid', $attr_item->get_type()]));
        ?>"
							>
						</li>
					<?php 
    }
    ?>
				</ul>
			</div>

			<div class="col-1 select-stock" data-prodid="<?php 
    echo $enc->attr($id);
    ?>"></div>

			<div class="col-2 select-quantity">
				<input type="number" class="form-control" placeholder="0"
					name="<?php 
    echo $enc->attr($this->formparam(['b_prod', $id, 'quantity']));
    ?>"
					min="0" max="2147483647" step="1" value="">
			</div>

		</div>

	<?php 
}
?>

</div>
