<?php

namespace Spryker\Glue\GlueStorefrontApiApplication\Plugin;

use Spryker\Glue\GlueApplication\ApiApplication\ApiApplicationContext; //@todo move application context into extension
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiApplicationPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationFactory getFactory()
 */
class GlueStorefrontApiApplicationPlugin extends AbstractPlugin implements ApiApplicationPluginInterface
{
    /**
     * @param ApiApplicationContext $apiApplicationContext
     *
     * @return bool
     */
    public function isServing(ApiApplicationContext $apiApplicationContext): bool
    {
        return true;
    }

    /**
     * @return Container
     */
    public function getDependencyContainer(): Container
    {
        return $this->getFactory()->getDependencyContainer();
    }
}
