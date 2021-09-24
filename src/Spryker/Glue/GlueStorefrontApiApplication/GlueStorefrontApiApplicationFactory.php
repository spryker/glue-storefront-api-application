<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication;

use Spryker\Glue\GlueJsonApi\Plugin\RouteRequestMatcherPlugin;
use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationConfig getConfig()
 */
class GlueStorefrontApiApplicationFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\Kernel\Container
     */
    public function getDependencyContainer(): Container
    {
        return $this->getContainer();
    }

    /**
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[]
     */
    public function getApplicationPlugins(): array
    {
        return $this->getProvidedDependency(GlueStorefrontApiApplicationDependencyProvider::PLUGIN_APPLICATIONS);
    }

    /**
     * @return RouteRequestMatcherPlugin
     * @throws \Spryker\Glue\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getRouteRequestMatcherPlugin(): RouteRequestMatcherPlugin
    {
        return $this->getProvidedDependency(GlueStorefrontApiApplicationDependencyProvider::PLUGIN_REQUEST_MATCHER);
    }
}
