<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
$enc = $this->encoder();
/** client/html/catalog/filter/button
 * Displays the "Search" button in the catalog filter if Javascript is disabled
 *
 * Usually the "Search" button is shown in the catalog filter if the browser
 * doesn't support Javascript or the user has disabled Javascript for the site.
 * If you are using parts of the catalog filter to e.g. render a menu, the
 * button shouldn't be displayed at all. This can be explicitely set via this
 * configuration option.
 *
 * @param boolean A value of "1" to enable the button, "0" to disable it
 * @since 2014.03
 */
$multi = $this->config('client/html/catalog/multiroute', false);
$link_key = $multi && $this->param('path') || $this->param('f_catid') ? 'client/html/catalog/tree/url' : 'client/html/catalog/lists/url';
$attr_ids = array_filter($this->param('f_attrid', []));
$opt_ids = array_filter($this->param('f_optid', []));
$one_ids = array_filter($this->param('f_oneid', []));
$attr_types = $this->get('detailAttributeTypes', []);
$attr_map = $this->get('attributeMap', []);
$params = $this->param();
$this->block()->start('catalog/filter/attribute');
if (!empty($attr_map)) {
    ?>
	<div class="section catalog-filter-attribute"
		aria-label="<?php 
    echo $enc->attr($this->translate('client', 'Product filters'));
    ?>"
		data-counturl="<?php 
    echo $enc->attr($this->link('client/html/catalog/count/url', ['count' => 'attribute'] + $this->get('filterParams', [])));
    ?>">

		<div class="attr-header header-name"><?php 
    echo $enc->html($this->translate('client', 'Filter'), $enc::TRUST);
    ?></div>

		<div class="attribute-lists">

			<?php 
    if (array_merge($attr_ids, $opt_ids, $one_ids) !== []) {
        ?>

				<div class="attribute-selected">
					<a class="btn reset" href="<?php 
        echo $enc->attr($this->link($link_key, $this->get('attributeResetParams', [])));
        ?>">
						<?php 
        echo $enc->html($this->translate('client', 'Reset'), $enc::TRUST);
        ?>
					</a>

					<div class="selected">
						<div class="selected-intro"><?php 
        echo $enc->html($this->translate('client', 'Your choice'), $enc::TRUST);
        ?></div>

						<?php 
        foreach ($attr_map as $attr_type => $attributes) {
            ?>
							<?php 
            foreach ($attributes as $id => $attribute) {
                ?>
								<?php 
                if (($key = array_search($id, $attr_ids)) !== false) {
                    ?>
									<?php 
                    $current = $params;
                    if (is_array($current['f_attrid'])) {
                        unset($current['f_attrid'][$key]);
                    }
                    ?>
								<?php 
                } elseif (($key = array_search($id, $opt_ids)) !== false) {
                    ?>
									<?php 
                    $current = $params;
                    if (is_array($current['f_optid'])) {
                        unset($current['f_optid'][$key]);
                    }
                    ?>
								<?php 
                } elseif (isset($one_ids[$attr_type]) && ($key = array_search($id, (array) $one_ids[$attr_type])) !== false) {
                    ?>
									<?php 
                    $current = $params;
                    if (is_array($current['f_oneid'][$attr_type])) {
                        unset($current['f_oneid'][$attr_type][$key]);
                    }
                    ?>
								<?php 
                } else {
                    continue;
                    ?>
								<?php 
                }
                ?>

								<a class="minibutton close" href="<?php 
                echo $enc->attr($this->link($link_key, $attribute->get('params', [])));
                ?>">
									<?php 
                echo $enc->html($attribute->get_name(), $enc::TRUST);
                ?>
								</a>
							<?php 
            }
            ?>
						<?php 
        }
        ?>
					</div>
				</div>

			<?php 
    }
    ?>


			<div class="fieldsets">

				<?php 
    foreach ($attr_map as $attr_type => $attributes) {
        ?>
					<?php 
        if (!empty($attributes)) {
            ?>

						<fieldset class="attr-sets attr-<?php 
            echo $enc->attr($attr_type, $enc::TAINT, '-');
            ?>">
							<legend class="attr-type"><?php 
            echo $enc->html($attr_types[$attr_type]?->get_name() ?? $attr_type);
            ?></legend>
							<ul class="attr-list"><!--

								<?php 
            foreach ($attributes as $id => $attribute) {
                ?>
									--><li class="attr-item" data-id="<?php 
                echo $enc->attr($id);
                ?>">

										<input class="attr-item" type="checkbox"
											id="attr-<?php 
                echo $enc->attr($id);
                ?>"
											value="<?php 
                echo $enc->attr($id);
                ?>"
											name="<?php 
                echo $enc->attr($this->formparam($attribute->get('formparam', [])));
                ?>"
											<?php 
                echo $attribute->get('checked', false) ? 'checked="checked"' : '';
                ?>
										>

										<label class="attr-name" for="attr-<?php 
                echo $enc->attr($id);
                ?>"><!--
											--><span class="media-list"><!--

												<?php 
                foreach ($attribute->get_ref_items('media', 'icon', 'default') as $media_item) {
                    ?>
													<?php 
                    echo '-->' . $this->partial($this->config('client/html/common/partials/media', 'common/partials/media'), ['item' => $media_item, 'boxAttributes' => ['class' => 'media-item']]) . '<!--';
                    ?>
												<?php 
                }
                ?>

											--></span>
											<span><?php 
                echo $enc->html($attribute->get_name(), $enc::TRUST);
                ?></span><!--
										--></label>
									</li><!--

								<?php 
            }
            ?>
							--></ul>
						</fieldset>

					<?php 
        }
        ?>
				<?php 
    }
    ?>

			</div>
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
}
$this->block()->stop();
echo $this->block()->get('catalog/filter/attribute');