<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
/* Available data:
 * - service : List of order service items
 * - type : Type of the service item, i.e. "delivery" or "payment"
 */
$enc = $this->encoder();
$type = $this->get('type');
foreach ($this->service as $service) {
    ?>
	<div class="item">
		<?php 
    if (($url = $service->get_media_url()) != '') {
        ?>
			<div class="item-icons">
				<img src="<?php 
        echo $enc->attr($this->content($url));
        ?>">
			</div>
		<?php 
    }
    ?>
		<h4><?php 
    echo $enc->html($service->get_name());
    ?></h4>
	</div>

	<?php 
    if (!($attributes = $service->get_attribute_items())->is_empty()) {
        ?>
		<ul class="attr-list">

			<?php 
        foreach ($attributes as $attribute) {
            ?>
				<?php 
            if (strpos($attribute->get_type(), 'hidden') === false) {
                ?>
					<li class="<?php 
                echo $enc->attr($type . '-' . $attribute->get_code());
                ?>">

						<span class="name">
							<?php 
                if ($attribute->get_name() != '') {
                    ?>
								<?php 
                    echo $enc->html($attribute->get_name());
                    ?>
							<?php 
                } else {
                    ?>
								<?php 
                    echo $enc->html($this->translate('client/code', $attribute->get_code()));
                    ?>
							<?php 
                }
                ?>
						</span>

						<?php 
                switch ($attribute->get_value()) {
                    case 'array':
                    case 'object':
                        ?>
							<span class="value"><?php 
                        echo $enc->html(join(', ', (array) $attribute->get_value()));
                        ?></span>
						<?php 
                        break;
                    default:
                        ?>
							<span class="value"><?php 
                        echo $enc->html($attribute->get_value());
                        ?></span>
						<?php 
                }
                ?>

					</li>

				<?php 
            }
            ?>
			<?php 
        }
        ?>

		</ul>
	<?php 
    }
}