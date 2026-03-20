<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2020-2026
 */
$enc = $this->encoder();
/** client/html/catalog/home/url/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2020.10
 * @see client/html/catalog/home/url/controller
 * @see client/html/catalog/home/url/action
 * @see client/html/catalog/home/url/config
 * @see client/html/catalog/home/url/filter
 */
/** client/html/catalog/home/url/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2020.10
 * @see client/html/catalog/home/url/target
 * @see client/html/catalog/home/url/action
 * @see client/html/catalog/home/url/config
 * @see client/html/catalog/home/url/filter
 */
/** client/html/catalog/home/url/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2020.10
 * @see client/html/catalog/home/url/target
 * @see client/html/catalog/home/url/controller
 * @see client/html/catalog/home/url/config
 * @see client/html/catalog/home/url/filter
 */
/** client/html/catalog/home/url/config
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
 * @since 2020.10
 * @see client/html/catalog/home/url/target
 * @see client/html/catalog/home/url/controller
 * @see client/html/catalog/home/url/action
 * @see client/html/catalog/home/url/filter
 */
/** client/html/catalog/home/url/filter
 * Removes parameters for the detail page before generating the URL
 *
 * This setting removes the listed parameters from the URLs. Keep care to
 * remove no required parameters!
 *
 * @param array List of parameter names to remove
 * @since 2022.10
 * @see client/html/catalog/home/url/target
 * @see client/html/catalog/home/url/controller
 * @see client/html/catalog/home/url/action
 * @see client/html/catalog/home/url/config
 */
if (isset($this->home_tree)) {
    ?>

	<title><?php 
    echo $enc->html(strip_tags($this->home_tree->get_name()));
    ?> | <?php 
    echo $enc->html($this->get('contextSiteLabel', 'Aimeos'));
    ?></title>

	<meta property="og:type" content="website">
	<meta property="og:site_name" content="<?php 
    echo $enc->attr($this->get('contextSiteLabel', 'Aimeos'));
    ?>">
	<meta property="og:title" content="<?php 
    echo $enc->attr(strip_tags($this->home_tree->get_name()));
    ?>">
	<meta property="og:url" content="<?php 
    echo $enc->attr($this->link('client/html/catalog/home/url', [], ['absoluteUri' => true]));
    ?>">

	<?php 
    if ($icon = $this->get('contextSiteIcon')) {
        ?>
		<meta property="og:image" content="<?php 
        echo $enc->attr($this->content($icon));
        ?>">
		<meta name="twitter:card" content="summary_large_image">
	<?php 
    }
    ?>

	<?php 
    foreach ($this->home_tree->get_ref_items('text', 'meta-description', 'default') as $text_item) {
        ?>
		<meta property="og:description" content="<?php 
        echo $enc->attr($text_item->get_content());
        ?>">
		<meta name="description" content="<?php 
        echo $enc->attr(strip_tags($text_item->get_content()));
        ?>">
	<?php 
    }
    ?>

	<?php 
    foreach ($this->home_tree->get_ref_items('text', 'meta-keyword', 'default') as $text_item) {
        ?>
		<meta name="keywords" content="<?php 
        echo $enc->attr(strip_tags($text_item->get_content()));
        ?>">
	<?php 
    }
    ?>

	<?php 
    if (($media = $this->home_tree->get_ref_items('media', 'stage', 'default'))->is_empty()) {
        $media = $this->home_tree->get_children()->get_ref_items('media', 'stage', 'default');
    }
    ?>

	<?php 
    if ($media_item = $media->flat()->first()) {
        ?>
		<link rel="preload" as="image"
			href="<?php 
        echo $enc->attr($this->content($media_item->get_preview(), $media_item->get_file_system()));
        ?>"
			imagesrcset="<?php 
        echo $enc->attr($this->imageset($media_item->get_previews(true), $media_item->get_file_system()));
        ?>">
	<?php 
    }
    ?>

<?php 
} else {
    ?>

	<title><?php 
    echo $enc->html($this->get('contextSiteLabel', 'Aimeos'));
    ?></title>

<?php 
}
?>

<link rel="stylesheet" href="<?php 
echo $enc->attr($this->content($this->get('contextSiteTheme', 'default') . '/slider.css', 'fs-theme', true));
?>">
<link rel="stylesheet" href="<?php 
echo $enc->attr($this->content($this->get('contextSiteTheme', 'default') . '/catalog-home.css', 'fs-theme', true));
?>">

<script defer src="<?php 
echo $enc->attr($this->content($this->get('contextSiteTheme', 'default') . '/slider.js', 'fs-theme', true));
?>"></script>
<script defer src="<?php 
echo $enc->attr($this->content($this->get('contextSiteTheme', 'default') . '/catalog-home.js', 'fs-theme', true));
?>"></script>

<meta name="application-name" content="Aimeos">
