<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\GlueStorefrontApiApplication\Discovery;

interface ApiCatalogBuilderInterface
{
    /**
     * Specification:
     * - Builds the RFC 9727 API catalog of the Storefront API host as an RFC 9264 linkset.
     * - Adds one link context for each MCP server, with a link to its server card.
     * - Adds the OpenAPI links only when the application serves its API documentation.
     *
     * @return array<string, list<array<string, mixed>>>
     */
    public function buildApiCatalog(): array;

    /**
     * Specification:
     * - Returns the URL of the API catalog.
     */
    public function getApiCatalogUrl(): string;
}
