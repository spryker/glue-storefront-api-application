<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueStorefrontApiApplication\LanguageNegotiation;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Negotiation\LanguageNegotiator;
use Spryker\Glue\GlueStorefrontApiApplication\Dependency\Client\GlueStorefrontApiApplicationToStoreClientInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Language\LanguageNegotiation;
use Spryker\Glue\GlueStorefrontApiApplication\Language\LanguageNegotiationInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueStorefrontApiApplication
 * @group LanguageNegotiation
 * @group LanguageNegotiationTest
 * Add your own group annotations below this line
 */
class LanguageNegotiationTest extends Unit
{
    /**
     * @var array
     */
    protected $locales = ['de' => 'de_DE', 'en' => 'en_US'];

    /**
     * @return void
     */
    public function testGetLanguageIsoCodeShouldReturnBaseWhenSelected(): void
    {
        $languageNegotiation = $this->createLanguageNegotiation();

        $isoCode = $languageNegotiation->getLanguageIsoCode('en; de;q=0.5');

        $this->assertSame('en_US', $isoCode);
    }

    /**
     * @return void
     */
    public function testGetLanguageIsoCodeShouldReturnBasedOnPriority(): void
    {
        $languageNegotiation = $this->createLanguageNegotiation();

        $isoCode = $languageNegotiation->getLanguageIsoCode('de;q=0.8, en;q=0.2');
        $this->assertSame('de_DE', $isoCode);

        $isoCode = $languageNegotiation->getLanguageIsoCode('de;q=0.2, en;q=0.8');
        $this->assertSame('en_US', $isoCode);
    }

    /**
     * @return void
     */
    public function testGetLanguageWhenNoHeaderProviderMustReturnFirstLocale(): void
    {
        $languageNegotiation = $this->createLanguageNegotiation();

        $isoCode = $languageNegotiation->getLanguageIsoCode('');
        $this->assertSame('de_DE', $isoCode);
    }

    /**
     * @return void
     */
    public function testGetLanguageWhenHeaderInvalidFormatterMustReturnFirstLocale(): void
    {
        $languageNegotiation = $this->createLanguageNegotiation();

        $isoCode = $languageNegotiation->getLanguageIsoCode('wrong');
        $this->assertSame('de_DE', $isoCode);
    }

    /**
     * @return \Spryker\Glue\GlueStorefrontApiApplication\Language\LanguageNegotiationInterface
     */
    protected function createLanguageNegotiation(): LanguageNegotiationInterface
    {
        return new LanguageNegotiation($this->createStoreClientMock(), $this->createLanguageNegotiator());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueStorefrontApiApplication\Dependency\Client\GlueStorefrontApiApplicationToStoreClientInterface
     */
    protected function createStoreClientMock(): GlueStorefrontApiApplicationToStoreClientInterface
    {
        $storeClientMock = $this->getMockBuilder(GlueStorefrontApiApplicationToStoreClientInterface::class)
            ->setMethods(['getCurrentStore'])
            ->getMock();

        $storeTransfer = (new StoreTransfer())->setAvailableLocaleIsoCodes($this->locales);

        $storeClientMock->method('getCurrentStore')
            ->willReturn($storeTransfer);

        return $storeClientMock;
    }

    /**
     * @return \Negotiation\LanguageNegotiator
     */
    protected function createLanguageNegotiator(): LanguageNegotiator
    {
        return new LanguageNegotiator();
    }
}
