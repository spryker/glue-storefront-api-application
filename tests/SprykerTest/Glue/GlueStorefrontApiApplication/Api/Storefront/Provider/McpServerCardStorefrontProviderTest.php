<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Glue\GlueStorefrontApiApplication\Api\Storefront\Provider;

use ApiPlatform\Metadata\Get;
use Codeception\Test\Unit;
use Spryker\Glue\GlueStorefrontApiApplication\Api\Storefront\Provider\McpServerCardStorefrontProvider;
use Spryker\Glue\GlueStorefrontApiApplication\Api\Storefront\Response\DiscoveryDocumentResponseBuilder;
use Spryker\Glue\GlueStorefrontApiApplication\Discovery\ApiCatalogBuilder;
use Spryker\Glue\GlueStorefrontApiApplication\Discovery\McpServerCardBuilder;
use Spryker\Glue\GlueStorefrontApiApplication\Discovery\McpServerCardBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueStorefrontApiApplication
 * @group Api
 * @group Storefront
 * @group Provider
 * @group McpServerCardStorefrontProviderTest
 * Add your own group annotations below this line
 */
class McpServerCardStorefrontProviderTest extends Unit
{
    protected const string PATH_SERVER_CARD = '/.well-known/mcp/server-card.json';

    /**
     * @var array<string, array<string, mixed>>
     */
    protected const array MCP_SERVERS = [
        'storefront' => ['name' => 'com.example/storefront', 'http' => ['path' => '/mcp']],
        'catalog' => ['name' => 'com.example/catalog'],
    ];

    public function testGivenNoServerKeyWhenProvidingThenReturnsThePublicCardOfTheDefaultServer(): void
    {
        // Arrange
        $provider = $this->createProvider(new McpServerCardBuilder(static::MCP_SERVERS, 'https://glue.example.com'));

        // Act
        $response = $provider->provide(new Get(), [], ['request' => Request::create(static::PATH_SERVER_CARD)]);

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame(ApiCatalogBuilder::MEDIA_TYPE_MCP_SERVER_CARD, $response->headers->get('Content-Type'));
        $this->assertSame('max-age=3600, public', $response->headers->get('Cache-Control'));
        $this->assertSame('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertSame('ETag', $response->headers->get('Access-Control-Expose-Headers'));
        $this->assertNotNull($response->getEtag());
        $this->assertSame('com.example/storefront', json_decode((string)$response->getContent(), true, 512, JSON_THROW_ON_ERROR)['name']);
    }

    public function testGivenServerKeyWhenProvidingThenReturnsTheCardOfThatServer(): void
    {
        // Arrange
        $provider = $this->createProvider(new McpServerCardBuilder(static::MCP_SERVERS, 'https://glue.example.com'));

        // Act
        $response = $provider->provide(new Get(), ['serverKey' => 'catalog'], ['request' => Request::create('/.well-known/mcp/catalog/server-card.json')]);

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame('com.example/catalog', json_decode((string)$response->getContent(), true, 512, JSON_THROW_ON_ERROR)['name']);
    }

    public function testGivenMatchingEtagWhenProvidingThenReturnsNotModifiedWithoutBody(): void
    {
        // Arrange
        $provider = $this->createProvider(new McpServerCardBuilder(static::MCP_SERVERS, 'https://glue.example.com'));
        $firstResponse = $provider->provide(new Get(), [], ['request' => Request::create(static::PATH_SERVER_CARD)]);
        $this->assertInstanceOf(Response::class, $firstResponse);
        $etag = $firstResponse->getEtag();
        $request = Request::create(static::PATH_SERVER_CARD, server: ['HTTP_IF_NONE_MATCH' => $etag]);

        // Act
        $response = $this->createProvider(new McpServerCardBuilder(static::MCP_SERVERS, 'https://glue.example.com'))->provide(new Get(), [], ['request' => $request]);

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(Response::HTTP_NOT_MODIFIED, $response->getStatusCode());
        $this->assertSame('', $response->getContent());
    }

    /**
     * @dataProvider provideMissingServerCards
     *
     * @param array<string, string> $uriVariables
     */
    public function testGivenNoMatchingServerWhenProvidingThenReturnsNotFound(?McpServerCardBuilderInterface $mcpServerCardBuilder, array $uriVariables): void
    {
        // Arrange
        $provider = $this->createProvider($mcpServerCardBuilder);

        // Act
        $response = $provider->provide(new Get(), $uriVariables, ['request' => Request::create(static::PATH_SERVER_CARD)]);

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @return array<string, array<mixed>>
     */
    public function provideMissingServerCards(): array
    {
        return [
            'MCP is not configured' => [null, []],
            'unknown server key' => [new McpServerCardBuilder(static::MCP_SERVERS, 'https://glue.example.com'), ['serverKey' => 'unknown']],
        ];
    }

    protected function createProvider(?McpServerCardBuilderInterface $mcpServerCardBuilder): McpServerCardStorefrontProvider
    {
        return new McpServerCardStorefrontProvider(new DiscoveryDocumentResponseBuilder(), $mcpServerCardBuilder);
    }
}
