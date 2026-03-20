<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
$enc = $this->encoder();
if (isset($this->product)) {
    ?>

	<a href="<?php 
    echo $enc->attr($this->link('client/html/catalog/detail/url', ['path' => $this->product->get_name('url'), 'd_name' => $this->product->get_name('url'), 'd_prodid' => $this->product->get_id(), 'd_pos' => '']));
    ?>">

		<?php 
    if (($media_item = $this->product->get_ref_items('media', 'default', 'default')->first()) !== null) {
        ?>
			<div class="media-item" style="background-image: url('<?php 
        echo $enc->attr($this->content($media_item->get_preview(), $media_item->get_file_system()));
        ?>')"></div>
		<?php 
    } else {
        ?>
			<div class="media-item"></div>
		<?php 
    }
    ?>

		<h2 class="name"><?php 
    echo $enc->html($this->product->get_name(), $enc::TRUST);
    ?></h2>

		<div class="price-list">
			<?php 
    echo $this->partial($this->config('client/html/common/partials/price', 'common/partials/price'), ['prices' => $this->product->get_ref_items('price', null, 'default')]);
    ?>
		</div>

	</a>
<?php 
}