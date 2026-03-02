<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication\RequestBuilder;

use Generated\Shared\Transfer\GlueRequestTransfer;

interface LocaleRequestBuilderInterface
{
    public function extract(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer;
}
