<?php

declare (strict_types=1);
/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2026
 * @package Client
 * @subpackage Html
 */
namespace Aimeos\Client;

/**
 * Common factory for HTML clients
 *
 * @package Client
 * @subpackage Html
 */
class Html
{
    private static array $objects = [];
    /**
     * Creates a new client object
     *
     * @param \Aimeos\MShop\ContextIface $context Shop context instance with necessary objects
     * @param string $path Type of the client, e.g 'account/favorite' for \Aimeos\Client\Html\Account\Favorite\Standard
     * @param string|null $name Client name (default: "Standard")
     * @return \Aimeos\Client\Html\Iface HTML client implementing \Aimeos\Client\Html\Iface
     * @throws \Aimeos\Client\Html\Exception If requested client implementation couldn't be found or initialisation fails
     */
    public static function create(\Aimeos\M_Shop\Context_Iface $context, string $path, ?string $name = null): \Aimeos\Client\Html\Iface
    {
        if (empty($path)) {
            throw new \Aimeos\Client\Html\Exception('Component path is empty', 400);
        }
        if (empty($name)) {
            $name = $context->config()->get('client/html/' . $path . '/name', 'Standard');
        }
        $interface = \Aimeos\Client\Html\Iface::class;
        $classname = '\Aimeos\Client\Html\\' . str_replace('/', '\\', ucwords($path, '/')) . '\\' . $name;
        if (class_exists($classname) === false) {
            throw new \Aimeos\Client\Html\Exception(sprintf('Class "%1$s" not found', $classname, 404));
        }
        $client = self::create_component($context, $classname, $interface, $path);
        return $client->set_object($client);
    }
    /**
     * Injects a client object.
     * The object is returned via create() if an instance of the class
     * with the name name is requested.
     *
     * @param string $classname Full name of the class for which the object should be returned
     * @param \Aimeos\Client\Html\Iface|null $client ExtJS client object
     */
    public static function inject(string $classname, ?\Aimeos\Client\Html\Iface $client = null): void
    {
        self::$objects['\\' . ltrim($classname, '\\')] = $client;
    }
    /**
     * Adds the decorators to the client object.
     *
     * @param \Aimeos\MShop\ContextIface $context Context instance with necessary objects
     * @param \Aimeos\Client\Html\Iface $client Client object
     * @param string $path Path of the client in lower case, e.g. "catalog/detail"
     * @return \Aimeos\Client\Html\Iface Client object
     */
    protected static function add_component_decorators(\Aimeos\M_Shop\Context_Iface $context, \Aimeos\Client\Html\Iface $client, string $path): \Aimeos\Client\Html\Iface
    {
        $config = $context->config();
        $local_class = str_replace('/', '\\', ucwords($path, '/'));
        $classprefix = '\Aimeos\Client\Html\\' . $local_class . '\Decorator\\';
        $decorators = array_reverse($config->get('client/html/' . $path . '/decorators/local', []));
        $client = self::add_decorators($context, $client, $decorators, $classprefix);
        $classprefix = '\Aimeos\Client\Html\Common\Decorator\\';
        $decorators = array_reverse($config->get('client/html/' . $path . '/decorators/global', []));
        $client = self::add_decorators($context, $client, $decorators, $classprefix);
        /** client/html/common/decorators/default
         * Configures the list of decorators applied to all html clients
         *
         * Decorators extend the functionality of a class by adding new aspects
         * (e.g. log what is currently done), executing the methods of the underlying
         * class only in certain conditions (e.g. only for logged in users) or
         * modify what is returned to the caller.
         *
         * This option allows you to configure a list of decorator names that should
         * be wrapped around the original instance of all created clients:
         *
         *  client/html/common/decorators/default = array( 'decorator1', 'decorator2' )
         *
         * This would wrap the decorators named "decorator1" and "decorator2" around
         * all client instances in that order. The decorator classes would be
         * "\Aimeos\Client\Html\Common\Decorator\Decorator1" and
         * "\Aimeos\Client\Html\Common\Decorator\Decorator2".
         *
         * @param array List of decorator names
         * @since 2014.03
         */
        $decorators = array_reverse($config->get('client/html/common/decorators/default', []));
        $excludes = $config->get('client/html/' . $path . '/decorators/excludes', []);
        foreach ($decorators as $key => $name) {
            if (in_array($name, $excludes)) {
                unset($decorators[$key]);
            }
        }
        $classprefix = '\Aimeos\Client\Html\Common\Decorator\\';
        return self::add_decorators($context, $client, $decorators, $classprefix);
    }
    /**
     * Adds the decorators to the client object.
     *
     * @param \Aimeos\MShop\ContextIface $context Context instance with necessary objects
     * @param \Aimeos\Client\Html\Iface $client Client object
     * @param array $decorators List of decorator name that should be wrapped around the client
     * @param string $classprefix Decorator class prefix, e.g. "\Aimeos\Client\Html\Catalog\Decorator\"
     * @return \Aimeos\Client\Html\Iface Client object
     * @throws \LogicException If class can't be instantiated
     */
    protected static function add_decorators(\Aimeos\M_Shop\Context_Iface $context, \Aimeos\Client\Html\Iface $client, array $decorators, string $classprefix): \Aimeos\Client\Html\Iface
    {
        $interface = \Aimeos\Client\Html\Common\Decorator\Iface::class;
        foreach ($decorators as $name) {
            if (ctype_alnum($name) === false) {
                throw new \LogicException(sprintf('Invalid class name "%1$s"', $name), 400);
            }
            $client = \Aimeos\Utils::create($classprefix . $name, [$client, $context], $interface);
        }
        return $client;
    }
    /**
     * Creates a client object.
     *
     * @param \Aimeos\MShop\ContextIface $context Context instance with necessary objects
     * @param string $classname Name of the client class
     * @param string $interface Name of the client interface
     * @param string $path Type of the client, e.g 'account/favorite' for \Aimeos\Client\Html\Account\Favorite\Standard
     * @return \Aimeos\Client\Html\Iface Client object
     * @throws \Aimeos\Client\Html\Exception If client couldn't be found or doesn't implement the interface
     */
    protected static function create_component(\Aimeos\M_Shop\Context_Iface $context, string $classname, string $interface, string $path): \Aimeos\Client\Html\Iface
    {
        if (isset(self::$objects[$classname])) {
            return self::$objects[$classname];
        }
        $client = \Aimeos\Utils::create($classname, [$context], $interface);
        return self::add_component_decorators($context, $client, $path);
    }
}