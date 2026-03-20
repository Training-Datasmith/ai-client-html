<?php

declare (strict_types=1);
/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2015-2026
 * @package Client
 * @subpackage Html
 */
namespace Aimeos\Client\Html\Common\Client\Factory;

/**
 * Common factory interface for all HTML client classes.
 *
 * @package Client
 * @subpackage Html
 */
interface Iface extends \Aimeos\Client\Html\Iface
{
    /**
     * Initializes the class instance.
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     */
    public function __construct(\Aimeos\M_Shop\Context_Iface $context);
}