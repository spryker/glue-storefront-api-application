<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Glue\GlueStorefrontApiApplication\Discovery;

use Codeception\Test\Unit;
use Spryker\Glue\GlueStorefrontApiApplication\Discovery\ApiCatalogBuilder;
use Spryker\Glue\GlueStorefrontApiApplication\Discovery\McpServerCardBuilder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueStorefrontApiApplication
 * @group Discovery
 * @group ApiCatalogBuilderTest
 * Add your own group annotations below this line
 */
class ApiCatalogBuilderTest extends Unit
{
    protected const string API_BASE_URL = 'https://glue-storefront.example.com';

    /**
     * @var array<string, array<string, mixed>>
     */
    protected const array MCP_SERVERS = [
        'storefront' => ['http' => ['path' => '/mcp']],
        'catalog' => [],
    ];

    public function testGivenApiDocumentationEnabledWhenBuildingApiCatalogThenEveryMcpServerAndTheOpenApiDocumentAreListed(): void
    {
        // Arrange
        $apiCatalogBuilder = $this->createApiCatalogBuilder(true);

        // Act
        $apiCatalog = $apiCatalogBuilder->buildApiCatalog();

        // Assert
        $this->assertSame([
            'linkset' => [
                [
                    'anchor' => static::API_BASE_URL . '/mcp',
                    'service-meta' => [['href' => static::API_BASE_URL . '/.well-known/mcp/server-card.json', 'type' => ApiCatalogBuilder::MEDIA_TYPE_MCP_SERVER_CARD]],
                ],
                [
                    'anchor' => static::API_BASE_URL . '/mcp/catalog',
                    'service-meta' => [['href' => static::API_BASE_URL . '/.well-known/mcp/catalog/server-card.json', 'type' => ApiCatalogBuilder::MEDIA_TYPE_MCP_SERVER_CARD]],
                ],
                [
                    'anchor' => static::API_BASE_URL . '/',
                    'service-desc' => [['href' => static::API_BASE_URL . '/docs.jsonopenapi', 'type' => 'application/vnd.openapi+json']],
                    'service-doc' => [['href' => static::API_BASE_URL . '/docs', 'type' => 'text/html']],
                ],
            ],
        ], $apiCatalog);
    }

    public function testGivenApiDocumentationDisabledWhenBuildingApiCatalogThenOnlyTheMcpServersAreListed(): void
    {
        // Arrange
        $apiCatalogBuilder = $this->createApiCatalogBuilder(false);

        // Act
        $apiCatalog = $apiCatalogBuilder->buildApiCatalog();

        // Assert
        $this->assertSame([static::API_BASE_URL . '/mcp', static::API_BASE_URL . '/mcp/catalog'], array_column($apiCatalog['linkset'], 'anchor'));
    }

    public function testGivenApiBaseUrlWhenGettingApiCatalogUrlThenReturnsTheWellKnownUrl(): void
    {
        // Arrange
        $apiCatalogBuilder = $this->createApiCatalogBuilder(true);

        // Act
        $apiCatalogUrl = $apiCatalogBuilder->getApiCatalogUrl();

        // Assert
        $this->assertSame(static::API_BASE_URL . '/.well-known/api-catalog', $apiCatalogUrl);
    }

    protected function createApiCatalogBuilder(bool $isApiDocumentationEnabled): ApiCatalogBuilder
    {
        return new ApiCatalogBuilder(
            new McpServerCardBuilder(static::MCP_SERVERS, static::API_BASE_URL),
            static::API_BASE_URL,
            $isApiDocumentationEnabled,
        );
    }
}
