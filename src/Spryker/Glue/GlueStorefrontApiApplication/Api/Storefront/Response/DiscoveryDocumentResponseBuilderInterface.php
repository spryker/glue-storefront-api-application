<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\GlueStorefrontApiApplication\Api\Storefront\Response;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface DiscoveryDocumentResponseBuilderInterface
{
    /**
     * Specification:
     * - Returns the document as a public JSON response with the given media type, cacheable for one hour and open to any origin.
     * - Returns `304 Not Modified` without a body when the `If-None-Match` header of the request has the ETag of the document.
     *
     * @param array<string, mixed> $document
     */
    public function createDocumentResponse(Request $request, array $document, string $mediaType): Response;

    /**
     * Specification:
     * - Returns an empty `404 Not Found` response.
     */
    public function createNotFoundResponse(): Response;
}
