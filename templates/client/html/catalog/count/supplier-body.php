<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018-2026
 */
echo $this->get('supplierCountList', map())->to_json(JSON_FORCE_OBJECT);