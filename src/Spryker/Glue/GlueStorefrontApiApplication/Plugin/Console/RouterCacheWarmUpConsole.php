<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication\Plugin\Console;

use Spryker\Glue\Kernel\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationFactory getFactory()
 */
class RouterCacheWarmUpConsole extends Console
{
    /**
     * @var string
     */
    protected const NAME = 'glue-storefront-api:router:cache:warm-up';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName(static::NAME)
            ->setDescription('Builds a fresh cache for the GlueStorefrontApiApplication router.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->getFactory()->createCache()->warmUp();

        return static::CODE_SUCCESS;
    }
}
