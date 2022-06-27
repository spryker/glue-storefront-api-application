<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication\Dependency\External;

use Symfony\Component\Yaml\Yaml;

class GlueStorefrontApiApplicationToYamlAdapter implements GlueStorefrontApiApplicationToYamlAdapterInterface
{
    /**
     * @param string $filename
     * @param int $flags
     *
     * @return array
     */
    public function parseFile(string $filename, int $flags = 0): array
    {
        return Yaml::parseFile($filename, $flags);
    }
}
