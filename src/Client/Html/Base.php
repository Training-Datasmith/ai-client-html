<?php

declare (strict_types=1);
/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2015-2026
 * @package Client
 * @subpackage Html
 */
namespace Aimeos\Client\Html;

/**
 * Common abstract class for all HTML client classes.
 *
 * @package Client
 * @subpackage Html
 */
abstract class Base implements \Aimeos\Client\Html\Iface, \Aimeos\Macro\Iface
{
    use \Aimeos\Macro\Macroable;
    private \Aimeos\M_Shop\Context_Iface $context;
    private ?\Aimeos\Base\View\Iface $view = null;
    private ?\Aimeos\Client\Html\Iface $object = null;
    private ?\Aimeos\Base\View\Iface $cached_view = null;
    private ?array $subclients = null;
    private array $cache = [];
    /**
     * Initializes the class instance.
     *
     * @param \Aimeos\MShop\ContextIface $context Context object
     */
    public function __construct(\Aimeos\M_Shop\Context_Iface $context)
    {
        $this->context = $context;
    }
    /**
     * Returns the HTML code for insertion into the body.
     *
     * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
     * @return string HTML code
     */
    public function body(string $uid = ''): string
    {
        $html = '';
        $type = $this->client_type();
        $parts = explode('/', $type);
        $list = array_merge($parts, ['body']);
        $template = join('/', array_splice($list, 0, 2, [])) . '/' . join('-', $list);
        // populate view only once
        if (count($parts) === 2) {
            $view = $this->cached_view ??= $this->object()->data($this->view());
        } else {
            $view = $this->view();
        }
        foreach ($this->get_sub_clients() as $subclient) {
            $html .= $subclient->set_view($view)->body($uid);
        }
        return $view->set('body', $html)->render($view->config("client/html/{$type}/template-body", $template));
    }
    /**
     * Adds the data to the view object required by the templates
     *
     * @param \Aimeos\Base\View\Iface $view The view object which generates the HTML output
     * @param array &$tags Result array for the list of tags that are associated to the output
     * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
     * @return \Aimeos\Base\View\Iface The view object with the data required by the templates
     */
    public function data(\Aimeos\Base\View\Iface $view, array &$tags = [], ?string &$expire = null): \Aimeos\Base\View\Iface
    {
        foreach ($this->get_sub_clients() as $subclient) {
            $view = $subclient->data($view, $tags, $expire);
        }
        return $view;
    }
    /**
     * Returns the sub-client given by its name.
     *
     * @param string $type Name of the client type
     * @param string|null $name Name of the sub-client (Default if null)
     * @return \Aimeos\Client\Html\Iface Sub-client object
     */
    public function get_sub_client(string $type, ?string $name = null): \Aimeos\Client\Html\Iface
    {
        return $this->create_sub_client($this->client_type() . '/' . $type, $name);
    }
    /**
     * Returns the HTML string for insertion into the header.
     *
     * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
     * @return string|null String including HTML tags for the header on error
     */
    public function header(string $uid = ''): ?string
    {
        $type = $this->client_type();
        $view = $this->cached_view ??= $this->object()->data($this->view());
        return $view->render($view->config("client/html/{$type}/template-header", $type . '/header'));
    }
    /**
     * Processes the input, e.g. store given values.
     *
     * A view must be available and this method doesn't generate any output
     * besides setting view variables.
     */
    public function init(): void
    {
        $view = $this->view();
        foreach ($this->get_sub_clients() as $subclient) {
            $subclient->set_view($view)->init();
        }
    }
    /**
     * Modifies the cached content to replace content based on sessions or cookies.
     *
     * @param string $content Cached content
     * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
     * @return string Modified content
     */
    public function modify(string $content, string $uid): string
    {
        $view = $this->view();
        foreach ($this->get_sub_clients() as $subclient) {
            $subclient->set_view($view);
            $content = $subclient->modify($content, $uid);
        }
        return $content;
    }
    /**
     * Returns the PSR-7 response object for the request
     *
     * @return \Psr\Http\Message\ResponseInterface Response object
     */
    public function response(): \Psr\Http\Message\Response_Interface
    {
        return $this->view()->response();
    }
    /**
     * Injects the reference of the outmost client object or decorator
     *
     * @param \Aimeos\Client\Html\Iface $object Reference to the outmost client or decorator
     * @return \Aimeos\Client\Html\Iface Client object for chaining method calls
     */
    public function set_object(\Aimeos\Client\Html\Iface $object): \Aimeos\Client\Html\Iface
    {
        $this->object = $object;
        return $this;
    }
    /**
     * Sets the view object that will generate the HTML output.
     *
     * @param \Aimeos\Base\View\Iface $view The view object which generates the HTML output
     * @return \Aimeos\Client\Html\Iface Reference to this object for fluent calls
     */
    public function set_view(\Aimeos\Base\View\Iface $view): \Aimeos\Client\Html\Iface
    {
        $this->view = $view;
        return $this;
    }
    /**
     * Returns the outmost decorator of the decorator stack
     *
     * @return \Aimeos\Client\Html\Iface Outmost decorator object
     */
    protected function object(): \Aimeos\Client\Html\Iface
    {
        if ($this->object !== null) {
            return $this->object;
        }
        return $this;
    }
    /**
     * Returns the view object that will generate the HTML output.
     *
     * @return \Aimeos\Base\View\Iface $view The view object which generates the HTML output
     */
    protected function view(): \Aimeos\Base\View\Iface
    {
        if (!isset($this->view)) {
            throw new \Aimeos\Client\Html\Exception(sprintf('No view available'));
        }
        return $this->view;
    }
    /**
     * Adds the decorators to the client object
     *
     * @param \Aimeos\Client\Html\Iface $client Client object
     * @param array $decorators List of decorator name that should be wrapped around the client
     * @param string $classprefix Decorator class prefix, e.g. "\Aimeos\Client\Html\Catalog\Decorator\"
     * @return \Aimeos\Client\Html\Iface Client object
     * @throws \LogicException If class can't be instantiated
     */
    protected function add_decorators(\Aimeos\Client\Html\Iface $client, array $decorators, string $classprefix): \Aimeos\Client\Html\Iface
    {
        $interface = \Aimeos\Client\Html\Common\Decorator\Iface::class;
        foreach ($decorators as $name) {
            if (ctype_alnum($name) === false) {
                $classname = is_string($name) ? $classprefix . $name : '<not a string>';
                throw new \Aimeos\Client\Html\Exception(sprintf('Invalid class name "%1$s"', $classname));
            }
            $classname = $classprefix . $name;
            $client = \Aimeos\Utils::create($classname, [$client, $this->context], $interface);
        }
        return $client;
    }
    /**
     * Adds the decorators to the client object
     *
     * @param \Aimeos\Client\Html\Iface $client Client object
     * @param string $path Client string in lower case, e.g. "catalog/detail/basic"
     * @return \Aimeos\Client\Html\Iface Client object
     */
    protected function add_client_decorators(\Aimeos\Client\Html\Iface $client, string $path): \Aimeos\Client\Html\Iface
    {
        if ($path === '') {
            throw new \Aimeos\Client\Html\Exception(sprintf('Invalid domain "%1$s"', $path));
        }
        $local_class = str_replace('/', '\\', ucwords($path, '/'));
        $config = $this->context->config();
        $classprefix = '\Aimeos\Client\Html\Common\Decorator\\';
        $decorators = $config->get('client/html/' . $path . '/decorators/global', []);
        $client = $this->add_decorators($client, $decorators, $classprefix);
        $classprefix = '\Aimeos\Client\Html\\' . $local_class . '\Decorator\\';
        $decorators = $config->get('client/html/' . $path . '/decorators/local', []);
        return $this->add_decorators($client, $decorators, $classprefix);
    }
    /**
     * Adds the cache tags to the given list and sets a new expiration date if necessary based on the given item.
     *
     * @param \Aimeos\MShop\Common\Item\Iface|iterable $items Item or list of items, maybe with associated list items
     * @param string|null &$expire Expiration date that will be overwritten if an earlier date is found
     * @param array &$tags List of tags the new tags will be added to
     * @param array $custom List of custom tags which are added too
     */
    protected function add_meta_items($items, ?string &$expire, array &$tags, array $custom = [])
    {
        /** client/html/common/cache/tag-all
         * Adds tags for all items used in a cache entry
         *
         * Each cache entry storing rendered parts for the HTML header or body
         * can be tagged with information which items like texts, media, etc.
         * are used in the HTML. This allows removing only those cache entries
         * whose content has really changed and only that entries have to be
         * rebuild the next time.
         *
         * The standard behavior stores only tags for each used domain, e.g. if
         * a text is used, only the tag "text" is added. If you change a text
         * in the administration interface, all cache entries with the tag
         * "text" will be removed from the cache. This effectively wipes out
         * almost all cached entries, which have to be rebuild with the next
         * request.
         *
         * Important: As a list or detail view can use several hundred items,
         * this configuration option will also add this number of tags to the
         * cache entry. When using a cache adapter that can't insert all tags
         * at once, this slows down the initial cache insert (and therefore the
         * page speed) drastically! It's only recommended to enable this option
         * if you use the DB, Mysql or Redis adapter that can insert all tags
         * at once.
         *
         * @param boolean True to add tags for all items, false to use only a domain tag
         * @since 2014.07
         * @see client/html/common/cache/force
         * @see madmin/cache/manager/name
         * @see madmin/cache/name
         */
        $tag_all = $this->context->config()->get('client/html/common/cache/tag-all', true);
        $expires = [];
        foreach (map($items) as $item) {
            if ($item instanceof \Aimeos\M_Shop\Common\Item\Lists_Ref\Iface) {
                $this->add_meta_item_ref($item, $expires, $tags, $tag_all);
            }
            $this->add_meta_item_single($item, $expires, $tags, $tag_all);
        }
        if ($expire !== null) {
            $expires[] = $expire;
        }
        if (!empty($expires)) {
            $expire = min($expires);
        }
        $tags = array_unique(array_merge($tags, $custom));
        return $items;
    }
    /**
     * Adds the cache tags to the given list and sets a new expiration date if necessary based on the given catalog tree.
     *
     * @param \Aimeos\MShop\Catalog\Item\Iface $tree Tree node, maybe with sub-nodes
     * @param string|null &$expire Expiration date that will be overwritten if an earlier date is found
     * @param array &$tags List of tags the new tags will be added to
     * @param array $custom List of custom tags which are added too
     */
    protected function add_meta_item_catalog(\Aimeos\M_Shop\Catalog\Item\Iface $tree, ?string &$expire = null, array &$tags = [], array $custom = [])
    {
        $this->add_meta_items($tree, $expire, $tags, $custom);
        foreach ($tree->get_children() as $child) {
            $this->add_meta_item_catalog($child, $expire, $tags, $custom);
        }
    }
    /**
     * Adds expire date and tags for a single item.
     *
     * @param \Aimeos\MShop\Common\Item\Iface $item Item, maybe with associated list items
     * @param array &$expires Will contain the list of expiration dates
     * @param array &$tags List of tags the new tags will be added to
     * @param bool $tagAll True of tags for all items should be added, false if only for the main item
     */
    private function add_meta_item_single(\Aimeos\M_Shop\Common\Item\Iface $item, array &$expires, array &$tags, bool $tag_all): void
    {
        $domain = str_replace('/', '_', $item->get_resource_type());
        // maximum compatiblity
        if (in_array($item->get_resource_type(), ['catalog', 'product', 'supplier', 'cms'])) {
            $tags[] = $tag_all ? $domain . '-' . $item->get_id() : $domain;
        }
        if ($item instanceof \Aimeos\M_Shop\Common\Item\Time\Iface && ($date = $item->get_date_end()) !== null) {
            $expires[] = $date;
        }
        if ($item instanceof \Aimeos\M_Shop\Common\Item\Lists_Ref\Iface) {
            $this->add_meta_item_ref($item, $expires, $tags, $tag_all);
        }
    }
    /**
     * Adds expire date and tags for referenced items
     *
     * @param \Aimeos\MShop\Common\Item\ListsRef\Iface $item Item with associated list items
     * @param array &$expires Will contain the list of expiration dates
     * @param array &$tags List of tags the new tags will be added to
     * @param bool $tagAll True of tags for all items should be added, false if only for the main item
     */
    private function add_meta_item_ref(\Aimeos\M_Shop\Common\Item\Lists_Ref\Iface $item, array &$expires, array &$tags, bool $tag_all): void
    {
        foreach ($item->get_list_items() as $list_item) {
            if (($ref_item = $list_item->get_ref_item()) === null) {
                continue;
            }
            if ($tag_all === true && in_array($list_item->get_domain(), ['catalog', 'product', 'supplier'])) {
                $tags[] = str_replace('/', '_', $list_item->get_domain()) . '-' . $list_item->get_ref_id();
            }
            if (($date = $list_item->get_date_end()) !== null) {
                $expires[] = $date;
            }
            $this->add_meta_item_single($ref_item, $expires, $tags, $tag_all);
        }
    }
    /**
     * Returns the client type of the class
     *
     * @return string Client type, e.g. "catalog/detail"
     */
    protected function client_type(): string
    {
        return strtolower(trim(dirname(str_replace('\\', '/', substr(static::class, 19))), '/'));
    }
    /**
     * Returns the sub-client given by its name.
     *
     * @param string $path Name of the sub-part in lower case (can contain a path like catalog/filter/tree)
     * @param string|null $name Name of the implementation, will be from configuration (or Default) if null
     * @return \Aimeos\Client\Html\Iface Sub-part object
     * @throws \LogicException If class can't be instantiated
     */
    protected function create_sub_client(string $path, ?string $name = null): \Aimeos\Client\Html\Iface
    {
        $path = strtolower($path);
        $name = $name ?: $this->context->config()->get('client/html/' . $path . '/name', 'Standard');
        if (empty($name) || ctype_alnum($name) === false) {
            throw new \LogicException(sprintf('Invalid characters in client name "%1$s"', $name), 400);
        }
        $subnames = str_replace('/', '\\', ucwords($path, '/'));
        $classname = '\Aimeos\Client\Html\\' . $subnames . '\\' . $name;
        $interface = \Aimeos\Client\Html\Iface::class;
        $object = \Aimeos\Utils::create($classname, [$this->context], $interface);
        $object = $this->add_client_decorators($object, $path);
        return $object->set_object($object);
    }
    /**
     * Returns the minimal expiration date.
     *
     * @param string|null $first First expiration date or null
     * @param string|null $second Second expiration date or null
     * @return string|null Expiration date
     */
    protected function expires(?string $first = null, ?string $second = null): ?string
    {
        return $first !== null ? $second !== null ? min($first, $second) : $first : $second;
    }
    /**
     * Returns the parameters used by the html client.
     *
     * @param array $params Associative list of all parameters
     * @param array $prefixes List of prefixes the parameters must start with
     * @return array Associative list of parameters used by the html client
     */
    protected function get_client_params(array $params, array $prefixes = ['f_', 'l_', 'd_', 'path']): array
    {
        return map($params)->filter(fn($val, $key) => \Aimeos\Base\Str::starts($key, $prefixes))->to_array();
    }
    /**
     * Returns the context object.
     *
     * @return \Aimeos\MShop\ContextIface Context object
     */
    protected function context(): \Aimeos\M_Shop\Context_Iface
    {
        return $this->context;
    }
    /**
     * Generates an unique hash from based on the input suitable to be used as part of the cache key
     *
     * @param array $prefixes List of prefixes the parameters must start with
     * @param string $key Unique identifier if the content is placed more than once on the same page
     * @param array $config Multi-dimensional array of configuration options used by the client and sub-clients
     * @return string Unique hash
     */
    protected function get_param_hash(array $prefixes = ['f_', 'l_', 'd_'], string $key = '', array $config = []): string
    {
        $locale = $this->context()->locale();
        $pstr = map($this->get_client_params($this->view()->param(), $prefixes))->ksort()->to_json();
        if (($cstr = json_encode($config)) === false) {
            throw new \Aimeos\Client\Html\Exception('Unable to encode parameters or configuration options');
        }
        return md5($key . $pstr . $cstr . $locale->get_language_id() . $locale->get_currency_id() . $locale->get_site_id());
    }
    /**
     * Returns the list of sub-client names configured for the client.
     *
     * @return array List of HTML client names
     */
    protected function get_sub_client_names(): array
    {
        return [];
    }
    /**
     * Returns the configured sub-clients or the ones named in the default parameter if none are configured.
     *
     * @return array List of sub-clients implementing \Aimeos\Client\Html\Iface	ordered in the same way as the names
     */
    protected function get_sub_clients(): array
    {
        if (!isset($this->subclients)) {
            $this->subclients = [];
            foreach ($this->get_sub_client_names() as $name) {
                $this->subclients[$name] = $this->get_sub_client($name);
            }
        }
        return $this->subclients;
    }
    /**
     * Returns the cache entry for the given unique ID and type.
     *
     * @param string $type Type of the cache entry, i.e. "body" or "header"
     * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
     * @param string[] $prefixes List of prefixes of all parameters that are relevant for generating the output
     * @param string $confkey Configuration key prefix that matches all relevant settings for the component
     * @return string|null Cached entry or null if not available
     */
    protected function cached(string $type, string $uid, array $prefixes, string $confkey): ?string
    {
        $context = $this->context();
        $config = $context->config();
        /** client/html/common/cache/force
         * Enforces content caching regardless of user logins
         *
         * Caching the component output is normally disabled as soon as the
         * user has logged in. This enables displaying user or user group
         * specific content without mixing standard and user specific output.
         *
         * If you don't have any user or user group specific content
         * (products, categories, attributes, media, prices, texts, etc.),
         * you can enforce content caching nevertheless to keep response
         * times as low as possible.
         *
         * @param boolean True to cache output regardless of login, false for no caching
         * @since 2015.08
         * @see client/html/common/cache/tag-all
         */
        $force = $config->get('client/html/common/cache/force', false);
        $enable = $config->get($confkey . '/cache', true);
        if ($enable == false || $force == false && $context->user() !== null) {
            return null;
        }
        $cfg = array_merge($config->get('client/html', []), $this->get_sub_client_names());
        $keys = ['body' => $this->get_param_hash($prefixes, $uid . ':' . $confkey . ':body', $cfg), 'header' => $this->get_param_hash($prefixes, $uid . ':' . $confkey . ':header', $cfg)];
        if (!isset($this->cache[$keys[$type]])) {
            $this->cache = $context->cache()->get_multiple($keys);
        }
        return $this->cache[$keys[$type]] ?? null;
    }
    /**
     * Returns the cache entry for the given type and unique ID.
     *
     * @param string $type Type of the cache entry, i.e. "body" or "header"
     * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
     * @param string[] $prefixes List of prefixes of all parameters that are relevant for generating the output
     * @param string $confkey Configuration key prefix that matches all relevant settings for the component
     * @param string $value Value string that should be stored for the given key
     * @param array $tags List of tag strings that should be assoicated to the given value in the cache
     * @param string|null $expire Date/time string in "YYYY-MM-DD HH:mm:ss"	format when the cache entry expires
     * @return string Cached value
     */
    protected function cache(string $type, string $uid, array $prefixes, string $confkey, string $value, array $tags, ?string $expire = null): string
    {
        $context = $this->context();
        $config = $context->config();
        $force = $config->get('client/html/common/cache/force', false);
        $enable = $config->get($confkey . '/cache', true);
        if (!$value || !$enable || !$force && $context->user()) {
            return $value;
        }
        $cfg = array_merge($config->get('client/html', []), $this->get_sub_client_names());
        $key = $this->get_param_hash($prefixes, $uid . ':' . $confkey . ':' . $type, $cfg);
        $context->cache()->set($key, $value, $expire, array_unique($tags));
        return $value;
    }
    /**
     * Writes the exception details to the log
     *
     * @param \Exception $e Exception object
     * @param int $level Log level of the exception
     */
    protected function log_exception(\Exception $e, int $level = \Aimeos\Base\Logger\Iface::WARN)
    {
        $uri = $this->view()->request()->get_server_params()['REQUEST_URI'] ?? '';
        $msg = ($uri ? $uri . PHP_EOL : '') . $e->get_message() . PHP_EOL . $e->get_trace_as_string();
        $this->context->logger()->log($msg, $level, 'client/html');
    }
    /**
     * Replaces the section in the content that is enclosed by the marker.
     *
     * @param string $content Cached content
     * @param string $section New section content
     * @param string $marker Name of the section marker without "<!-- " and " -->" parts
     */
    protected function replace_section(string $content, string $section, string $marker): string
    {
        $marker = '<!-- ' . $marker . ' -->';
        $clen = strlen($content);
        $mlen = strlen($marker);
        $len = strlen($section);
        $start = 0;
        while ($start + 2 * $mlen <= $clen && ($start = strpos($content, $marker, $start)) !== false) {
            if (($end = strpos($content, $marker, $start + $mlen)) !== false) {
                $content = substr_replace($content, $section, $start + $mlen, $end - $start - $mlen);
            }
            $start += 2 * $mlen + $len;
        }
        return $content;
    }
}