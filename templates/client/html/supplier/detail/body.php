<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2020-2026
 */
/* Available data:
 * - detailSupplierItem : Supplier item incl. referenced items
 */
$enc = $this->encoder();
if (isset($this->detail_supplier_item)) {
    ?>

	<div class="section aimeos supplier-detail" itemscope itemtype="http://schema.org/Organization" data-jsonurl="<?php 
    echo $enc->attr($this->link('client/jsonapi/url'));
    ?>">
		<div class="supplier container-xxl">
			<div class="row">
				<div class="supplier-detail-basic col-sm-6 col-md-7 col-lg-8">

					<h1 class="name" itemprop="name"><?php 
    echo $enc->html($this->detail_supplier_item->get_name(), $enc::TRUST);
    ?></h1>

					<?php 
    foreach ($this->detail_supplier_item->get_ref_items('text', 'long') as $text_item) {
        ?>
						<div class="long item"><?php 
        echo $enc->html($text_item->get_content(), $enc::TRUST);
        ?></div>
					<?php 
    }
    ?>

				</div>
				<div class="supplier-detail-image col-sm-6 col-md-5 col-lg-4">
					<div class="image-single">

						<?php 
    foreach ($this->detail_supplier_item->get_ref_items('media', 'default', 'default') as $id => $media_item) {
        ?>
							<div id="image-<?php 
        echo $enc->attr($id);
        ?>" class="media-item">
								<?php 
        echo $this->image($media_item);
        ?>
							</div>
						<?php 
    }
    ?>

					</div>
				</div>
			</div>
		</div>
	</div>

<?php 
}