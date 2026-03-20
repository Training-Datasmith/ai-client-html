<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2020
 */
$enc = $this->encoder();
/** client/html/catalog/tree/url/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2019.01
 * @see client/html/catalog/tree/url/controller
 * @see client/html/catalog/tree/url/action
 * @see client/html/catalog/tree/url/config
 * @see client/html/catalog/tree/url/filter
 */
$target = $this->config('client/html/catalog/tree/url/target');
/** client/html/catalog/tree/url/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2019.01
 * @see client/html/catalog/tree/url/target
 * @see client/html/catalog/tree/url/action
 * @see client/html/catalog/tree/url/config
 * @see client/html/catalog/tree/url/filter
 */
$controller = $this->config('client/html/catalog/tree/url/controller', 'catalog');
/** client/html/catalog/tree/url/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2019.01
 * @see client/html/catalog/tree/url/target
 * @see client/html/catalog/tree/url/controller
 * @see client/html/catalog/tree/url/config
 * @see client/html/catalog/tree/url/filter
 */
$action = $this->config('client/html/catalog/tree/url/action', 'list');
/** client/html/catalog/tree/url/config
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
 * @since 2019.01
 * @see client/html/catalog/tree/url/target
 * @see client/html/catalog/tree/url/controller
 * @see client/html/catalog/tree/url/action
 * @see client/html/catalog/tree/url/filter
 */
$config = $this->config('client/html/catalog/tree/url/config', []);
/** client/html/catalog/tree/url/filter
 * Removes parameters for the detail page before generating the URL
 *
 * This setting removes the listed parameters from the URLs. Keep care to
 * remove no required parameters!
 *
 * @param array List of parameter names to remove
 * @since 2022.10
 * @see client/html/catalog/tree/url/target
 * @see client/html/catalog/tree/url/controller
 * @see client/html/catalog/tree/url/action
 * @see client/html/catalog/tree/url/config
 */
$filter = array_flip($this->config('client/html/catalog/tree/url/filter', []));
/** client/html/common/partials/media
 * Relative path to the media partial template file
 *
 * Partials are templates which are reused in other templates and generate
 * reoccuring blocks filled with data from the assigned values. The media
 * partial creates an HTML block of for images, video, audio or other documents.
 *
 * The partial template files are usually stored in the templates/partials/ folder
 * of the core or the extensions. The configured path to the partial file must
 * be relative to the templates/ folder, e.g. "common/partials/media.php".
 *
 * @param string Relative path to the template file
 * @since 2015.08
 */
?>
<div class="list-container level-<?php 
echo $enc->attr($this->get('level', 0));
?>">

	<?php 
foreach ($this->get('nodes', []) as $item) {
    ?>
		<?php 
    if ($item->get_status() > 0) {
        ?>
			<?php 
        $name = $item->get_name('url');
        ?>
			<?php 
        $params = array_diff_key(array_merge($this->get('params', []), ['path' => $name, 'f_name' => $name, 'f_catid' => $item->get_id()]), $filter);
        ?>
			<?php 
        $url = $this->url($item->get_target() ?: $target, $controller, $action, $params, [], $config);
        ?>

			<div class="cat-item catid-<?php 
        echo $enc->attr($item->get_id() . (!$item->get_children()->is_empty() ? ' withchild' : ' nochild') . ($this->get('path', map())->has($item->get_id()) ? ' active' : '') . ' catcode-' . $item->get_code() . ' ' . $item->get_config_value('css-class'));
        ?>"
				data-id="<?php 
        echo $item->get_id();
        ?>">

				<div class="item-links row">
					<a class="col-10 cat-link name <?php 
        echo $this->get('path', map())->has($item->get_id()) ? ' active' : '';
        ?>"
						href="<?php 
        echo $enc->attr($url);
        ?>">
						<div class="media-list">
							<?php 
        foreach ($item->get_ref_items('media', 'icon', 'default') as $media_item) {
            ?>
								<?php 
            echo $this->partial($this->config('client/html/common/partials/media', 'common/partials/media'), ['item' => $media_item, 'boxAttributes' => ['class' => 'media-item']]);
            ?>
							<?php 
        }
        ?>
						</div>
						<span class="cat-name"><?php 
        echo $enc->html($item->get_name(), $enc::TRUST);
        ?></span>
					</a>
					<?php 
        if (!$item->get_children()->is_empty()) {
            ?>
						<div class="col-2 next" data-submenu="<?php 
            echo $enc->attr($item->get_id());
            ?>"
							title="<?php 
            echo $enc->attr($this->translate('client', 'Open submenu'));
            ?>">
						</div>
					<?php 
        } else {
            ?>
						<div class="col-2"></div>
					<?php 
        }
        ?>
				</div>

				<?php 
        if (count($item->get_children()) > 0) {
            ?>

					<div id="<?php 
            echo $enc->attr($item->get_id());
            ?>" class="submenu <?php 
            echo $enc->attr($this->get('path', map())->has($item->get_id()) ? ' active opened' : '') . (!$item->get_children()->is_empty() ? ' withchild' : ' nochild');
            ?>"
						<?php 
            echo $this->get('path', map())->get_id()->last() == $item->get_id() ? 'aria-current="page"' : '';
            ?>
						data-id="<?php 
            echo $item->get_id();
            ?>">

						<div class="row header">
							<div class="col-2 back" data-submenu-close="<?php 
            echo $enc->attr($item->get_id());
            ?>"></div>
							<div class="col-8 name"><?php 
            echo $enc->html($item->get_name(), $enc::TRUST);
            ?></div>
							<div class="col-2 close"></div>
						</div>

						<?php 
            echo $this->partial($this->config('client/html/catalog/filter/tree/partial', 'catalog/filter/tree-partial'), ['nodes' => $item->get_children(), 'path' => $this->get('path', map()), 'level' => $this->get('level', 0) + 1, 'params' => $this->get('params', [])]);
            ?>

						<?php 
            if ($item->get_level() == 1) {
                ?>

							<a class="cat-img <?php 
                echo $enc->attr($this->get('path', map())->get_id()->last() == $item->get_id() ? ' active' : '');
                ?>"
								title="<?php 
                echo $enc->attr($item->get_ref_items('media', 'menu', 'default')->get_properties('title')->first() ?: $item->get_name());
                ?>"
								href="<?php 
                echo $enc->attr($url);
                ?>">

								<?php 
                foreach ($item->get_ref_items('media', 'menu', 'default') as $media_item) {
                    ?>
									<img class="img-menu" loading="lazy"
										src="<?php 
                    echo $enc->attr($this->content($media_item->get_preview(), $media_item->get_file_system()));
                    ?>"
										srcset="<?php 
                    echo $enc->attr($this->imageset($media_item->get_previews(true), $media_item->get_file_system()));
                    ?>"
										sizes="<?php 
                    echo $enc->attr($this->config('client/html/common/imageset-sizes', '(min-width: 260px) 240px, 100vw'));
                    ?>"
										alt="<?php 
                    echo $enc->attr($media_item->get_properties('name')->first() ?: $media_item->get_label());
                    ?>"
									>
								<?php 
                }
                ?>

							</a>

						<?php 
            }
            ?>

					</div>

				<?php 
        }
        ?>

			</div>
		<?php 
    }
    ?>
	<?php 
}
?>
</div>
