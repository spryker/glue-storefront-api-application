<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication;

use Spryker\Glue\GlueApplication\GlueApplicationDependencyProvider;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationConfig getConfig()
 */
class GlueStorefrontApiApplicationDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @param Container $container
     * @return Container
     */
    public function provideDependencies(Container $container)
    {
        $container = parent::provideDependencies($container);
        $container = $this->addResourceRoutePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addResourceRoutePlugins(Container $container): Container
    {
        $container->set(GlueApplicationDependencyProvider::PLUGIN_RESOURCE_ROUTES, function (Container $container) {
            return $this->getResourceRoutePlugins();
        });

        return $container;
    }

    /**
     * Rest resource route plugin stack
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface[]
     */
    protected function getResourceRoutePlugins(): array
    {
        return [];
    }
}
