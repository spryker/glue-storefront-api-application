<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\GlueStorefrontApiApplication\Discovery;

class ApiCatalogBuilder implements ApiCatalogBuilderInterface
{
    public const string PATH_API_CATALOG = '/.well-known/api-catalog';

    public const string MEDIA_TYPE_MCP_SERVER_CARD = 'application/mcp-server-card+json';

    protected const string PATH_API_ROOT = '/';

    protected const string PATH_OPEN_API_DESCRIPTION = '/docs.jsonopenapi';

    protected const string PATH_API_DOCUMENTATION = '/docs';

    protected const string MEDIA_TYPE_OPEN_API = 'application/vnd.openapi+json';

    protected const string MEDIA_TYPE_HTML = 'text/html';

    protected const string RELATION_SERVICE_META = 'service-meta';

    protected const string RELATION_SERVICE_DESCRIPTION = 'service-desc';

    protected const string RELATION_SERVICE_DOCUMENTATION = 'service-doc';

    public function __construct(
        protected McpServerCardBuilderInterface $mcpServerCardBuilder,
        protected string $apiBaseUrl,
        protected bool $isApiDocumentationEnabled,
    ) {
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string, list<array<string, mixed>>>
     */
    public function buildApiCatalog(): array
    {
        $linkset = [];

        foreach ($this->mcpServerCardBuilder->getServerUrls() as $serverUrls) {
            $linkset[] = [
                'anchor' => $serverUrls[McpServerCardBuilder::MCP_ENDPOINT_URL],
                static::RELATION_SERVICE_META => [
                    $this->createLink($serverUrls[McpServerCardBuilder::SERVER_CARD_URL], static::MEDIA_TYPE_MCP_SERVER_CARD),
                ],
            ];
        }

        if ($this->isApiDocumentationEnabled) {
            $linkset[] = [
                'anchor' => $this->apiBaseUrl . static::PATH_API_ROOT,
                static::RELATION_SERVICE_DESCRIPTION => [
                    $this->createLink($this->apiBaseUrl . static::PATH_OPEN_API_DESCRIPTION, static::MEDIA_TYPE_OPEN_API),
                ],
                static::RELATION_SERVICE_DOCUMENTATION => [
                    $this->createLink($this->apiBaseUrl . static::PATH_API_DOCUMENTATION, static::MEDIA_TYPE_HTML),
                ],
            ];
        }

        return ['linkset' => $linkset];
    }

    public function getApiCatalogUrl(): string
    {
        return $this->apiBaseUrl . static::PATH_API_CATALOG;
    }

    /**
     * @return array<string, string>
     */
    protected function createLink(string $href, string $mediaType): array
    {
        return ['href' => $href, 'type' => $mediaType];
    }
}
