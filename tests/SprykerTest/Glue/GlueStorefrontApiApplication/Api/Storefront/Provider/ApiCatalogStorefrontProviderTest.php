<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Glue\GlueStorefrontApiApplication\Api\Storefront\Provider;

use ApiPlatform\Metadata\Get;
use Codeception\Test\Unit;
use Spryker\ApiPlatform\Request\RequestAttribute;
use Spryker\Glue\GlueStorefrontApiApplication\Api\Storefront\Provider\ApiCatalogStorefrontProvider;
use Spryker\Glue\GlueStorefrontApiApplication\Api\Storefront\Response\DiscoveryDocumentResponseBuilder;
use Spryker\Glue\GlueStorefrontApiApplication\Discovery\ApiCatalogBuilder;
use Spryker\Glue\GlueStorefrontApiApplication\Discovery\McpServerCardBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\WebLink\GenericLinkProvider;
use Symfony\Component\WebLink\Link;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueStorefrontApiApplication
 * @group Api
 * @group Storefront
 * @group Provider
 * @group ApiCatalogStorefrontProviderTest
 * Add your own group annotations below this line
 */
class ApiCatalogStorefrontProviderTest extends Unit
{
    protected const string API_BASE_URL = 'https://glue.example.com';

    protected const string PATH_API_CATALOG = '/.well-known/api-catalog';

    public function testGivenConfiguredMcpServerWhenProvidingThenReturnsTheLinksetAndAddsTheApiCatalogLink(): void
    {
        // Arrange
        $request = Request::create(static::PATH_API_CATALOG);
        $request->attributes->set(RequestAttribute::API_PLATFORM_LINKS, (new GenericLinkProvider())->withLink(new Link('describedby', static::API_BASE_URL . '/docs')));
        $provider = new ApiCatalogStorefrontProvider(
            new DiscoveryDocumentResponseBuilder(),
            new ApiCatalogBuilder(new McpServerCardBuilder(['storefront' => ['http' => ['path' => '/mcp']]], static::API_BASE_URL), static::API_BASE_URL, false),
        );

        // Act
        $response = $provider->provide(new Get(), [], ['request' => $request]);

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame('application/linkset+json; profile="https://www.rfc-editor.org/info/rfc9727"', $response->headers->get('Content-Type'));
        $this->assertSame([static::API_BASE_URL . '/mcp'], array_column(json_decode((string)$response->getContent(), true, 512, JSON_THROW_ON_ERROR)['linkset'], 'anchor'));
        $this->assertSame(
            [[['describedby'], static::API_BASE_URL . '/docs'], [['api-catalog'], static::API_BASE_URL . static::PATH_API_CATALOG]],
            array_map(
                static fn (Link $link): array => [$link->getRels(), $link->getHref()],
                $request->attributes->get(RequestAttribute::API_PLATFORM_LINKS)->getLinks(),
            ),
        );
    }

    public function testGivenNoMcpConfigurationWhenProvidingThenReturnsNotFound(): void
    {
        // Arrange
        $provider = new ApiCatalogStorefrontProvider(new DiscoveryDocumentResponseBuilder());

        // Act
        $response = $provider->provide(new Get(), [], ['request' => Request::create(static::PATH_API_CATALOG)]);

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}
