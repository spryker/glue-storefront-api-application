<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueStorefrontApiApplication\ResourceRouteBuilder;

use Codeception\Test\Unit;
use Spryker\Glue\GlueStorefrontApiApplication\ResourceRouteBuilder\ResourceRouteBuilder;
use SprykerTest\Glue\GlueStorefrontApiApplication\Stub\TestResourceEmptyMethodsRouteProviderPlugin;
use SprykerTest\Glue\GlueStorefrontApiApplication\Stub\TestResourceRouteProviderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueStorefrontApiApplication
 * @group ResourceRouteBuilder
 * @group ResourceRouteBuilderTest
 * Add your own group annotations below this line
 */
class ResourceRouteBuilderTest extends Unit
{
    /**
     * @var string
     */
    protected const RESOURCE_METHOD_GET = 'get';

    /**
     * @var string
     */
    protected const RESOURCE_METHOD_GET_COLLECTION = 'getCollection';

    /**
     * @var string
     */
    protected const RESOURCE_METHOD_POST = 'post';

    /**
     * @var string
     */
    protected const RESOURCE_METHOD_PATCH = 'patch';

    /**
     * @var string
     */
    protected const RESOURCE_METHOD_DELETE = 'delete';

    public function testBuilderReturnsNonEmptyRoutes(): void
    {
        //Arrange
        $resourceRouteBuilder = $this->createResourceRouteBuilder();
        $resourcePlugin = $this->createResourceRouterProviderPlugin();

        //Act
        $routes = $resourceRouteBuilder->buildRoutes($resourcePlugin);

        //Assert
        $this->assertNotEmpty($routes);
    }

    public function testBuilderReturnsEmptyRoutes(): void
    {
        //Arrange
        $resourceRouteBuilder = $this->createResourceRouteBuilder();
        $resourcePlugin = $this->createResourceEmptyMethodsRouterProviderPlugin();

        //Act
        $routes = $resourceRouteBuilder->buildRoutes($resourcePlugin);

        //Assert
        $this->assertEmpty($routes);
    }

    public function testGetMethodRouteIsGenerated(): void
    {
        //Arrange
        $resourceRouteBuilder = $this->createResourceRouteBuilder();
        $resourcePlugin = $this->createResourceRouterProviderPlugin();

        //Act
        $routes = $resourceRouteBuilder->buildRoutes($resourcePlugin);
        $methodKey = $this->getGeneratedMethodKey($resourcePlugin->getType(), static::RESOURCE_METHOD_GET);

        //Arrange
        $this->assertNotNull($routes[$methodKey]);
    }

    public function testGetCoolectionMethodRouteIsGenerated(): void
    {
        //Arrange
        $resourceRouteBuilder = $this->createResourceRouteBuilder();
        $resourcePlugin = $this->createResourceRouterProviderPlugin();

        //Act
        $routes = $resourceRouteBuilder->buildRoutes($resourcePlugin);
        $methodKey = $this->getGeneratedMethodKey($resourcePlugin->getType(), static::RESOURCE_METHOD_GET_COLLECTION);

        //Arrange
        $this->assertNotNull($routes[$methodKey]);
    }

    public function testPostMethodRouteIsGenerated(): void
    {
        //Arrange
        $resourceRouteBuilder = $this->createResourceRouteBuilder();
        $resourcePlugin = $this->createResourceRouterProviderPlugin();

        //Act
        $routes = $resourceRouteBuilder->buildRoutes($resourcePlugin);
        $methodKey = $this->getGeneratedMethodKey($resourcePlugin->getType(), static::RESOURCE_METHOD_POST);

        //Arrange
        $this->assertNotNull($routes[$methodKey]);
    }

    public function testPatchMethodRouteIsGenerated(): void
    {
        //Arrange
        $resourceRouteBuilder = $this->createResourceRouteBuilder();
        $resourcePlugin = $this->createResourceRouterProviderPlugin();

        //Act
        $routes = $resourceRouteBuilder->buildRoutes($resourcePlugin);
        $methodKey = $this->getGeneratedMethodKey($resourcePlugin->getType(), static::RESOURCE_METHOD_PATCH);

        //Arrange
        $this->assertNotNull($routes[$methodKey]);
    }

    public function testDeleteMethodRouteIsGenerated(): void
    {
        //Arrange
        $resourceRouteBuilder = $this->createResourceRouteBuilder();
        $resourcePlugin = $this->createResourceRouterProviderPlugin();

        //Act
        $routes = $resourceRouteBuilder->buildRoutes($resourcePlugin);
        $methodKey = $this->getGeneratedMethodKey($resourcePlugin->getType(), static::RESOURCE_METHOD_DELETE);

        //Arrange
        $this->assertNotNull($routes[$methodKey]);
    }

    protected function getGeneratedMethodKey(string $resourceType, string $method): string
    {
        return sprintf(
            '%s%s%s',
            $resourceType,
            'Resource',
            ucfirst($method),
        );
    }

    protected function createResourceRouterProviderPlugin(): TestResourceRouteProviderPlugin
    {
        return new TestResourceRouteProviderPlugin();
    }

    protected function createResourceEmptyMethodsRouterProviderPlugin(): TestResourceEmptyMethodsRouteProviderPlugin
    {
        return new TestResourceEmptyMethodsRouteProviderPlugin();
    }

    protected function createResourceRouteBuilder(): ResourceRouteBuilder
    {
        return new ResourceRouteBuilder();
    }
}
