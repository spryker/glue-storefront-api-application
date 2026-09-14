<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication\Dependency\External;

use Symfony\Component\Yaml\Yaml;

class GlueStorefrontApiApplicationToYamlAdapter implements GlueStorefrontApiApplicationToYamlAdapterInterface
{
    public function parseFile(string $filename, int $flags = 0): array
    {
        // @phpstan-ignore argument.type (flags is a bitmask of Yaml::PARSE_* constants; phpstan-symfony's stub enumerates only the valid combinations, which int can't statically express)
        return Yaml::parseFile($filename, $flags);
    }
}
