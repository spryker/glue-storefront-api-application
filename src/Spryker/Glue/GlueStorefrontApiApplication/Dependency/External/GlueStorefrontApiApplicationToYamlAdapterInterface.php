<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication\Dependency\External;

interface GlueStorefrontApiApplicationToYamlAdapterInterface
{
    public function parseFile(string $filename, int $flags = 0): array;
}
