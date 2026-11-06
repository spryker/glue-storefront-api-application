<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Glue\GlueStorefrontApiApplication\Discovery;

use Codeception\Test\Unit;
use Spryker\Glue\GlueStorefrontApiApplication\Discovery\McpServerCardBuilder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueStorefrontApiApplication
 * @group Discovery
 * @group McpServerCardBuilderTest
 * Add your own group annotations below this line
 */
class McpServerCardBuilderTest extends Unit
{
    protected const string API_BASE_URL = 'https://glue-storefront.example.com';

    /**
     * @var array<string, array<string, mixed>>
     */
    protected const array MCP_SERVERS = [
        'storefront' => [
            'name' => 'com.example/storefront',
            'version' => '1.2.3',
            'description' => 'MCP server of the example Storefront API.',
            'protocol_versions' => ['2026-07-28'],
            'http' => ['path' => '/mcp'],
        ],
        'internal' => [
            'transports' => ['http' => false, 'stdio' => true],
        ],
        'catalog' => [],
    ];

    public function testGivenNoServerKeyWhenBuildingServerCardThenReturnsTheCardOfTheFirstHttpServer(): void
    {
        // Arrange
        $mcpServerCardBuilder = new McpServerCardBuilder(static::MCP_SERVERS, static::API_BASE_URL);

        // Act
        $serverCard = $mcpServerCardBuilder->buildServerCard();

        // Assert
        $this->assertSame([
            '$schema' => McpServerCardBuilder::SCHEMA_URL,
            'name' => 'com.example/storefront',
            'version' => '1.2.3',
            'description' => 'MCP server of the example Storefront API.',
            'remotes' => [
                [
                    'type' => McpServerCardBuilder::REMOTE_TYPE_STREAMABLE_HTTP,
                    'url' => static::API_BASE_URL . '/mcp',
                    'supportedProtocolVersions' => ['2026-07-28'],
                ],
            ],
        ], $serverCard);
    }

    public function testGivenServerWithoutOptionsWhenBuildingServerCardThenTheBundleDefaultsAreUsed(): void
    {
        // Arrange
        $mcpServerCardBuilder = new McpServerCardBuilder(static::MCP_SERVERS, static::API_BASE_URL);

        // Act
        $serverCard = $mcpServerCardBuilder->buildServerCard('catalog');

        // Assert
        $this->assertSame([
            '$schema' => McpServerCardBuilder::SCHEMA_URL,
            'name' => 'catalog',
            'version' => '0.0.1',
            'remotes' => [
                [
                    'type' => McpServerCardBuilder::REMOTE_TYPE_STREAMABLE_HTTP,
                    'url' => static::API_BASE_URL . '/mcp/catalog',
                ],
            ],
        ], $serverCard);
    }

    /**
     * @dataProvider provideServerKeysWithoutCard
     */
    public function testGivenServerKeyWithoutHttpServerWhenBuildingServerCardThenReturnsNull(string $serverKey): void
    {
        // Arrange
        $mcpServerCardBuilder = new McpServerCardBuilder(static::MCP_SERVERS, static::API_BASE_URL);

        // Act
        $serverCard = $mcpServerCardBuilder->buildServerCard($serverKey);

        // Assert
        $this->assertNull($serverCard);
    }

    /**
     * @return array<string, array<string>>
     */
    public function provideServerKeysWithoutCard(): array
    {
        return [
            'unknown server' => ['unknown'],
            'server without HTTP transport' => ['internal'],
        ];
    }

    public function testGivenNoServerWhenBuildingServerCardThenReturnsNull(): void
    {
        // Arrange
        $mcpServerCardBuilder = new McpServerCardBuilder([], static::API_BASE_URL);

        // Act
        $serverCard = $mcpServerCardBuilder->buildServerCard();

        // Assert
        $this->assertNull($serverCard);
    }

    public function testGivenTwoHttpServersWhenGettingServerUrlsThenOnlyTheFirstServerHasTheDefaultServerCardUrl(): void
    {
        // Arrange
        $mcpServerCardBuilder = new McpServerCardBuilder(static::MCP_SERVERS, static::API_BASE_URL);

        // Act
        $serverUrls = $mcpServerCardBuilder->getServerUrls();

        // Assert
        $this->assertSame([
            'storefront' => [
                McpServerCardBuilder::MCP_ENDPOINT_URL => static::API_BASE_URL . '/mcp',
                McpServerCardBuilder::SERVER_CARD_URL => static::API_BASE_URL . '/.well-known/mcp/server-card.json',
            ],
            'catalog' => [
                McpServerCardBuilder::MCP_ENDPOINT_URL => static::API_BASE_URL . '/mcp/catalog',
                McpServerCardBuilder::SERVER_CARD_URL => static::API_BASE_URL . '/.well-known/mcp/catalog/server-card.json',
            ],
        ], $serverUrls);
    }
}
