<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Glue\GlueStorefrontApiApplication\Config;

use Codeception\Test\Unit;
use Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationConfig;
use Spryker\Shared\GlueStorefrontApiApplication\GlueStorefrontApiApplicationConstants;
use SprykerTest\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueStorefrontApiApplication
 * @group Config
 * @group GlueStorefrontApiApplicationConfigTest
 * Add your own group annotations below this line
 */
class GlueStorefrontApiApplicationConfigTest extends Unit
{
    protected const string STOREFRONT_HOST = 'glue-storefront.example.com';

    protected GlueStorefrontApiApplicationTester $tester;

    public function testGivenNoBaseUrlWhenGettingStorefrontApiBaseUrlThenReturnsHttpsUrlOfTheHost(): void
    {
        // Arrange
        $this->tester->setConfig(GlueStorefrontApiApplicationConstants::GLUE_STOREFRONT_API_BASE_URL, '');
        $this->tester->setConfig(GlueStorefrontApiApplicationConstants::GLUE_STOREFRONT_API_HOST, static::STOREFRONT_HOST);

        // Act
        $baseUrl = $this->createConfig()->getStorefrontApiBaseUrl();

        // Assert
        $this->assertSame('https://' . static::STOREFRONT_HOST, $baseUrl);
    }

    public function testGivenStorefrontHostAndBaseUrlWhenGettingMcpAllowedHostsThenReturnsEachHostOnce(): void
    {
        // Arrange
        $this->tester->setConfig(GlueStorefrontApiApplicationConstants::GLUE_STOREFRONT_API_HOST, static::STOREFRONT_HOST);
        $this->tester->setConfig(GlueStorefrontApiApplicationConstants::GLUE_STOREFRONT_API_BASE_URL, 'https://api.example.com:8443/');

        // Act
        $allowedHosts = $this->createConfig()->getMcpAllowedHosts();

        // Assert
        $this->assertSame([static::STOREFRONT_HOST, 'api.example.com'], $allowedHosts);
    }

    protected function createConfig(): GlueStorefrontApiApplicationConfig
    {
        return new GlueStorefrontApiApplicationConfig();
    }
}
