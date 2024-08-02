<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueStorefrontApiApplication\LanguageNegotiation;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AcceptLanguageTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Glue\GlueStorefrontApiApplication\Dependency\Client\GlueStorefrontApiApplicationToStoreClientInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Dependency\Service\GlueStorefrontApiApplicationToLocaleServiceBridge;
use Spryker\Glue\GlueStorefrontApiApplication\Dependency\Service\GlueStorefrontApiApplicationToLocaleServiceInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Language\LanguageNegotiation;
use SprykerTest\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationTester;

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
     * @var array<string, string>
     */
    protected const AVAILABLE_LOCALE_ISO_CODES = [
        'de' => 'de_DE',
        'en' => 'en_US',
    ];

    /**
     * @var list<string>
     */
    protected const AVAILABLE_LOCALE_ISO_CODES_DYNAMIC_STORE = [
        'de_DE',
        'en_US',
    ];

    /**
     * @var \SprykerTest\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationTester
     */
    protected GlueStorefrontApiApplicationTester $tester;

    /**
     * @dataProvider languageIsoCodeDataProvider
     *
     * @param string $acceptLanguage
     * @param \Generated\Shared\Transfer\AcceptLanguageTransfer|null $foundAcceptLanguage
     * @param string $expectedLanguageIsoCode
     *
     * @return void
     */
    public function testGetLanguageIsoCode(string $acceptLanguage, ?AcceptLanguageTransfer $foundAcceptLanguage, string $expectedLanguageIsoCode): void
    {
        // Arrange
        $storeTransfer = $this->createStoreTransfer(static::AVAILABLE_LOCALE_ISO_CODES);
        $storeClientMock = $this->createStoreClientMock($storeTransfer);
        $localeServiceMock = $this->createLocaleServiceMock($foundAcceptLanguage);

        $languageNegotiation = new LanguageNegotiation(
            $storeClientMock,
            $localeServiceMock,
        );

        // Act
        $languageIsoCode = $languageNegotiation->getLanguageIsoCode($acceptLanguage);

        // Assert
        $this->assertSame($expectedLanguageIsoCode, $languageIsoCode);
    }

    /**
     * @dataProvider languageIsoCodeDynamicStoreDataProvider
     *
     * @param string $acceptLanguage
     * @param string $expectedLocaleIsoCode
     *
     * @return void
     */
    public function testGetLanguageIsoCodeReturnsCorrectLocaleForDynamicStore(
        string $acceptLanguage,
        string $expectedLocaleIsoCode
    ): void {
        // Arrange
        $storeTransfer = $this->createStoreTransfer(static::AVAILABLE_LOCALE_ISO_CODES_DYNAMIC_STORE);
        $storeFacadeMock = $this->createStoreClientMock($storeTransfer);

        $languageNegotiation = new LanguageNegotiation(
            $storeFacadeMock,
            new GlueStorefrontApiApplicationToLocaleServiceBridge($this->tester->getLocator()->locale()->service()),
        );

        // Act
        $languageIsoCode = $languageNegotiation->getLanguageIsoCode($acceptLanguage);

        // Assert
        $this->assertSame($expectedLocaleIsoCode, $languageIsoCode);
    }

    /**
     * @return array<string, array<string, string>>
     */
    protected function languageIsoCodeDataProvider(): array
    {
        return [
            'AcceptLanguageUnavailable' => [
                'acceptLanguage' => '',
                'foundAcceptLanguage' => null,
                'expectedLanguageIsoCode' => static::AVAILABLE_LOCALE_ISO_CODES['de'],
            ],
            'AcceptLanguageInvalid' => [
                'acceptLanguage' => 'invalid',
                'foundAcceptLanguage' => null,
                'expectedLanguageIsoCode' => static::AVAILABLE_LOCALE_ISO_CODES['de'],
            ],
            'AcceptLanguageNegotiable' => [
                'acceptLanguage' => 'de;q=0.8, en;q=0.2',
                'foundAcceptLanguage' => (new AcceptLanguageTransfer())->setType('de'),
                'expectedLanguageIsoCode' => static::AVAILABLE_LOCALE_ISO_CODES['de'],
            ],
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    protected function languageIsoCodeDynamicStoreDataProvider(): array
    {
        return [
            'AcceptLanguageUnavailable' => [
                'acceptLanguage' => '',
                'expectedLocaleIsoCode' => static::AVAILABLE_LOCALE_ISO_CODES_DYNAMIC_STORE[0],
            ],
            'AcceptLanguageInvalid' => [
                'acceptLanguage' => 'invalid',
                'expectedLocaleIsoCode' => static::AVAILABLE_LOCALE_ISO_CODES_DYNAMIC_STORE[0],
            ],
            'AcceptLanguageNegotiableDe' => [
                'acceptLanguage' => 'de;q=0.8, en;q=0.2',
                'expectedLocaleIsoCode' => static::AVAILABLE_LOCALE_ISO_CODES_DYNAMIC_STORE[0],
            ],
            'AcceptLanguageNegotiableEn' => [
                'acceptLanguage' => 'en;q=0.8, de;q=0.2',
                'expectedLocaleIsoCode' => static::AVAILABLE_LOCALE_ISO_CODES_DYNAMIC_STORE[1],
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueStorefrontApiApplication\Dependency\Client\GlueStorefrontApiApplicationToStoreClientInterface
     */
    protected function createStoreClientMock(
        StoreTransfer $storeTransfer
    ): GlueStorefrontApiApplicationToStoreClientInterface {
        $storeClientMock = $this->getMockBuilder(GlueStorefrontApiApplicationToStoreClientInterface::class)
            ->onlyMethods(['getCurrentStore'])
            ->getMock();

        $storeClientMock->method('getCurrentStore')->willReturn($storeTransfer);

        return $storeClientMock;
    }

    /**
     * @param array<string, string> $availableLocaleIsoCodes
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function createStoreTransfer(array $availableLocaleIsoCodes): StoreTransfer
    {
        return (new StoreTransfer())->setAvailableLocaleIsoCodes($availableLocaleIsoCodes);
    }

    /**
     * @param \Generated\Shared\Transfer\AcceptLanguageTransfer|null $foundAcceptLanguage
     *
     * @return \Spryker\Glue\GlueStorefrontApiApplication\Dependency\Service\GlueStorefrontApiApplicationToLocaleServiceInterface
     */
    protected function createLocaleServiceMock(?AcceptLanguageTransfer $foundAcceptLanguage): GlueStorefrontApiApplicationToLocaleServiceInterface
    {
        $localeServiceMock = $this->getMockBuilder(GlueStorefrontApiApplicationToLocaleServiceInterface::class)->getMock();

        $localeServiceMock->method('getAcceptLanguage')->willReturn($foundAcceptLanguage);

        return $localeServiceMock;
    }
}
