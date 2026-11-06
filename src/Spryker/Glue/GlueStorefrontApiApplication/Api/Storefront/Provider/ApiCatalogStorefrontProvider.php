<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\GlueStorefrontApiApplication\Api\Storefront\Provider;

use Spryker\ApiPlatform\Request\RequestAttribute;
use Spryker\ApiPlatform\State\Provider\AbstractStorefrontProvider;
use Spryker\Glue\GlueStorefrontApiApplication\Api\Storefront\Response\DiscoveryDocumentResponseBuilderInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Discovery\ApiCatalogBuilderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\WebLink\GenericLinkProvider;
use Symfony\Component\WebLink\Link;

/**
 * Serves the RFC 9727 API catalog of the Storefront API host. Without a configured MCP server the catalog is not found.
 */
class ApiCatalogStorefrontProvider extends AbstractStorefrontProvider
{
    protected const string MEDIA_TYPE_API_CATALOG = 'application/linkset+json; profile="https://www.rfc-editor.org/info/rfc9727"';

    protected const string LINK_RELATION_API_CATALOG = 'api-catalog';

    public function __construct(
        protected DiscoveryDocumentResponseBuilderInterface $discoveryDocumentResponseBuilder,
        protected ?ApiCatalogBuilderInterface $apiCatalogBuilder = null,
    ) {
    }

    protected function provideItem(): Response
    {
        $apiCatalogBuilder = $this->apiCatalogBuilder;

        if ($apiCatalogBuilder === null) {
            return $this->discoveryDocumentResponseBuilder->createNotFoundResponse();
        }

        $this->addApiCatalogLink($apiCatalogBuilder->getApiCatalogUrl());

        return $this->discoveryDocumentResponseBuilder->createDocumentResponse(
            $this->getRequest(),
            $apiCatalogBuilder->buildApiCatalog(),
            static::MEDIA_TYPE_API_CATALOG,
        );
    }

    protected function addApiCatalogLink(string $apiCatalogUrl): void
    {
        $request = $this->getRequest();
        $linkProvider = $request->attributes->get(RequestAttribute::API_PLATFORM_LINKS) ?? new GenericLinkProvider();
        $apiCatalogLink = new Link(static::LINK_RELATION_API_CATALOG, $apiCatalogUrl);

        $request->attributes->set(RequestAttribute::API_PLATFORM_LINKS, $linkProvider->withLink($apiCatalogLink));
    }
}
