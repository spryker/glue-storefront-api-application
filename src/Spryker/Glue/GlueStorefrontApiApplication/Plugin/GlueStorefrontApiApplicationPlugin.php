<?php

namespace Spryker\Glue\GlueStorefrontApiApplication\Plugin;

use Spryker\Glue\GlueApplication\ApiApplication\ApiApplicationContext;
use Spryker\Glue\GlueApplication\Plugin\ApiApplication\HostApplicationApiContextExpander;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiApplicationPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationFactory getFactory()
 */
class GlueStorefrontApiApplicationPlugin extends AbstractPlugin implements ApiApplicationPluginInterface
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
            && preg_match('/glue\.de/', $apiApplicationContext->get('host')) > 0
        );
    }

    /**
     * @return \Spryker\Glue\Kernel\Container
     */
    public function getDependencyContainer(): Container
    {
        return $this->getFactory()->getDependencyContainer();
    }
}
