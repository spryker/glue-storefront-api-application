<?php

namespace Spryker\Glue\GlueStorefrontApiApplication\Plugin;

use Generated\Shared\Transfer\ApiContextTransfer;
use Spryker\Glue\JsonApiConvention\Plugin\AbstractGlueJsonRequestApiApplicationPlugin;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiApplicationPluginInterface;
use Spryker\Glue\JsonApiConvention\Plugin\RouteRequestMatcherPlugin;

/**
 * @method \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationFactory getFactory()
 */
//@todo better use injection over inheritance
class GlueStorefrontApiApplicationPlugin extends AbstractGlueJsonRequestApiApplicationPlugin implements ApiApplicationPluginInterface
{
    /**
     * @param ApiContextTransfer $apiApplicationContext
     *
     * @return bool
     */
    public function isServing(ApiContextTransfer $apiApplicationContext): bool
    {
        return preg_match('/glue\.de/', (string)$apiApplicationContext->getHost()) > 0;
    }

    /**
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[]
     */
    public function getApplicationPlugins(): array
    {
        return $this->getFactory()->getApplicationPlugins();
    }

    /**
     * @return RouteRequestMatcherPlugin
     */
    protected function getRouteRequestMatcherPlugin(): RouteRequestMatcherPlugin
    {
        return $this->getFactory()->getRouteRequestMatcherPlugin();
    }
}
