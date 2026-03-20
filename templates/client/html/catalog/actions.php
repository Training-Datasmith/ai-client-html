<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
/* Available data:
 * - productItem : Product item incl. referenced items
 */
$enc = $this->encoder();
/** client/html/catalog/actions/list
 * List of user action names that should be displayed in the catalog detail view
 *
 * Users can add products to several personal lists that are either only
 * available during the session or permanently if the user is logged in. The list
 * of pinned products is session based while the watch list and the favorite
 * products are durable. For the later two lists, the user has to be logged in
 * so the products can be associated to the user account.
 *
 * The order of the action names in the configuration determines the order of
 * the actions on the catalog detail page.
 *
 * @param array List of user action names
 * @since 2017.04
 */
$list = $this->config('client/html/catalog/actions/list', ['pin', 'watch', 'favorite']);
?>
<ul class="catalog-actions">
	<?php 
if (in_array('pin', $list)) {
    ?>
		<li>
			<form class="actions-pin" method="POST" action="<?php 
    echo $enc->attr($this->link('client/html/catalog/session/pinned/url'));
    ?>">
				<!-- catalog.detail.csrf --><?php 
    echo $this->csrf()->formfield();
    ?><!-- catalog.detail.csrf -->
				<input type="hidden" name="<?php 
    echo $this->formparam('pin_action');
    ?>" value="add">
				<input type="hidden" name="<?php 
    echo $this->formparam('pin_id');
    ?>" value="<?php 
    echo $enc->attr($this->product_item->get_id());
    ?>">
				<input type="hidden" name="<?php 
    echo $this->formparam('d_prodid');
    ?>" value="<?php 
    echo $enc->attr($this->product_item->get_id());
    ?>">
				<input type="hidden" name="<?php 
    echo $this->formparam('d_name');
    ?>" value="<?php 
    echo $this->product_item->get_name('url');
    ?>">
				<button class="actions-button actions-button-pin" type="submit" title="<?php 
    echo $enc->attr($this->translate('client/code', 'pin'));
    ?>"></button>
			</form>
		</li>
	<?php 
}
?>

	<?php 
if (in_array('watch', $list)) {
    ?>
		<li>
			<form class="actions-watch" method="POST" action="<?php 
    echo $enc->attr($this->link('client/html/account/watch/url'));
    ?>">
				<!-- catalog.detail.csrf --><?php 
    echo $this->csrf()->formfield();
    ?><!-- catalog.detail.csrf -->
				<input type="hidden" name="<?php 
    echo $this->formparam('wat_action');
    ?>" value="add">
				<input type="hidden" name="<?php 
    echo $this->formparam('wat_id');
    ?>" value="<?php 
    echo $enc->attr($this->product_item->get_id());
    ?>">
				<input type="hidden" name="<?php 
    echo $this->formparam('d_prodid');
    ?>" value="<?php 
    echo $enc->attr($this->product_item->get_id());
    ?>">
				<input type="hidden" name="<?php 
    echo $this->formparam('d_name');
    ?>" value="<?php 
    echo $this->product_item->get_name('url');
    ?>">
				<button class="actions-button actions-button-watch" type="submit" title="<?php 
    echo $enc->attr($this->translate('client/code', 'watch'));
    ?>"></button>
			</form>
		</li>
	<?php 
}
?>

	<?php 
if (in_array('favorite', $list)) {
    ?>
		<li>
			<form class="actions-favorite" method="POST" action="<?php 
    echo $enc->attr($this->link('client/html/account/favorite/url'));
    ?>">
				<!-- catalog.detail.csrf --><?php 
    echo $this->csrf()->formfield();
    ?><!-- catalog.detail.csrf -->
				<input type="hidden" name="<?php 
    echo $this->formparam('fav_action');
    ?>" value="add">
				<input type="hidden" name="<?php 
    echo $this->formparam('fav_id');
    ?>" value="<?php 
    echo $enc->attr($this->product_item->get_id());
    ?>">
				<input type="hidden" name="<?php 
    echo $this->formparam('d_prodid');
    ?>" value="<?php 
    echo $enc->attr($this->product_item->get_id());
    ?>">
				<input type="hidden" name="<?php 
    echo $this->formparam('d_name');
    ?>" value="<?php 
    echo $this->product_item->get_name('url');
    ?>">
				<button class="actions-button actions-button-favorite" type="submit" title="<?php 
    echo $enc->attr($this->translate('client/code', 'favorite'));
    ?>"></button>
			</form>
		</li>
	<?php 
}
?>
</ul>
