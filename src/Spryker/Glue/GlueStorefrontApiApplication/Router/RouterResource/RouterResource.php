<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication\Router\RouterResource;

use Spryker\Glue\GlueStorefrontApiApplication\Router\Route\RouteCollection;

class RouterResource implements RouterResourceInterface
{
    /**
     * @var \Spryker\Glue\GlueStorefrontApiApplication\Router\Route\RouteCollection
     */
    protected RouteCollection $routeCollection;

    /**
     * @var array<\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface>
     */
    protected $routeProvider = [];

    /**
     * @param \Spryker\Glue\GlueStorefrontApiApplication\Router\Route\RouteCollection $routeCollection
     * @param array<\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface> $routeProvider
     */
    public function __construct(RouteCollection $routeCollection, array $routeProvider)
    {
        $this->routeCollection = $routeCollection;
        $this->routeProvider = $routeProvider;
    }

    /**
     * @return \Spryker\Glue\GlueStorefrontApiApplication\Router\Route\RouteCollection
     */
    public function __invoke(): RouteCollection
    {
        foreach ($this->routeProvider as $routeProviderPlugin) {
            $this->routeCollection = $routeProviderPlugin->addRoutes($this->routeCollection);
        }

        return $this->routeCollection;
    }
}
