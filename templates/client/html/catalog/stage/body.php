<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
$enc = $this->encoder();
?>
<div class="section aimeos catalog-stage <?php 
echo $enc->attr($this->get('stageCatPath', map())->get_config_value('css-class', '')->join(' '));
?>"
	data-jsonurl="<?php 
echo $enc->attr($this->link('client/jsonapi/url'));
?>">

	<?php 
if (($cat_item = $this->get('stageCurrentCatItem')) && !($media_items = $cat_item->get_ref_items('media', 'stage', 'default'))->is_empty()) {
    ?>
		<div class="catalog-stage-image single-item">

			<?php 
    foreach ($media_items as $media_item) {
        ?>
				<div class="stage-item">
					<img alt="<?php 
        echo $enc->attr($media_item->get_properties('title')->first());
        ?>"
						src="<?php 
        echo $enc->attr($this->content($media_item->get_preview(true), $media_item->get_file_system()));
        ?>"
						srcset="<?php 
        echo $enc->attr($this->imageset($media_item->get_previews(true), $media_item->get_file_system()));
        ?>"
					>
				</div>
			<?php 
    }
    ?>

		</div>
	<?php 
}
?>

	<div class="catalog-stage-breadcrumb container-xxl" aria-label="<?php 
echo $enc->attr($this->translate('client', 'Breadcrumb navigation'));
?>">

		<?php 
if (isset($this->stage_cat_path)) {
    ?>
			<?php 
    $entries = [];
    $idx = 1;
    foreach ($this->get('stageCatPath', map()) as $cat) {
        $url = $cat->get_name('url');
        $entries[] = ['@type' => 'ListItem', 'position' => $idx++, 'name' => $cat->get_name(), 'item' => $this->link('client/html/catalog/tree/url', ['path' => $url, 'f_name' => $url, 'f_catid' => $cat->get_id()], ['absoluteUri' => true])];
    }
    ?>
			<script type="application/ld+json">
				{
					"@context": "https://schema.org",
					"@type": "BreadcrumbList",
					"itemListElement": <?php 
    echo json_encode($entries);
    ?>
				}
			</script>
		<?php 
}
?>

		<nav class="breadcrumb">
			<span class="title"><?php 
echo $enc->html($this->translate('client', 'You are here:'), $enc::TRUST);
?></span>
			<ol>

				<?php 
if (isset($this->stage_cat_path)) {
    ?>
					<?php 
    foreach ($this->get('stageCatPath', map()) as $cat) {
        ?>
						<?php 
        $name = $cat->get_name('url');
        ?>
						<li>
							<a href="<?php 
        echo $enc->attr($this->link('client/html/catalog/tree/url', array_merge($this->get('stageParams', []), ['path' => $name, 'f_name' => $name, 'f_catid' => $cat->get_id()])));
        ?>">
								<?php 
        echo $enc->html($cat->get_name(), $enc::TRUST);
        ?>
							</a>
						</li>
					<?php 
    }
    ?>
				<?php 
} else {
    ?>
					<li>
						<a class="back" href="#">
							<?php 
    echo $enc->html($this->translate('client', 'Back'), $enc::TRUST);
    ?>
						</a>
					</li>
				<?php 
}
?>

			</ol>
		</nav>
	</div>

</div>
