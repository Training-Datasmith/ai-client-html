<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
$enc = $this->encoder();
/** client/html/account/watch/url/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2014.09
 * @see client/html/account/watch/url/controller
 * @see client/html/account/watch/url/action
 * @see client/html/account/watch/url/config
 * @see client/html/account/watch/url/filter
 */
/** client/html/account/watch/url/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2014.09
 * @see client/html/account/watch/url/target
 * @see client/html/account/watch/url/action
 * @see client/html/account/watch/url/config
 * @see client/html/account/watch/url/filter
 */
/** client/html/account/watch/url/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2014.09
 * @see client/html/account/watch/url/target
 * @see client/html/account/watch/url/controller
 * @see client/html/account/watch/url/config
 * @see client/html/account/watch/url/filter
 */
/** client/html/account/watch/url/config
 * Associative list of configuration options used for generating the URL
 *
 * You can specify additional options as key/value pairs used when generating
 * the URLs, like
 *
 *  client/html/<clientname>/url/config = array( 'absoluteUri' => true )
 *
 * The available key/value pairs depend on the application that embeds the e-commerce
 * framework. This is because the infrastructure of the application is used for
 * generating the URLs. The full list of available config options is referenced
 * in the "see also" section of this page.
 *
 * @param string Associative list of configuration options
 * @since 2014.09
 * @see client/html/account/watch/url/target
 * @see client/html/account/watch/url/controller
 * @see client/html/account/watch/url/action
 * @see client/html/account/watch/url/filter
 */
/** client/html/account/watch/url/filter
 * Removes parameters for the detail page before generating the URL
 *
 * This setting removes the listed parameters from the URLs. Keep care to
 * remove no required parameters!
 *
 * @param array List of parameter names to remove
 * @since 2022.10
 * @see client/html/account/watch/url/target
 * @see client/html/account/watch/url/controller
 * @see client/html/account/watch/url/action
 * @see client/html/account/watch/url/config
 */
if (!$this->get('watchItems', map())->is_empty()) {
    ?>

	<div class="section aimeos account-watch" data-jsonurl="<?php 
    echo $enc->attr($this->link('client/jsonapi/url'));
    ?>">
		<div class="container-xxl">

			<h2 class="header"><?php 
    echo $this->translate('client', 'Watched products');
    ?></h2>

			<div class="watch-items">
				<?php 
    foreach ($this->get('watchItems', map())->reverse() as $list_item) {
        ?>
					<?php 
        if (($product_item = $list_item->get_ref_item()) !== null) {
            ?>

						<div class="product watch-item" data-prodid="<?php 
            echo $enc->attr($product_item->get_id());
            ?>">
							<?php 
            $params = ['wat_action' => 'delete', 'wat_id' => $list_item->get_ref_id()] + $this->get('watchParams', []);
            ?>
							<form class="delete" method="POST" action="<?php 
            echo $enc->attr($this->link('client/html/account/watch/url', $params));
            ?>">
								<button class="minibutton delete" title="<?php 
            echo $this->translate('client', 'Delete item');
            ?>"></button>
								<?php 
            echo $this->csrf()->formfield();
            ?>
							</form>

							<?php 
            $name = $product_item->get_name('url');
            ?>
							<?php 
            $params = ['path' => $name, 'd_name' => $name, 'd_prodid' => $product_item->get_id(), 'd_pos' => ''];
            ?>
							<a class="watch-basic" href="<?php 
            echo $enc->attr($this->link('client/html/catalog/detail/url', $params));
            ?>">
								<?php 
            $media_items = $product_item->get_ref_items('media', 'default', 'default');
            ?>

								<?php 
            if ($media_item = $media_items->first()) {
                ?>
									<div class="media-item">
										<img loading="lazy"
											sizes="<?php 
                echo $enc->attr($this->config('client/html/common/imageset-sizes', '(min-width: 260px) 240px, 100vw'));
                ?>"
											src="<?php 
                echo $enc->attr($this->content($media_item->get_preview(), $media_item->get_file_system()));
                ?>"
											srcset="<?php 
                echo $enc->attr($this->imageset($media_item->get_previews(true), $media_item->get_file_system()));
                ?>"
											alt="<?php 
                echo $enc->attr($media_item->get_properties('title')->first() ?: $media_item->get_label());
                ?>"
										>
									</div>
								<?php 
            } else {
                ?>
									<div class="media-item"></div>
								<?php 
            }
            ?>

								<h2 class="name"><?php 
            echo $enc->html($product_item->get_name(), $enc::TRUST);
            ?></h2>

								<div class="price-list">
									<?php 
            echo $this->partial($this->config('client/html/common/partials/price', 'common/partials/price'), ['prices' => $product_item->get_ref_items('price', null, 'default')]);
            ?>
								</div>
							</a>

							<?php 
            $url = $this->link('client/html/account/watch/url', $this->get('watchParams', []));
            ?>
							<form class="watch-details" method="POST" action="<?php 
            echo $enc->attr($url);
            ?>">
								<input type="hidden" name="<?php 
            echo $enc->attr($this->formparam(['wat_action']));
            ?>" value="edit">
								<input type="hidden" name="<?php 
            echo $enc->attr($this->formparam(['wat_id']));
            ?>" value="<?php 
            echo $enc->attr($list_item->get_ref_id());
            ?>">
								<?php 
            echo $this->csrf()->formfield();
            ?>

								<ul class="form-list">
									<?php 
            $config = $list_item->get_config();
            ?>

									<?php 
            $timeframe = isset($config['timeframe']) ? (int) $config['timeframe'] : 7;
            ?>
									<li class="form-item timeframe">
										<label for="watch-timeframe"><?php 
            echo $enc->html($this->translate('client', 'Time frame'), $enc::TRUST);
            ?></label><!--
										--><select id="watch-timeframe" name="<?php 
            echo $enc->attr($this->formparam(['wat_timeframe']));
            ?>">
											<option value="7" <?php 
            echo $timeframe == 7 ? 'selected="selected"' : '';
            ?>>
												<?php 
            echo $enc->html($this->translate('client', 'One week'));
            ?>
											</option>
											<option value="14" <?php 
            echo $timeframe == 14 ? 'selected="selected"' : '';
            ?>>
												<?php 
            echo $enc->html($this->translate('client', 'Two weeks'));
            ?>
											</option>
											<option value="30" <?php 
            echo $timeframe == 30 ? 'selected="selected"' : '';
            ?>>
												<?php 
            echo $enc->html($this->translate('client', 'One month'));
            ?>
											</option>
											<option value="90" <?php 
            echo $timeframe == 90 ? 'selected="selected"' : '';
            ?>>
												<?php 
            echo $enc->html($this->translate('client', 'Three month'));
            ?>
											</option>
										</select>
									</li>

									<?php 
            $price = isset($config['price']) ? (int) $config['price'] : 0;
            ?>
									<li class="form-item price">
										<label for="watch-price"><?php 
            echo $enc->html($this->translate('client', 'If price decreases'), $enc::TRUST);
            ?></label><!--
										--><input type="checkbox"
											name="<?php 
            echo $enc->attr($this->formparam(['wat_price']));
            ?>"
											id="watch-price"
											value="1"
											<?php 
            echo $price ? 'checked="checked"' : '';
            ?>
										>
										<input type="hidden"
											name="<?php 
            echo $enc->attr($this->formparam(['wat_pricevalue']));
            ?>"
											value="<?php 
            echo $enc->attr(($price_item = $product_item->get_ref_items('price', null, 'default')->first()) !== null ? $price_item->get_value() : '0.00');
            ?>"
										>
									</li>

									<?php 
            $stock = isset($config['stock']) ? (int) $config['stock'] : 0;
            ?>
									<li class="form-item stock">
										<label for="watch-stock"><?php 
            echo $enc->html($this->translate('client', 'If back in stock'), $enc::TRUST);
            ?></label><!--
											--><input type="checkbox"
												name="<?php 
            echo $enc->attr($this->formparam(['wat_stock']));
            ?>"
												id="watch-stock"
												value="1"
												<?php 
            echo $stock ? 'checked="checked"' : '';
            ?>
											>
									</li>
								</ul>

								<button class="btn btn-primary btn-action"><?php 
            echo $enc->html($this->translate('client', 'Watch'), $enc::TRUST);
            ?></button>
							</form>

						</div>

					<?php 
        }
        ?>
				<?php 
    }
    ?>
			</div>


			<?php 
    if ($this->get('watchPageLast', 1) > 1) {
        ?>
				<nav class="pagination">
					<div class="sort">
						<span>&nbsp;</span>
					</div>
					<div class="browser">

						<?php 
        $params = ['wat_page' => $this->watch_page_first] + $this->get('watchParams', []);
        ?>
						<a class="first" href="<?php 
        echo $enc->attr($this->link('client/html/account/watch/url', $params));
        ?>">
							<?php 
        echo $enc->html($this->translate('client', '◀◀'), $enc::TRUST);
        ?>
						</a>

						<?php 
        $params = ['wat_page' => $this->watch_page_prev] + $this->get('watchParams', []);
        ?>
						<a class="prev" href="<?php 
        echo $enc->attr($this->link('client/html/account/watch/url', $params));
        ?>" rel="prev">
							<?php 
        echo $enc->html($this->translate('client', '◀'), $enc::TRUST);
        ?>
						</a>

						<span>
							<?php 
        echo $enc->html(sprintf($this->translate('client', 'Page %1$d of %2$d'), $this->get('watchPageCurr', 1), $this->get('watchPageLast', 1)));
        ?>
						</span>

						<?php 
        $params = ['wat_page' => $this->watch_page_next] + $this->get('watchParams', []);
        ?>
						<a class="next" href="<?php 
        echo $enc->attr($this->link('client/html/account/watch/url', $params));
        ?>" rel="next">
							<?php 
        echo $enc->html($this->translate('client', '▶'), $enc::TRUST);
        ?>
						</a>

						<?php 
        $params = ['wat_page' => $this->watch_page_last] + $this->get('watchParams', []);
        ?>
						<a class="last" href="<?php 
        echo $enc->attr($this->link('client/html/account/watch/url', $params));
        ?>">
							<?php 
        echo $enc->html($this->translate('client', '▶▶'), $enc::TRUST);
        ?>
						</a>
					</div>
				</nav>
			<?php 
    }
    ?>

		</div>
	</div>

<?php 
}