<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueStorefrontApiApplication\Stub;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\Kernel\Controller\AbstractStorefrontApiController;

class ResourceController extends AbstractStorefrontApiController
{
    public function getCollectionAction(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return new GlueResponseTransfer();
    }

    public function getAction(string $id, GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return new GlueResponseTransfer();
    }

    public function postAction(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return new GlueResponseTransfer();
    }

    public function patchAction(string $id, GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return new GlueResponseTransfer();
    }

    public function deleteAction(string $id, GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return new GlueResponseTransfer();
    }
}
