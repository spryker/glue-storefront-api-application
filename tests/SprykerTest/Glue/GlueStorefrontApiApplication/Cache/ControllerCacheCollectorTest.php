<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueStorefrontApiApplication\Cache;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Cache\ControllerCacheCollector;
use SprykerTest\Glue\GlueStorefrontApiApplication\Stub\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueStorefrontApiApplication
 * @group Cache
 * @group ControllerCacheCollectorTest
 * Add your own group annotations below this line
 */
class ControllerCacheCollectorTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_RESOURCE = 'fake';

    /**
     * @var string
     */
    protected const FAKE_CUSTOM_PATH = '/custom-fake';

    /**
     * @var string
     */
    protected const FAKE_COLLECTION = 'fakeCollection';

    /**
     * @uses \Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueApplication\ApplicationIdentifierRequestBuilderPlugin::GLUE_STOREFRONT_API_APPLICATION
     *
     * @var string
     */
    protected const GLUE_STOREFRONT_API_APPLICATION = 'GLUE_STOREFRONT_API_APPLICATION';

    /**
     * @return void
     */
    public function testCollectResourceRoutesShouldMapApiControllerConfigurationTransfersData(): void
    {
        //Arrange
        $resourceMock = $this->createResourceMock();

        //Act
        $controllerCacheCollector = new ControllerCacheCollector([$resourceMock], []);
        $apiControllerConfigurationTransfersData = $controllerCacheCollector->collect();

        //Assert
        $this->assertNotEmpty($apiControllerConfigurationTransfersData);
        $this->assertSame(1, count($apiControllerConfigurationTransfersData));
        $key = sprintf(
            '%s:%s:%sAction',
            ResourceController::class,
            static::FAKE_RESOURCE,
            GlueResourceMethodCollectionTransfer::GET_COLLECTION,
        );
        $this->assertArrayHasKey($key, $apiControllerConfigurationTransfersData[static::GLUE_STOREFRONT_API_APPLICATION]);
    }

    /**
     * @return void
     */
    public function testCollectCustomRoutesShouldMapApiControllerConfigurationTransfersData(): void
    {
        //Arrange
        $resourceMock = $this->createResourceMock();

        $routeProviderPluginMock = $this->createMock(RouteProviderPluginInterface::class);
        $routeProviderPluginMock->expects($this->once())
            ->method('addRoutes')
            ->willReturn($this->createFakeRouteCollection());

        //Act
        $controllerCacheCollector = new ControllerCacheCollector([$resourceMock], [$routeProviderPluginMock]);
        $apiControllerConfigurationTransfersData = $controllerCacheCollector->collect();

        //Assert
        $this->assertNotEmpty($apiControllerConfigurationTransfersData);
        $this->assertSame(1, count($apiControllerConfigurationTransfersData));
        $key = sprintf(
            '%s:%s:%sAction',
            ResourceController::class,
            static::FAKE_RESOURCE,
            GlueResourceMethodCollectionTransfer::GET_COLLECTION,
        );
        $this->assertArrayHasKey($key, $apiControllerConfigurationTransfersData[static::GLUE_STOREFRONT_API_APPLICATION]);
    }

    /**
     * @return \Symfony\Component\Routing\RouteCollection
     */
    protected function createFakeRouteCollection(): RouteCollection
    {
        $defaults = array_filter([
            '_resourceName' => static::FAKE_RESOURCE,
            '_method' => 'getCollection',
        ]);

        $collectionRoute = (new Route(static::FAKE_CUSTOM_PATH, $defaults))->setMethods(Request::METHOD_GET);

        $routeCollection = new RouteCollection();
        $routeCollection->add(static::FAKE_COLLECTION, $collectionRoute);

        return $routeCollection;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface
     */
    protected function createResourceMock(): ResourceInterface
    {
        $resourceMock = $this->createMock(ResourceInterface::class);
        $resourceMock->expects($this->once())
            ->method('getDeclaredMethods')
            ->willReturn(
                (new GlueResourceMethodCollectionTransfer())
                    ->setGetCollection(new GlueResourceMethodConfigurationTransfer()),
            );
        $resourceMock->expects($this->exactly(2))
            ->method('getType')
            ->willReturn(static::FAKE_RESOURCE);
        $resourceMock->expects($this->any())
            ->method('getController')
            ->willReturn(ResourceController::class);

        return $resourceMock;
    }
}
