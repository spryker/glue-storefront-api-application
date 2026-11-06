<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\GlueStorefrontApiApplication\Discovery;

/**
 * Builds one server card of the MCP Server Card extension for each MCP server of the `symfony/mcp-bundle` configuration.
 * The card lists no tools and no capabilities: clients read them from the live server.
 */
class McpServerCardBuilder implements McpServerCardBuilderInterface
{
    public const string SCHEMA_URL = 'https://static.modelcontextprotocol.io/schemas/v1/server-card.schema.json';

    public const string REMOTE_TYPE_STREAMABLE_HTTP = 'streamable-http';

    public const string PATH_SERVER_CARD = '/.well-known/mcp/server-card.json';

    public const string PATH_SERVER_CARD_OF_SERVER = '/.well-known/mcp/%s/server-card.json';

    public const string MCP_ENDPOINT_URL = 'mcpEndpointUrl';

    public const string SERVER_CARD_URL = 'serverCardUrl';

    /**
     * The defaults of `symfony/mcp-bundle` for the server options that the card shows.
     */
    protected const string DEFAULT_SERVER_VERSION = '0.0.1';

    protected const string DEFAULT_MCP_ENDPOINT_PATH = '/mcp/%s';

    /**
     * @param array<string, array<string, mixed>> $mcpServers The `servers` configuration of `symfony/mcp-bundle`, keyed by server key.
     */
    public function __construct(
        protected array $mcpServers,
        protected string $apiBaseUrl,
    ) {
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string, mixed>|null
     */
    public function buildServerCard(?string $serverKey = null): ?array
    {
        $httpServers = $this->getHttpServers();
        $serverKey ??= array_key_first($httpServers);

        if ($serverKey === null || !isset($httpServers[$serverKey])) {
            return null;
        }

        $server = $httpServers[$serverKey];
        $remote = [
            'type' => static::REMOTE_TYPE_STREAMABLE_HTTP,
            'url' => $this->buildMcpEndpointUrl($serverKey, $server),
        ];

        if (($server['protocol_versions'] ?? []) !== []) {
            $remote['supportedProtocolVersions'] = array_values($server['protocol_versions']);
        }

        return array_filter([
            '$schema' => static::SCHEMA_URL,
            'name' => $server['name'] ?? $serverKey,
            'version' => $server['version'] ?? static::DEFAULT_SERVER_VERSION,
            'description' => $server['description'] ?? null,
            'remotes' => [$remote],
        ], static fn (mixed $value): bool => $value !== null);
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string, array<string, string>>
     */
    public function getServerUrls(): array
    {
        $serverUrls = [];
        $defaultServerKey = null;

        foreach ($this->getHttpServers() as $serverKey => $server) {
            $defaultServerKey ??= $serverKey;
            $serverCardPath = $serverKey === $defaultServerKey ? static::PATH_SERVER_CARD : sprintf(static::PATH_SERVER_CARD_OF_SERVER, $serverKey);

            $serverUrls[$serverKey] = [
                static::MCP_ENDPOINT_URL => $this->buildMcpEndpointUrl($serverKey, $server),
                static::SERVER_CARD_URL => $this->apiBaseUrl . $serverCardPath,
            ];
        }

        return $serverUrls;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function getHttpServers(): array
    {
        return array_filter(
            $this->mcpServers,
            static fn (array $server): bool => ($server['transports']['http'] ?? true) === true,
        );
    }

    /**
     * @param array<string, mixed> $server
     */
    protected function buildMcpEndpointUrl(string $serverKey, array $server): string
    {
        return $this->apiBaseUrl . ($server['http']['path'] ?? sprintf(static::DEFAULT_MCP_ENDPOINT_PATH, $serverKey));
    }
}
