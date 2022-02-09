<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication\Router\RouterResource;

use Spryker\Glue\GlueStorefrontApiApplication\Router\Route\RouteCollection;

interface RouterResourceInterface
{
    /**
     * @return \Spryker\Glue\GlueStorefrontApiApplication\Router\Route\RouteCollection
     */
    public function __invoke(): RouteCollection;
}
