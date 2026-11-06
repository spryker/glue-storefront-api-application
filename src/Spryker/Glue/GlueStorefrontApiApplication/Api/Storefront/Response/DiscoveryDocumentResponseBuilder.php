<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\GlueStorefrontApiApplication\Api\Storefront\Response;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The discovery documents carry no caller data, so every response is public, cacheable for one hour and open to any origin.
 */
class DiscoveryDocumentResponseBuilder implements DiscoveryDocumentResponseBuilderInterface
{
    protected const string HEADER_CONTENT_TYPE = 'Content-Type';

    protected const string CONTENT_TYPE_TEXT = 'text/plain; charset=UTF-8';

    protected const string ETAG_HASH_ALGORITHM = 'xxh128';

    protected const int MAX_AGE_SECONDS = 3600;

    /**
     * @var array<string, string>
     */
    protected const array CORS_HEADERS = [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, HEAD',
        'Access-Control-Allow-Headers' => 'Content-Type, If-None-Match',
        'Access-Control-Expose-Headers' => 'ETag',
    ];

    /**
     * {@inheritDoc}
     *
     * @param array<string, mixed> $document
     */
    public function createDocumentResponse(Request $request, array $document, string $mediaType): Response
    {
        $content = json_encode($document, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);

        $response = (new Response($content, Response::HTTP_OK, static::CORS_HEADERS))
            ->setPublic()
            ->setMaxAge(static::MAX_AGE_SECONDS)
            ->setEtag(hash(static::ETAG_HASH_ALGORITHM, $content));
        $response->headers->set(static::HEADER_CONTENT_TYPE, $mediaType);
        $response->isNotModified($request);

        return $response;
    }

    public function createNotFoundResponse(): Response
    {
        return new Response('', Response::HTTP_NOT_FOUND, [static::HEADER_CONTENT_TYPE => static::CONTENT_TYPE_TEXT]);
    }
}
