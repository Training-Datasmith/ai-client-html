<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018-2026
 */
$enc = $this->encoder();
/** client/html/account/subscription/url/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2018.04
 * @see client/html/account/subscription/url/controller
 * @see client/html/account/subscription/url/action
 * @see client/html/account/subscription/url/config
 * @see client/html/account/subscription/url/filter
 */
/** client/html/account/subscription/url/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2018.04
 * @see client/html/account/subscription/url/target
 * @see client/html/account/subscription/url/action
 * @see client/html/account/subscription/url/config
 * @see client/html/account/subscription/url/filter
 */
/** client/html/account/subscription/url/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2018.04
 * @see client/html/account/subscription/url/target
 * @see client/html/account/subscription/url/controller
 * @see client/html/account/subscription/url/config
 * @see client/html/account/subscription/url/filter
 */
/** client/html/account/subscription/url/config
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
 * @since 2018.04
 * @see client/html/account/subscription/url/target
 * @see client/html/account/subscription/url/controller
 * @see client/html/account/subscription/url/action
 * @see client/html/account/subscription/url/filter
 */
/** client/html/account/subscription/url/filter
 * Removes parameters for the detail page before generating the URL
 *
 * This setting removes the listed parameters from the URLs. Keep care to
 * remove no required parameters!
 *
 * @param array List of parameter names to remove
 * @since 2022.10
 * @see client/html/account/subscription/url/target
 * @see client/html/account/subscription/url/controller
 * @see client/html/account/subscription/url/action
 * @see client/html/account/subscription/url/config
 */
/// Date format with year (Y), month (m) and day (d). See http://php.net/manual/en/function.date.php
$dateformat = $this->translate('client', 'Y-m-d');
if (!$this->get('subscriptionItems', map())->is_empty()) {
    ?>

	<div class="section aimeos account-subscription" data-jsonurl="<?php 
    echo $enc->attr($this->link('client/jsonapi/url'));
    ?>">
		<div class="container-xxl">

			<h2 class="header"><?php 
    echo $enc->html($this->translate('client', 'Subscriptions'), $enc::TRUST);
    ?></h2>

			<div class="subscription-list">

				<?php 
    foreach ($this->get('subscriptionItems', []) as $id => $item) {
        ?>

					<div class="subscription-item row">

						<div class="col-12">
							<h2 class="subscription-basic">
								<span class="name">
									<?php 
        echo $enc->html($this->translate('client', 'Subscription ID'), $enc::TRUST);
        ?>
								</span>
								<span class="value">
									<?php 
        echo $enc->html($id);
        ?>
								</span>
							</h2>
						</div>

						<div class="col-12 col-md-10">
							<div class="row">
								<div class="col-md-6">
									<div class="subscription-created row">
										<span class="name col-5">
											<?php 
        echo $enc->html($this->translate('client', 'Created'), $enc::TRUST);
        ?>
										</span>
										<span class="value col-7">
											<?php 
        echo $enc->html(date_create($item->get_time_created())->format($dateformat));
        ?>
										</span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="subscription-interval row">
										<span class="name col-5">
											<?php 
        echo $enc->html($this->translate('client', 'Interval'), $enc::TRUST);
        ?>
										</span>
										<span class="value col-7">
											<?php 
        if ($interval = $this->get('listsIntervalItems', map())->get($item->get_interval())) {
            ?>
												<?php 
            echo $enc->html($interval->get_name(), $enc::TRUST);
            ?>
											<?php 
        } else {
            ?>
												<?php 
            echo $enc->html($item->get_interval(), $enc::TRUST);
            ?>
											<?php 
        }
        ?>
										</span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="subscription-datenext row">
										<span class="name col-5">
											<?php 
        echo $enc->html($this->translate('client', 'Next order'), $enc::TRUST);
        ?>
										</span>
										<span class="value col-7">
											<?php 
        if (($date = $item->get_date_next()) != null) {
            ?>
												<?php 
            echo $enc->html(date_create($date)->format($dateformat), $enc::TRUST);
            ?>
											<?php 
        }
        ?>
										</span>
									</div>
								</div>
								<div class="col-md-6">
									<div class="subscription-dateend row">
										<span class="name col-5">
											<?php 
        echo $enc->html($this->translate('client', 'End date'), $enc::TRUST);
        ?>
										</span>
										<span class="value col-7">
											<?php 
        if (($date = $item->get_date_end()) != null) {
            ?>
												<?php 
            echo $enc->html(date_create($date)->format($dateformat), $enc::TRUST);
            ?>
											<?php 
        }
        ?>
										</span>
									</div>
								</div>
							</div>
						</div>

						<div class="action col-12 col-md-2">
							<a class="btn btn-secondary show" href="#"><?php 
        echo $enc->html($this->translate('client', 'Show'));
        ?></a>
							<a class="btn btn-secondary close hidden" href="#"><?php 
        echo $enc->html($this->translate('client', 'Close'));
        ?></a>
						</div>

						<div class="account-subscription-detail common-summary col-12" style="display: none">

							<h2 class="header"><?php 
        echo $enc->html($this->translate('client', 'Subscription details'), $enc::TRUST);
        ?></h2>

							<div class="common-summary-address row">
								<div class="item payment col-sm-6">
									<div class="header">
										<h3><?php 
        echo $enc->html($this->translate('client', 'Billing address'), $enc::TRUST);
        ?></h3>
									</div>

									<div class="content">
										<?php 
        if (!empty($item->get_order_item()->get_address('payment'))) {
            ?>
											<?php 
            echo $this->partial(
                /** client/html/account/subscription/summary/address
                 * Location of the address partial template for the account subscription component
                 *
                 * To configure an alternative template for the address partial, you
                 * have to configure its path relative to the template directory
                 * (usually templates/client/html/). It's then used to display the
                 * payment or delivery address block in the account subscription component.
                 *
                 * @param string Relative path to the address partial
                 * @since 2018.04
                 * @see client/html/account/subscription/summary/detail
                 * @see client/html/account/subscription/summary/service
                 */
                $this->config('client/html/account/subscription/summary/address', 'common/summary/address'),
                ['addresses' => $item->get_order_item()->get_address('payment')]
            );
            ?>
										<?php 
        }
        ?>
									</div>
								</div><!--

								--><div class="item delivery col-sm-6">
									<div class="header">
										<h3><?php 
        echo $enc->html($this->translate('client', 'Delivery address'), $enc::TRUST);
        ?></h3>
									</div>

									<div class="content">
										<?php 
        if (!empty($item->get_order_item()->get_address('delivery'))) {
            ?>
											<?php 
            echo $this->partial($this->config('client/html/account/subscription/summary/address', 'common/summary/address'), ['addresses' => $item->get_order_item()->get_address('delivery')]);
            ?>
										<?php 
        } else {
            ?>
											<?php 
            echo $enc->html($this->translate('client', 'like billing address'), $enc::TRUST);
            ?>
										<?php 
        }
        ?>
									</div>
								</div>
							</div>


							<div class="common-summary-detail row">
								<div class="header">
									<h2><?php 
        echo $enc->html($this->translate('client', 'Details'), $enc::TRUST);
        ?></h2>
								</div>

								<div class="basket">
									<?php 
        echo $this->partial(
            /** client/html/account/subscription/summary/detail
             * Location of the detail partial template for the account subscription component
             *
             * To configure an alternative template for the detail partial, you
             * have to configure its path relative to the template directory
             * (usually templates/client/html/). It's then used to display the
             * product detail block in the account subscription component.
             *
             * @param string Relative path to the detail partial
             * @since 2018.04
             * @see client/html/account/subscription/summary/address
             * @see client/html/account/subscription/summary/service
             */
            $this->config('client/html/account/subscription/summary/detail', 'common/summary/detail'),
            ['summaryBasket' => $item->get_order_item()]
        );
        ?>
								</div>
							</div>


							<div class="button-group">
								<a class="btn btn-secondary close" href="<?php 
        echo $enc->attr($this->link('client/html/account/subscription/url'));
        ?>">
									<?php 
        echo $enc->html($this->translate('client', 'Close'), $enc::TRUST);
        ?>
								</a>
								<?php 
        if ($item->get_date_end() == null) {
            ?>
									<?php 
            $params = ['sub_action' => 'cancel', 'sub_id' => $item->get_id()];
            ?>
									<a class="btn btn-primary" href="<?php 
            echo $enc->attr($this->link('client/html/account/subscription/url', $params, ['account-subscription']));
            ?>">
										<?php 
            echo $enc->html($this->translate('client', 'Cancel'), $enc::TRUST);
            ?>
									</a>
								<?php 
        }
        ?>
							</div>
						</div>
					</div>

				<?php 
    }
    ?>

			</div>
		</div>
	</div>

<?php 
}