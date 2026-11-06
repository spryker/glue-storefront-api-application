<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Glue\GlueStorefrontApiApplication\Mcp\EventSubscriber;

use Codeception\Test\Unit;
use Mcp\Schema\JsonRpc\Error;
use Spryker\Glue\GlueStorefrontApiApplication\Mcp\EventSubscriber\McpHandshakeEraRefusalSubscriber;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueStorefrontApiApplication
 * @group Mcp
 * @group EventSubscriber
 * @group McpHandshakeEraRefusalSubscriberTest
 * Add your own group annotations below this line
 */
class McpHandshakeEraRefusalSubscriberTest extends Unit
{
    protected const string MCP_PATH = '/mcp';

    protected const string MODERN_PROTOCOL_VERSION = '2026-07-28';

    public function testGivenAnInitializeRequestWhenItReachesTheMcpPathThenItIsRefusedWithTheSupportedProtocolVersions(): void
    {
        // Arrange
        $request = $this->createPostRequest(static::MCP_PATH, [
            'jsonrpc' => '2.0',
            'id' => 7,
            'method' => 'initialize',
            'params' => ['protocolVersion' => '2025-11-25', 'capabilities' => [], 'clientInfo' => ['name' => 'client', 'version' => '1']],
        ]);

        // Act
        $response = $this->dispatch($request);

        // Assert
        $this->assertNotNull($response);
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertSame([
            'jsonrpc' => '2.0',
            'id' => 7,
            'error' => [
                'code' => Error::UNSUPPORTED_PROTOCOL_VERSION,
                'message' => 'Unsupported protocol version',
                'data' => ['requested' => '2025-11-25', 'supported' => [static::MODERN_PROTOCOL_VERSION]],
            ],
        ], json_decode((string)$response->getContent(), true));
    }

    public function testGivenASessionRequestWithAHandshakeProtocolHeaderWhenItReachesTheMcpPathThenItIsRefused(): void
    {
        // Arrange
        $request = $this->createPostRequest(static::MCP_PATH, ['jsonrpc' => '2.0', 'id' => 'a', 'method' => 'tools/list', 'params' => []]);
        $request->headers->set('MCP-Protocol-Version', '2025-11-25');
        $request->headers->set('Mcp-Session-Id', 'session');

        // Act
        $response = $this->dispatch($request);

        // Assert
        $this->assertNotNull($response);
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertSame('2025-11-25', json_decode((string)$response->getContent(), true)['error']['data']['requested']);
    }

    public function testGivenAGetRequestWhenItReachesTheMcpPathThenItIsRefused(): void
    {
        // Arrange
        $request = Request::create(static::MCP_PATH, Request::METHOD_GET);

        // Act
        $response = $this->dispatch($request);

        // Assert
        $this->assertNotNull($response);
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testGivenAModernRequestWhenItReachesTheMcpPathThenItPassesThrough(): void
    {
        // Arrange
        $request = $this->createPostRequest(static::MCP_PATH, [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'tools/list',
            'params' => ['_meta' => ['io.modelcontextprotocol/protocolVersion' => static::MODERN_PROTOCOL_VERSION]],
        ]);
        $request->headers->set('MCP-Protocol-Version', static::MODERN_PROTOCOL_VERSION);

        // Act
        $response = $this->dispatch($request);

        // Assert
        $this->assertNull($response);
    }

    /**
     * @dataProvider provideUnreadableBodies
     */
    public function testGivenAPostRequestWithAnUnreadableBodyWhenItReachesTheMcpPathThenItGetsTheParseError(string $body): void
    {
        // Arrange
        $request = Request::create(static::MCP_PATH, Request::METHOD_POST, [], [], [], ['CONTENT_TYPE' => 'application/json'], $body);
        $request->headers->set('MCP-Protocol-Version', static::MODERN_PROTOCOL_VERSION);

        // Act
        $response = $this->dispatch($request);

        // Assert
        $this->assertNotNull($response);
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertSame(Error::PARSE_ERROR, json_decode((string)$response->getContent(), true)['error']['code']);
    }

    /**
     * @return array<string, array<string>>
     */
    public function provideUnreadableBodies(): array
    {
        return [
            'broken JSON' => ['{"jsonrpc":"2.0","id":1,'],
            'JSON scalar' => ['"tools/list"'],
            'empty body' => [''],
        ];
    }

    public function testGivenAPreflightRequestWhenItReachesTheMcpPathThenItPassesThrough(): void
    {
        // Arrange
        $request = Request::create(static::MCP_PATH, Request::METHOD_OPTIONS);

        // Act
        $response = $this->dispatch($request);

        // Assert
        $this->assertNull($response);
    }

    public function testGivenAnInitializeRequestWhenItReachesAnotherPathThenItPassesThrough(): void
    {
        // Arrange
        $request = $this->createPostRequest('/health-check', ['jsonrpc' => '2.0', 'id' => 1, 'method' => 'initialize', 'params' => []]);

        // Act
        $response = $this->dispatch($request);

        // Assert
        $this->assertNull($response);
    }

    /**
     * @param array<string, mixed> $message
     */
    protected function createPostRequest(string $path, array $message): Request
    {
        return Request::create($path, Request::METHOD_POST, [], [], [], ['CONTENT_TYPE' => 'application/json'], (string)json_encode($message));
    }

    protected function dispatch(Request $request): ?Response
    {
        $event = new RequestEvent($this->createMock(HttpKernelInterface::class), $request, HttpKernelInterface::MAIN_REQUEST);

        (new McpHandshakeEraRefusalSubscriber([static::MCP_PATH], [static::MODERN_PROTOCOL_VERSION]))->onKernelRequest($event);

        return $event->getResponse();
    }
}
