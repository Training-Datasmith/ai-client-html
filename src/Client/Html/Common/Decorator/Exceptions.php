<?php

declare (strict_types=1);
/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2022-2026
 * @package Client
 * @subpackage Html
 */
namespace Aimeos\Client\Html\Common\Decorator;

/**
 * Provides exception handling for HTML clients.
 *
 * @package Client
 * @subpackage Html
 */
class Exceptions extends Base implements Iface
{
    /**
     * Returns the HTML code for insertion into the body.
     *
     * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
     * @return string HTML code
     */
    public function body(string $uid = ''): string
    {
        $output = '';
        $view = $this->view();
        $context = $this->context();
        try {
            $output = $this->client()->body($uid);
        } catch (\Aimeos\Client\Html\Exception $e) {
            $error = [$context->translate('client', $e->get_message())];
            $view->errors = array_merge($view->get('errors', []), $error);
        } catch (\Aimeos\Controller\Frontend\Exception $e) {
            if ($e->get_code() >= 400) {
                throw $e;
            }
            $error = [$context->translate('controller/frontend', $e->get_message())];
            $view->errors = array_merge($view->get('errors', []), $error);
        } catch (\Aimeos\M_Shop\Exception $e) {
            if ($e->get_code() >= 400) {
                throw $e;
            }
            $error = [$context->translate('mshop', $e->get_message())];
            $view->errors = array_merge($view->get('errors', []), $error);
        } catch (\Exception $e) {
            $error = [$context->translate('client', 'A non-recoverable error occured')];
            $view->errors = array_merge($view->get('errors', []), $error);
            $this->log_exception($e);
        }
        return $view->render('error') . $output;
    }
    /**
     * Returns the HTML string for insertion into the header.
     *
     * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
     * @return string|null String including HTML tags for the header on error
     */
    public function header(string $uid = ''): ?string
    {
        $output = '';
        try {
            $output = $this->client()->header($uid);
        } catch (\Aimeos\Client\Html\Exception|\Aimeos\Controller\Frontend\Exception|\Aimeos\M_Shop\Exception) {
            // no output in header
        } catch (\Exception $e) {
            $this->log_exception($e);
        }
        return $output;
    }
    /**
     * Processes the input, e.g. store given values.
     */
    public function init(): void
    {
        $view = $this->view();
        $context = $this->context();
        try {
            $this->client()->init();
        } catch (\Aimeos\Client\Html\Exception $e) {
            $error = [$context->translate('client', $e->get_message())];
            $view->errors = array_merge($view->get('errors', []), $error);
        } catch (\Aimeos\Controller\Frontend\Exception $e) {
            if ($e->get_code() >= 400) {
                throw $e;
            }
            $error = [$context->translate('controller/frontend', $e->get_message())];
            $view->errors = array_merge($view->get('errors', []), $error);
        } catch (\Aimeos\M_Shop\Exception $e) {
            if ($e->get_code() >= 400) {
                throw $e;
            }
            $error = [$context->translate('mshop', $e->get_message())];
            $view->errors = array_merge($view->get('errors', []), $error);
        } catch (\Exception $e) {
            $error = [$context->translate('client', 'A non-recoverable error occured')];
            $view->errors = array_merge($view->get('errors', []), $error);
            $this->log_exception($e);
        }
    }
}