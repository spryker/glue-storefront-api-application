<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication\Dependency\Client;

use Generated\Shared\Transfer\StoreTransfer;

interface GlueStorefrontApiApplicationToStoreClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore(): StoreTransfer;

    /**
     * @return bool
     */
    public function isDynamicStoreEnabled(): bool;
}
