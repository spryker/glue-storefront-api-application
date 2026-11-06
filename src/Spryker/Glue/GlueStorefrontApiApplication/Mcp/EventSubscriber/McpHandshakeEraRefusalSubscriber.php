<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\GlueStorefrontApiApplication\Mcp\EventSubscriber;

use Mcp\Schema\Enum\ProtocolVersion;
use Mcp\Schema\JsonRpc\Error;
use Mcp\Server\Wire\InboundClassifier;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Refuses handshake-era MCP requests (`initialize`, session requests, `GET` and `DELETE`) on the MCP paths
 * with the JSON-RPC error "Unsupported protocol version". `symfony/mcp-bundle` serves the handshake era
 * also when `protocol_versions` lists only modern revisions. A `POST` body that is not a JSON object or array
 * gets the JSON-RPC parse error: the handshake leg of the transport would answer it with "Unsupported protocol
 * version" when the request has the `MCP-Protocol-Version: 2026-07-28` header.
 */
class McpHandshakeEraRefusalSubscriber implements EventSubscriberInterface
{
    protected const string MESSAGE_KEY_ID = 'id';

    protected const string MESSAGE_KEY_PARAMS = 'params';

    protected const string MESSAGE_KEY_PROTOCOL_VERSION = 'protocolVersion';

    protected const string MESSAGE_PARSE_ERROR = 'Parse error: the request body is not a JSON object or array.';

    /**
     * @param array<string> $mcpPaths
     * @param list<string> $supportedProtocolVersions
     */
    public function __construct(
        protected array $mcpPaths,
        protected array $supportedProtocolVersions,
        protected InboundClassifier $inboundClassifier = new InboundClassifier(),
    ) {
    }

    /**
     * @return array<string, array{string, int}>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 16],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$event->isMainRequest() || $request->isMethod(Request::METHOD_OPTIONS) || !in_array($request->getPathInfo(), $this->mcpPaths, true)) {
            return;
        }

        if ($this->hasUnreadableBody($request)) {
            $event->setResponse($this->createErrorResponse(Error::forParseError(static::MESSAGE_PARSE_ERROR)));

            return;
        }

        $protocolVersionHeader = (string)$request->headers->get(InboundClassifier::PROTOCOL_VERSION_HEADER);
        $eraClassification = $this->inboundClassifier->classify(
            $request->getMethod(),
            $request->getContent(),
            [InboundClassifier::PROTOCOL_VERSION_HEADER => $protocolVersionHeader],
        );

        if ($eraClassification->modern || $eraClassification->isRejected()) {
            return;
        }

        $message = $this->decodeMessage($request);
        $error = Error::forUnsupportedProtocolVersion(
            $this->resolveRequestedProtocolVersion($message, $eraClassification->claimedVersion, $protocolVersionHeader),
            array_map(ProtocolVersion::from(...), $this->supportedProtocolVersions),
            $this->findJsonRpcId($message),
        );

        $event->setResponse($this->createErrorResponse($error));
    }

    protected function createErrorResponse(Error $error): JsonResponse
    {
        return new JsonResponse($error->jsonSerialize(), Response::HTTP_BAD_REQUEST);
    }

    protected function hasUnreadableBody(Request $request): bool
    {
        return $request->isMethod(Request::METHOD_POST) && !is_array(json_decode($request->getContent(), true));
    }

    /**
     * @return array<mixed>
     */
    protected function decodeMessage(Request $request): array
    {
        $message = json_decode($request->getContent(), true);

        return is_array($message) ? $message : [];
    }

    /**
     * @param array<mixed> $message
     */
    protected function resolveRequestedProtocolVersion(array $message, ?string $claimedVersion, string $protocolVersionHeader): string
    {
        $initializeProtocolVersion = $message[static::MESSAGE_KEY_PARAMS][static::MESSAGE_KEY_PROTOCOL_VERSION] ?? null;

        if ($claimedVersion !== null) {
            return $claimedVersion;
        }

        if ($protocolVersionHeader !== '') {
            return $protocolVersionHeader;
        }

        return is_string($initializeProtocolVersion) ? $initializeProtocolVersion : ProtocolVersion::DEFAULT_HEADER_VERSION->value;
    }

    /**
     * @param array<mixed> $message
     */
    protected function findJsonRpcId(array $message): string|int|null
    {
        $id = $message[static::MESSAGE_KEY_ID] ?? null;

        return is_string($id) || is_int($id) ? $id : null;
    }
}
