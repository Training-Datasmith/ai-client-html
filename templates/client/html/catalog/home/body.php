<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2020-2026
 */
$enc = $this->encoder();
$pos = 0;
/** client/html/catalog/home/imageset-sizes
 * Size hints for loading the appropriate catalog home image sizes
 *
 * Modern browsers can load images of different sizes depending on their viewport
 * size. This is also known as serving "responsive images" because on small
 * smartphone screens, only small images are loaded while full width images are
 * loaded on large desktop screens.
 *
 * A responsive image contains additional "srcset" and "sizes" attributes:
 *
 *  <img src="img.jpg"
 *  	srcset="img-small.jpg 240w, img-large.jpg 720w"
 *  	sizes="(max-width: 320px) 240px, 720px"
 *  >
 *
 * The images and their width in the "srcset" attribute are automatically added
 * based on the sizes of the generated preview images. The value of the "sizes"
 * attribute can't be determined by Aimeos because it depends on the used frontend
 * theme and the size of the images defined in the CSS file. This config setting
 * adds the required value for the "sizes" attribute.
 *
 * It's value consists of one or more comma separated rules with
 * - an optional CSS media query for the view port size
 * - the (max) width the image will be displayed within this viewport size
 *
 * Rules without a media query are independent of the view port size and must be
 * always at last because the rules are evaluated from left to right and the first
 * matching rule is used.
 *
 * The above example tells the browser:
 * - Up to 320px view port width use img-small.jpg
 * - Above 320px view port width use img-large.jpg
 *
 * For more information about the "sizes" attribute of the "img" HTML tag read:
 * {@link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/img#attr-sizes}
 *
 * @param string HTML image "sizes" attribute
 * @since 2021.04
 * @see client/html/common/imageset-sizes
 */
$lazy = false;
if (isset($this->home_tree) && !($this->home_tree->get_ref_items('media', 'stage', 'default')->is_empty() && $this->home_tree->get_children()->get_ref_items('media', 'stage', 'default')->collapse(1)->is_empty())) {
    ?>

	<div class="section aimeos catalog-home swiffy-slider slider-item-nogap slider-nav-animation slider-nav-autoplay slider-nav-autopause"
		data-slider-nav-autoplay-interval="4000" data-jsonurl="<?php 
    echo $enc->attr($this->link('client/jsonapi/url'));
    ?>">

		<div class="home-gallery <?php 
    echo $enc->attr($this->home_tree->get_code());
    ?> slider-container">

			<?php 
    if (!($media_items = $this->home_tree->get_ref_items('media', 'stage', 'default'))->is_empty()) {
        ?>
				<?php 
        $name = $this->home_tree->get_name('url');
        ?>
				<?php 
        $params = ['path' => $name, 'f_name' => $name, 'f_catid' => $this->home_tree->get_id()];
        ?>
				<?php 
        $url = $this->link('client/html/catalog/tree/url', $params);
        ?>

				<?php 
        foreach ($media_items as $media_item) {
            ?>

					<div class="home-item home-image <?php 
            echo $enc->attr($this->home_tree->get_code());
            ?>">
						<div class="home-stage catalog-stage-image">
							<a class="stage-item" href="<?php 
            echo $enc->attr($url);
            ?>">
								<img class="stage-image" loading="<?php 
            echo $lazy ? 'lazy' : '';
            ?>"
									src="<?php 
            echo $enc->attr($this->content($media_item->get_preview(true), $media_item->get_file_system()));
            ?>"
									srcset="<?php 
            echo $enc->attr($this->imageset($media_item->get_previews(true), $media_item->get_file_system()));
            ?>"
									alt="<?php 
            echo $enc->attr($media_item->get_properties('name')->first());
            ?>"
								>
								<div class="stage-text">
									<div class="stage-short">
										<?php 
            foreach ($this->home_tree->get_ref_items('text', 'short', 'default') as $text_item) {
                ?>
											<?php 
                echo $text_item->get_content();
                ?>
										<?php 
            }
            ?>
									</div>
									<div class="btn"><?php 
            echo $enc->html($this->translate('client', 'Take a look'));
            ?></div>
								</div>
							</a>
						</div>
					</div>

					<?php 
            $lazy = true;
            ?>
				<?php 
        }
        ?>

			<?php 
    }
    ?>

			<?php 
    foreach ($this->home_tree->get_children() as $child) {
        ?>
				<?php 
        if (!($media_items = $child->get_ref_items('media', 'stage', 'default'))->is_empty()) {
            ?>

					<?php 
            foreach ($media_items as $media_item) {
                ?>

						<div class="home-item cat-image <?php 
                echo $enc->attr($child->get_code());
                ?>">
							<div class="home-stage catalog-stage-image">
								<?php 
                $name = $child->get_name('url');
                ?>
								<a class="stage-item row" href="<?php 
                echo $enc->attr($this->link('client/html/catalog/tree/url', ['f_catid' => $child->get_id(), 'f_name' => $name, 'path' => $name]));
                ?>">
									<img class="stage-image" loading="<?php 
                echo $lazy ? 'lazy' : '';
                ?>"
										src="<?php 
                echo $enc->attr($this->content($media_item->get_preview(true), $media_item->get_file_system()));
                ?>"
										srcset="<?php 
                echo $enc->attr($this->imageset($media_item->get_previews(true), $media_item->get_file_system()));
                ?>"
										alt="<?php 
                echo $enc->attr($media_item->get_properties('name')->first());
                ?>"
									>
									<div class="stage-text">
										<div class="stage-short">
											<?php 
                foreach ($child->get_ref_items('text', 'short', 'default') as $text_item) {
                    ?>
												<?php 
                    echo $text_item->get_content();
                    ?>
											<?php 
                }
                ?>
										</div>
										<div class="btn"><?php 
                echo $enc->html($this->translate('client', 'Take a look'));
                ?></div>
									</div>
								</a>
							</div>
						</div>

						<?php 
                $lazy = true;
                ?>
					<?php 
            }
            ?>

				<?php 
        }
        ?>
			<?php 
    }
    ?>

		</div>

		<button type="button" class="slider-nav" aria-label="Go to previous"></button>
		<button type="button" class="slider-nav slider-nav-next" aria-label="Go to next"></button>

	</div>

<?php 
}