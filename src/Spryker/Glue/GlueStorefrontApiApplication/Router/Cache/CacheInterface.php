<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication\Router\Cache;

interface CacheInterface
{
    /**
     * @return void
     */
    public function warmUp(): void;
}
