<?php

declare(strict_types=1);

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2026
 */

namespace Aimeos\Client\Html\Common\Decorator;

class ClientTest extends \PHPUnit\Framework\TestCase
{
    private $context;

    protected function setUp(): void
    {
        $this->context = \TestHelper::context();
    }

    public function testDecorateFactoryClientCommon()
    {
        $config = $this->context->config();
        $config->set('client/html/common/decorators/default', [ 'Example' ]);

        $object = \Aimeos\Client\Html::create($this->context, 'catalog/filter');

        $this->assertInstanceOf('\\Aimeos\\Client\\Html\\Common\\Decorator\\Iface', $object);
    }

    public function testDecorateFactoryClientGlobal()
    {
        $config = $this->context->config();
        $config->set('client/html/catalog/filter/decorators/global', [ 'Example' ]);

        $object = \Aimeos\Client\Html::create($this->context, 'catalog/filter');

        $this->assertInstanceOf('\\Aimeos\\Client\\Html\\Common\\Decorator\\Iface', $object);
    }

    public function testDecorateSubClientGlobal()
    {
        $config = $this->context->config();
        $config->set('client/html/catalog/filter/tree/decorators/global', [ 'Example' ]);

        $object = \Aimeos\Client\Html::create($this->context, 'catalog/filter')->getSubClient('tree');

        $this->assertInstanceOf('\\Aimeos\\Client\\Html\\Common\\Decorator\\Iface', $object);
    }
}
