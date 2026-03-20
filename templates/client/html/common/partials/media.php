<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
/* Available data:
 * - item : Media item object implementing \MShop\Media\Item\Iface
 */
if (($item = $this->get('item')) === null) {
    return;
}
$enc = $this->encoder();
$boxattr = '';
foreach ($this->get('boxAttributes', []) as $name => $value) {
    $boxattr .= $name . ($value != null ? '="' . $enc->attr($value) . '" ' : '');
}
$itemattr = '';
foreach ($this->get('itemAttributes', []) as $name => $value) {
    $itemattr .= $name . ($value != null ? '="' . $enc->attr($value) . '" ' : '');
}
switch (current(explode('/', $item->get_mimetype()))) {
    case 'audio':
        ?>

	<audio <?php 
        echo $boxattr;
        ?>>
		<source src="<?php 
        echo $enc->attr($this->content($item->get_url(true), $item->get_file_system()));
        ?>"
			title="<?php 
        echo $enc->attr($item->get_properties('title')->first($item->get_name()));
        ?>"
			type="<?php 
        echo $enc->attr($item->get_mimetype());
        ?>" <?php 
        echo $itemattr;
        ?>>
		<?php 
        foreach ($item->get_ref_items('media') as $sub_item) {
            ?>
			<source src="<?php 
            echo $enc->attr($this->content($sub_item->get_url(true), $sub_item->get_file_system()));
            ?>"
				title="<?php 
            echo $enc->attr($sub_item->get_properties('title')->first($item->get_name()));
            ?>"
				type="<?php 
            echo $enc->attr($sub_item->get_mimetype());
            ?>" <?php 
            echo $itemattr;
            ?>>
		<?php 
        }
        ?>
		<?php 
        echo $enc->html($item->get_name());
        ?>
	</audio>

	<?php 
        break;
    // no break
    case 'video':
        ?>

		<video <?php 
        echo $boxattr;
        ?>>
			<source src="<?php 
        echo $enc->attr($this->content($item->get_url(true), $item->get_file_system()));
        ?>"
				title="<?php 
        echo $enc->attr($item->get_properties('title')->first($item->get_name()));
        ?>"
				type="<?php 
        echo $enc->attr($item->get_mimetype());
        ?>" <?php 
        echo $itemattr;
        ?>>
		<?php 
        foreach ($item->get_ref_items('media') as $sub_item) {
            ?>
			<source src="<?php 
            echo $enc->attr($this->content($sub_item->get_url(true), $sub_item->get_file_system()));
            ?>"
				title="<?php 
            echo $enc->attr($sub_item->get_properties('title')->first($item->get_name()));
            ?>"
				type="<?php 
            echo $enc->attr($sub_item->get_mimetype());
            ?>" <?php 
            echo $itemattr;
            ?>>
		<?php 
        }
        ?>
		<?php 
        echo $enc->html($item->get_name());
        ?>
	</video>

	<?php 
        break;
    // no break
    case 'image':
        ?>

		<span <?php 
        echo $boxattr;
        ?>><!--
			--><img sizes="100vw"
				alt="<?php 
        echo $enc->attr($item->get_properties('title')->first($item->get_name()));
        ?>" <?php 
        echo $itemattr;
        ?>
				src="<?php 
        echo $enc->attr($this->content($item->get_preview(), $item->get_file_system()));
        ?>"
				<?php 
        echo !empty($item->get_previews()) ? 'srcset="' . $enc->attr($this->imageset($item->get_previews(true), $item->get_file_system())) . '"' : '';
        ?>
			><!--
		<?php 
        foreach ($item->get_ref_items('media') as $sub_item) {
            ?>
			--><img sizes="100vw"
				alt="<?php 
            echo $enc->attr($sub_item->get_properties('title')->first($item->get_name()));
            ?>" <?php 
            echo $itemattr;
            ?>
				src="<?php 
            echo $enc->attr($this->content($sub_item->get_preview(), $sub_item->get_file_system()));
            ?>"
				<?php 
            echo !empty($sub_item->get_previews()) ? 'srcset="' . $enc->attr($this->imageset($sub_item->get_previews(true), $sub_item->get_file_system())) . '"' : '';
            ?>
			><!--
		<?php 
        }
        ?>
		--></span>

	<?php 
        break;
    // no break
    default:
        ?>

		<a href="<?php 
        echo $enc->attr($this->content($item->get_url(true), $item->get_file_system()));
        ?>" <?php 
        echo $boxattr;
        ?>><!--
			--><img src="<?php 
        echo $enc->attr($this->content($item->get_preview(), $item->get_file_system()));
        ?>"
				title="<?php 
        echo $enc->attr($item->get_properties('title')->first($item->get_name()));
        ?>" <?php 
        echo $itemattr;
        ?>
			><?php 
        echo $enc->html($item->get_name());
        ?><!--
		--></a>

<?php 
}