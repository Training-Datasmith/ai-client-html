<?php

declare (strict_types=1);
/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2026
 * @package Client
 * @subpackage Html
 */
namespace Aimeos\Client\Html\Catalog\Count\Tree;

/**
 * Default implementation of catalog count tree section HTML client.
 *
 * @package Client
 * @subpackage Html
 */
class Standard extends \Aimeos\Client\Html\Catalog\Base implements \Aimeos\Client\Html\Common\Client\Factory\Iface
{
    /**
     * Sets the necessary parameter values in the view.
     *
     * @param \Aimeos\Base\View\Iface $view The view object which generates the HTML output
     * @param array &$tags Result array for the list of tags that are associated to the output
     * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
     * @return \Aimeos\Base\View\Iface Modified view object
     */
    public function data(\Aimeos\Base\View\Iface $view, array &$tags = [], ?string &$expire = null): \Aimeos\Base\View\Iface
    {
        $context = $this->context();
        $config = $context->config();
        /** client/html/catalog/count/tree/aggregate
         * Enables or disables generating product counts for the category catalog filter
         *
         * This configuration option allows shop owners to enable or disable product counts
         * for the tree section of the catalog filter HTML client.
         *
         * @param boolean Disabled if "0", enabled if "1"
         * @since 2014.03
         */
        if ($config->get('client/html/catalog/count/tree/aggregate', true) == true) {
            $startid = $view->config('client/html/catalog/filter/tree/startid');
            $level = $view->config('client/html/catalog/lists/levels', \Aimeos\MW\Tree\Manager\Base::LEVEL_LIST);
            $cntl = \Aimeos\Controller\Frontend::create($context, 'catalog')->root(current((array) $startid));
            $root = $cntl->get_tree(\Aimeos\MW\Tree\Manager\Base::LEVEL_ONE);
            if (($cat_id = $view->param('f_catid', $root->get_id())) != null && $cat_id != $root->get_id()) {
                $cntl->visible($cntl->get_path($cat_id)->keys()->to_array());
            } elseif ($level != \Aimeos\MW\Tree\Manager\Base::LEVEL_TREE) {
                $cntl->visible([$root->get_id()]);
            }
            $tree = $cntl->get_tree();
            $cntl = \Aimeos\Controller\Frontend::create($context, 'product')->radius($view->param('f_point', []), $view->param('f_dist'))->category($tree->to_list()->keys()->to_array())->supplier($view->param('f_supid', []))->allof($view->param('f_attrid', []))->one_of($view->param('f_optid', []))->one_of($view->param('f_oneid', []))->text($view->param('f_search'))->price($view->param('f_price'))->slice(0, 0x7fffffff)->sort();
            $view->tree_count_list = $cntl->aggregate('index.catalog.id');
            if ($level === \Aimeos\MW\Tree\Manager\Base::LEVEL_TREE) {
                $view->tree_count_list = map($this->counts($this->traverse($tree, $view->tree_count_list->to_array())));
            }
        }
        return parent::data($view, $tags, $expire);
    }
    /**
     * Returns the product counts per node
     *
     * @param \Aimeos\MShop\Catalog\Item\Iface $node Tree node, maybe with children
     * @return array Associative list of catalog IDs as keys and product counts as values
     */
    protected function counts(\Aimeos\M_Shop\Catalog\Item\Iface $node): array
    {
        $list = [$node->get_id() => $node->count];
        foreach ($node->get_children() as $child) {
            $list += $this->counts($child);
        }
        return $list;
    }
    /**
     * Traverses the tree and adds the aggregated product counts to each node
     *
     * @param \Aimeos\MShop\Catalog\Item\Iface $node Tree node, maybe with children
     * @param array $counts Associative list of catalog IDs as keys and product counts as values
     * @return \Aimeos\MShop\Catalog\Item\Iface Updated tree node
     */
    protected function traverse(\Aimeos\M_Shop\Catalog\Item\Iface $node, array $counts): \Aimeos\M_Shop\Catalog\Item\Iface
    {
        $count = $counts[$node->get_id()] ?? 0;
        foreach ($node->get_children() as $child) {
            $count += $this->traverse($child, $counts)->count;
        }
        $node->count = $count;
        return $node;
    }
    /** client/html/catalog/count/tree/template-body
     * Relative path to the HTML body template of the catalog count tree client.
     *
     * The template file contains the HTML code and processing instructions
     * to generate the result shown in the body of the frontend. The
     * configuration string is the path to the template file relative
     * to the templates directory (usually in templates/client/html).
     *
     * You can overwrite the template file configuration in extensions and
     * provide alternative templates. These alternative templates should be
     * named like the default one but suffixed by
     * an unique name. You may use the name of your project for this. If
     * you've implemented an alternative client class as well, it
     * should be suffixed by the name of the new class.
     *
     * @param string Relative path to the template creating code for the HTML page body
     * @since 2014.03
     * @see client/html/catalog/count/tree/template-header
     */
}