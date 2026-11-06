<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\GlueStorefrontApiApplication\Discovery;

interface McpServerCardBuilderInterface
{
    /**
     * Specification:
     * - Builds the server card of the MCP server with the given configuration key.
     * - Builds the server card of the first MCP server with an HTTP transport when the key is null.
     * - Returns null when no such MCP server is configured.
     *
     * @return array<string, mixed>|null
     */
    public function buildServerCard(?string $serverKey = null): ?array;

    /**
     * Specification:
     * - Returns the MCP endpoint URL (key `mcpEndpointUrl`) and the server card URL (key `serverCardUrl`) of each MCP server with an HTTP transport, keyed by server key.
     * - The first server has the default server card URL, the other servers have a server card URL with their configuration key.
     *
     * @return array<string, array<string, string>>
     */
    public function getServerUrls(): array;
}
