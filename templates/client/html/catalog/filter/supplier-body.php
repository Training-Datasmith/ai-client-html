<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018-2026
 */
$enc = $this->encoder();
$multi = $this->config('client/html/catalog/multiroute', false);
$link_key = $multi && $this->param('path') || $this->param('f_catid') ? 'client/html/catalog/tree/url' : 'client/html/catalog/lists/url';
$this->block()->start('catalog/filter/supplier');
?>
	<div class="section catalog-filter-supplier"
		aria-label="<?php 
echo $enc->attr($this->translate('client', 'Supplier list'));
?>"
		data-counturl="<?php 
echo $enc->attr($this->link('client/html/catalog/count/url', ['count' => 'supplier'] + $this->get('filterParams', [])));
?>">

		<div class="header-name"><?php 
echo $enc->html($this->translate('client', 'Suppliers'), $enc::TRUST);
?></div>

		<div class="supplier-lists">
			<?php 
if ($this->param('f_supid')) {
    ?>
				<a class="btn supplier-selected" href="<?php 
    echo $enc->attr($this->link($link_key, $this->get('supplierResetParams', [])));
    ?>">
					<?php 
    echo $enc->html($this->translate('client', 'Reset'), $enc::TRUST);
    ?>
				</a>
			<?php 
}
?>

			<input class="form-control search" placeholder="<?php 
echo $enc->attr($this->translate('client', 'Search'));
?>">

			<ul class="attr-list">

				<?php 
foreach ($this->get('supplierList', []) as $id => $supplier) {
    ?>
					<li class="attr-item" data-id="<?php 
    echo $enc->attr($id);
    ?>">

						<input class="attr-item" type="checkbox"
							id="sup-<?php 
    echo $enc->attr($id);
    ?>"
							name="<?php 
    echo $enc->attr($this->formparam(['f_supid', '']));
    ?>"
							value="<?php 
    echo $enc->attr($id);
    ?>"
							<?php 
    echo in_array($id, (array) $this->param('f_supid', [])) ? 'checked="checked"' : '';
    ?>
						>

						<label class="attr-name" for="sup-<?php 
    echo $enc->attr($id);
    ?>">
							<span class="media-list">

								<?php 
    foreach ($supplier->get_ref_items('media', 'icon', 'default') as $media_item) {
        ?>
									<?php 
        echo $this->partial($this->config('client/html/common/partials/media', 'common/partials/media'), ['item' => $media_item, 'boxAttributes' => ['class' => 'media-item']]);
        ?>
								<?php 
    }
    ?>

							</span>
							<span><?php 
    echo $enc->html($supplier->get_name(), $enc::TRUST);
    ?></span>
						</label>
					</li>
				<?php 
}
?>

				<li class="attr-item prototype" data-id="">
					<input class="attr-item" type="checkbox" id="_supproto" value="" name="<?php 
echo $enc->attr($this->formparam(['f_supid', '']));
?>" disabled>
					<label class="attr-name" for="_supproto"><span></span></label>
				</li>

			</ul>
		</div>

		<?php 
if ($this->config('client/html/catalog/filter/button', true)) {
    ?>
			<noscript>
				<button class="filter btn btn-primary" type="submit">
					<?php 
    echo $enc->html($this->translate('client', 'Show'), $enc::TRUST);
    ?>
				</button>
			</noscript>
		<?php 
}
?>

	</div>
<?php 
$this->block()->stop();
echo $this->block()->get('catalog/filter/supplier');