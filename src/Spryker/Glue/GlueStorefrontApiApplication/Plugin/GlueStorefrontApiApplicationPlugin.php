<?php

namespace Spryker\Glue\GlueStorefrontApiApplication\Plugin;

use Spryker\Glue\GlueApplication\ApiApplication\ApiApplicationContext;
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
        $isGlueHost = (
            $apiApplicationContext->has('host')
            && preg_match('/glue/', $apiApplicationContext->get('host')) > 0
        );

        return $isGlueHost;
    }

    /**
     * @return Container
     */
    public function getDependencyContainer(): Container
    {
        return $this->getFactory()->getDependencyContainer();
    }
}
