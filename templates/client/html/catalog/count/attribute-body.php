<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2026
 */
echo $this->get('attributeCountList', map())->to_json(JSON_FORCE_OBJECT);