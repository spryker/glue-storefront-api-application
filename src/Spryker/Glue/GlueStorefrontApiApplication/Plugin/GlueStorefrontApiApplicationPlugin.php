<?php

namespace Spryker\Glue\GlueStorefrontApiApplication\Plugin;

use Spryker\Glue\GlueJsonApi\Plugin\AbstractGlueJsonApiApplicationPlugin;
use Spryker\Glue\GlueApplication\ApiApplication\ApiApplicationContext;
use Spryker\Glue\GlueJsonApi\Plugin\HostApplicationApiContextExpander;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiApplicationPluginInterface;
use Spryker\Glue\GlueJsonApi\Plugin\RouteRequestMatcherPlugin;

/**
 * @method \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationFactory getFactory()
 */
class GlueStorefrontApiApplicationPlugin extends AbstractGlueJsonApiApplicationPlugin implements ApiApplicationPluginInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\ApiApplication\ApiApplicationContext $apiApplicationContext
     *
     * @return bool
     */
    public function isServing(ApiApplicationContext $apiApplicationContext): bool
    {
        return (
            $apiApplicationContext->has(HostApplicationApiContextExpander::HOST)
            && preg_match('/glue\.de/', $apiApplicationContext->get(HostApplicationApiContextExpander::HOST)) > 0
        );
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
