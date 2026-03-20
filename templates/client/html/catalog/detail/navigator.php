<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
$enc = $this->encoder();
if ($this->param('d_pos') !== null) {
    ?>
	<div class="catalog-detail-navigator">
		<nav>

			<?php 
    if (isset($this->navigation_prev)) {
        ?>
				<a class="prev" href="<?php 
        echo $enc->attr($this->navigation_prev);
        ?>" rel="prev"><!--
					--><?php 
        echo $enc->html($this->translate('client', 'Previous'), $enc::TRUST);
        ?><!--
				--></a>
			<?php 
    }
    ?>

			<?php 
    if (isset($this->navigation_next)) {
        ?>
				<a class="next" href="<?php 
        echo $enc->attr($this->navigation_next);
        ?>" rel="next"><!--
					--><?php 
        echo $enc->html($this->translate('client', 'Next'), $enc::TRUST);
        ?><!--
				--></a>
			<?php 
    }
    ?>

		</nav>
	</div>
<?php 
}