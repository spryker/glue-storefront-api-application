<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\GlueStorefrontApiApplication\Api\Storefront\Provider;

use Spryker\ApiPlatform\State\Provider\AbstractStorefrontProvider;
use Spryker\Glue\GlueStorefrontApiApplication\Api\Storefront\Response\DiscoveryDocumentResponseBuilderInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Discovery\ApiCatalogBuilder;
use Spryker\Glue\GlueStorefrontApiApplication\Discovery\McpServerCardBuilderInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Serves the server card of the MCP Server Card extension. Without a configured MCP server the card is not found.
 */
class McpServerCardStorefrontProvider extends AbstractStorefrontProvider
{
    protected const string URI_VARIABLE_SERVER_KEY = 'serverKey';

    public function __construct(
        protected DiscoveryDocumentResponseBuilderInterface $discoveryDocumentResponseBuilder,
        protected ?McpServerCardBuilderInterface $mcpServerCardBuilder = null,
    ) {
    }

    protected function provideItem(): Response
    {
        $serverKey = $this->findUriVariable(static::URI_VARIABLE_SERVER_KEY);
        $serverCard = $this->mcpServerCardBuilder?->buildServerCard($serverKey !== null ? (string)$serverKey : null);

        if ($serverCard === null) {
            return $this->discoveryDocumentResponseBuilder->createNotFoundResponse();
        }

        return $this->discoveryDocumentResponseBuilder->createDocumentResponse(
            $this->getRequest(),
            $serverCard,
            ApiCatalogBuilder::MEDIA_TYPE_MCP_SERVER_CARD,
        );
    }
}
