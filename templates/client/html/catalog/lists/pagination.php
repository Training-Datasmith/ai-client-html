<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
$enc = $this->encoder();
$multi = $this->config('client/html/catalog/multiroute', false);
$link_key = $multi && $this->param('path') || $this->param('f_catid') ? 'client/html/catalog/tree/url' : 'client/html/catalog/lists/url';
$sort = $this->param('f_sort', $this->config('client/html/catalog/lists/sort', 'relevance'));
$infinite_scroll = $this->config('client/html/catalog/lists/infinite-scroll', false);
$params = $this->get('params', []);
$sortname = ltrim($sort, '-');
$name_dir = $price_dir = '';
if ($sort === 'name') {
    $name_sort = $this->translate('client', '▼ Name');
    $name_dir = '-';
} elseif ($sort === '-name') {
    $name_sort = $this->translate('client', '▲ Name');
} else {
    $name_sort = $this->translate('client', 'Name');
}
if ($sort === 'price') {
    $price_sort = $this->translate('client', '▼ Price');
    $price_dir = '-';
} elseif ($sort === '-price') {
    $price_sort = $this->translate('client', '▲ Price');
} else {
    $price_sort = $this->translate('client', 'Price');
}
?>
<nav class="pagination">

	<div class="sort" aria-label="<?php 
echo $enc->attr($this->translate('client', 'Sort by'));
?>">
		<span><?php 
echo $enc->html($this->translate('client', 'Sort by'), $enc::TRUST);
?>:</span>
		<ul>
			<li>
				<?php 
$url = $this->link($link_key, ['f_sort' => 'relevance'] + $params);
?>
				<a class="option-relevance <?php 
echo $sort === 'relevance' ? 'active' : '';
?>" href="<?php 
echo $enc->attr($url);
?>">
					<?php 
echo $enc->html($this->translate('client', 'Relevance'), $enc::TRUST);
?>
				</a>
			</li>
			<li>
				<?php 
$url = $this->link($link_key, ['f_sort' => '-ctime'] + $params);
?>
				<a class="option-ctime <?php 
echo $sort === '-ctime' ? 'active' : '';
?>" href="<?php 
echo $enc->attr($url);
?>">
					<?php 
echo $enc->html($this->translate('client', 'Latest'), $enc::TRUST);
?>
				</a>
			</li>
			<li>
				<?php 
$url = $this->link($link_key, ['f_sort' => $name_dir . 'name'] + $params);
?>
				<a class="option-name <?php 
echo $sortname === 'name' ? 'active' : '';
?>" href="<?php 
echo $enc->attr($url);
?>">
					<?php 
echo $enc->html($name_sort, $enc::TRUST);
?>
				</a>
			</li>
			<li>
				<?php 
$url = $this->link($link_key, ['f_sort' => $price_dir . 'price'] + $params);
?>
				<a class="option-price <?php 
echo $sortname === 'price' ? 'active' : '';
?>" href="<?php 
echo $enc->attr($url);
?>">
					<?php 
echo $enc->html($price_sort, $enc::TRUST);
?>
				</a>
			</li>
			<li>
				<?php 
$url = $this->link($link_key, ['f_sort' => '-rating'] + $params);
?>
				<a class="option-rating <?php 
echo $sort === '-rating' ? 'active' : '';
?>" href="<?php 
echo $enc->attr($url);
?>">
					<?php 
echo $enc->html($this->translate('client', 'Rating'), $enc::TRUST);
?>
				</a>
			</li>
		</ul>
	</div>

	<?php 
if (!$infinite_scroll && $this->last > 1) {
    ?>
		<div class="browser" aria-label="<?php 
    echo $enc->attr($this->translate('client', 'Go to page'));
    ?>">

			<?php 
    $url = $this->link($link_key, ['l_page' => 1] + $params);
    ?>
			<a class="first" href="<?php 
    echo $enc->attr($url);
    ?>" title="<?php 
    echo $enc->attr($this->translate('client', 'First'));
    ?>">
				<?php 
    echo $enc->html($this->translate('client', '◀◀'), $enc::TRUST);
    ?>
			</a>

			<?php 
    $url = $this->link($link_key, ['l_page' => $this->prev] + $params);
    ?>
			<a class="prev" href="<?php 
    echo $enc->attr($url);
    ?>" title="<?php 
    echo $enc->attr($this->translate('client', 'Previous'));
    ?>">
				<?php 
    echo $enc->html($this->translate('client', '◀'), $enc::TRUST);
    ?>
			</a>

			<span><?php 
    echo $enc->html(sprintf($this->translate('client', 'Page %1$d of %2$d'), $this->current, $this->last));
    ?></span>

			<?php 
    $url = $this->link($link_key, ['l_page' => $this->next] + $params);
    ?>
			<a class="next" href="<?php 
    echo $enc->attr($url);
    ?>" title="<?php 
    echo $enc->attr($this->translate('client', 'Next'));
    ?>">
				<?php 
    echo $enc->html($this->translate('client', '▶'), $enc::TRUST);
    ?>
			</a>

			<?php 
    $url = $this->link($link_key, ['l_page' => $this->last] + $params);
    ?>
			<a class="last" href="<?php 
    echo $enc->attr($url);
    ?>" title="<?php 
    echo $enc->attr($this->translate('client', 'Last'));
    ?>">
				<?php 
    echo $enc->html($this->translate('client', '▶▶'), $enc::TRUST);
    ?>
			</a>

		</div>
	<?php 
}
?>

</nav>
