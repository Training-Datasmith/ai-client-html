<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
$enc = $this->encoder();
/** client/html/locale/select/currency/url/config
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
 */
$config = $this->config('client/html/locale/select/currency/url/config', []);
?>
<div class="locale-select-currency">
	<h2 class="header"><?php 
echo $this->translate('client', 'Select currency');
?></h2>

	<ul class="select-menu">
		<li class="select-dropdown select-current"><a href="#"><?php 
echo $this->get('selectCurrencyId', 'EUR');
?></a>
			<ul class="select-dropdown">

				<?php 
foreach ($this->get('selectMap', map())->get($this->get('selectLanguageId', 'en'), []) as $currency => $loc_param) {
    ?>
					<li class="select-item <?php 
    echo $currency === $this->get('selectCurrencyId', 'EUR') ? 'active' : '';
    ?>">
						<a href="<?php 
    echo $enc->attr($this->url($this->request()->get_target(), $this->param('controller'), $this->param('action'), array_merge($this->get('selectParams', []), $loc_param), [], $config));
    ?>"
							title="<?php 
    echo $enc->attr($currency);
    ?>">
							<?php 
    echo $enc->html($this->translate('currency', $currency), $enc::TRUST);
    ?>
						</a>
					</li>
				<?php 
}
?>

			</ul>
		</li>
	</ul>

</div>
