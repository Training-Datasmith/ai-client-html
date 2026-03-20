<?php

declare (strict_types=1);
/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2020-2026
 * @package MW
 * @subpackage View
 */
namespace Aimeos\Base\View\Helper\Image;

/**
 * View helper class for creating an HTML image tag
 *
 * @package MW
 * @subpackage View
 */
class Standard extends \Aimeos\Base\View\Helper\Base implements \Aimeos\Base\View\Helper\Image\Iface
{
    /**
     * Returns the HTML image tag for the given media item
     *
     * @param \Aimeos\MShop\Media\Item\Iface $media Media item
     * @param string $sizes Preferred image srcset sizes
     * @param bool $main TRUE for main image, FALSE for secondary images
     * @return string HTML image tag
     */
    public function transform(\Aimeos\M_Shop\Media\Item\Iface $media, string $sizes = '', $main = true): string
    {
        $view = $this->view();
        $enc = $view->encoder();
        $variant = '';
        foreach ($media->get_ref_items('attribute', null, 'variant') as $id => $item) {
            $variant .= ' data-variant-' . $item->get_type() . '="' . $enc->attr($id) . '"';
        }
        if (!strncmp($media->get_mimetype(), 'video/', 6)) {
            return '
				<video autoplay muted class="item" id="image-' . $media->get_id() . '" loading="lazy"
					thumbnail="' . $enc->attr($view->content($media->get_preview(), $media->get_file_system())) . '"
					poster="' . $enc->attr($view->content($media->get_preview(600), $media->get_file_system())) . '"
					src="' . $enc->attr($view->content($media->get_url(), $media->get_file_system())) . '"
					alt="' . $enc->attr($media->get_properties('title')->first($media->get_name())) . '"
					' . $variant . '>
				</video>
			';
        }
        $srcset = !empty($media->get_previews()) ? 'srcset="' . $enc->attr($view->imageset($media->get_previews(true), $media->get_file_system())) . '"' : '';
        return '
			<div itemscope itemprop="image" itemtype="http://schema.org/ImageObject">
				<meta itemprop="representativeOfPage" content="' . ($main ? 'true' : 'false') . '">
				<img class="item" id="image-' . $media->get_id() . '" loading="lazy" itemprop="contentUrl"
					src="' . $enc->attr($view->content($media->get_preview(), $media->get_file_system())) . '"
					data-zoom="' . $enc->attr($view->content($media->get_url(), $media->get_file_system())) . '"
					alt="' . $enc->attr($media->get_properties('title')->first($media->get_name())) . '"
					sizes="' . $sizes . '" ' . $srcset . ' ' . $variant . '>
			</div>
		';
    }
}