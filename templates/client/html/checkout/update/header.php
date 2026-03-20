<?php

declare (strict_types=1);
/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
@header('HTTP/1.1 ' . $this->response()->get_status_code() . ' ' . $this->response()->get_reason_phrase());
foreach ($this->response()->get_headers() as $key => $value) {
    foreach ((array) $value as $val) {
        @header($key . ': ' . $val);
    }
}