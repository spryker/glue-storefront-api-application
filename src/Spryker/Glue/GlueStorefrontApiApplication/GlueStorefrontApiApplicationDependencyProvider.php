<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication;

use Spryker\Glue\EventDispatcher\Plugin\Application\EventDispatcherApplicationPlugin;
use Spryker\Glue\GlueApplication\GlueApplicationDependencyProvider;
use Spryker\Glue\GlueApplication\Plugin\Application\GlueApplicationApplicationPlugin;
use Spryker\Glue\JsonApiConvention\Plugin\RouteRequestMatcherPlugin;
use Spryker\Glue\GlueJsonApiExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\HelloStorefrontRestApi\Plugin\GlueApplication\HelloStorefrontResourceRoutePlugin;
use Spryker\Glue\Http\Plugin\Application\HttpApplicationPlugin;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\Router\Plugin\Application\RouterApplicationPlugin;
use Spryker\Glue\Session\Plugin\Application\SessionApplicationPlugin;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;

/**
 * @method \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationConfig getConfig()
 */
class GlueStorefrontApiApplicationDependencyProvider extends AbstractBundleDependencyProvider
{
    /** @var string */
    public const PLUGIN_RESOURCE_ROUTES = 'PLUGIN_RESOURCE_ROUTES';
    /** @var string */
    public const PLUGIN_APPLICATIONS = 'PLUGIN_APPLICATIONS';
    /** @var string */
    public const PLUGIN_REQUEST_MATCHER = 'PLUGIN_REQUEST_MATCHER';

    /**
     * @param Container $container
     * @return Container
     */
    public function provideDependencies(Container $container)
    {
        $container = parent::provideDependencies($container);
        $container = $this->addApplicationPlugins($container);
        $container = $this->addResourceRoutePlugins($container);
        $container = $this->addRequestMatcherPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addResourceRoutePlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_RESOURCE_ROUTES, function (Container $container) {
            return $this->getResourceRoutePlugins();
        });

        return $container;
    }

    /**
     * Rest resource route plugin stack
     *
     * @return ResourceRoutePluginInterface
     */
    protected function getResourceRoutePlugins(): array
    {
        return [
            new HelloStorefrontResourceRoutePlugin(), //@todo wiring should happen on project level
        ];
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addApplicationPlugins(Container $container)
    {
        $container->set(static::PLUGIN_APPLICATIONS, function (Container $container) {
            return $this->getApplicationPlugins();
        });

        return $container;
    }

    /**
     * @return ApplicationPluginInterface[]
     */
    protected function getApplicationPlugins()
    {
        //@todo wiring should happen in the project. Also most plugins are probably not necessary
        return [
            new HttpApplicationPlugin(),
            new SessionApplicationPlugin(),
            new EventDispatcherApplicationPlugin(),
            new GlueApplicationApplicationPlugin(),
            new RouterApplicationPlugin(),
        ];
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addRequestMatcherPlugin(Container $container)
    {
        $container->set(static::PLUGIN_REQUEST_MATCHER, function () {
            return new RouteRequestMatcherPlugin($this->getResourceRoutePlugins());
        });

        return $container;
    }
}
