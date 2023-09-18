<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\TaxApp;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\ExpenseBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\DataBuilder\StockAddressBuilder;
use Generated\Shared\Transfer\AccessTokenResponseTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantStockAddressTransfer;
use Generated\Shared\Transfer\StockAddressTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TaxAppConfigTransfer;
use Orm\Zed\TaxApp\Persistence\SpyTaxAppConfig;
use Orm\Zed\TaxApp\Persistence\SpyTaxAppConfigQuery;
use ReflectionProperty;
use Spryker\Shared\Oms\OmsConstants;
use Spryker\Zed\MerchantProfile\Communication\Plugin\TaxApp\MerchantProfileAddressCalculableObjectTaxAppExpanderPlugin;
use Spryker\Zed\Oms\Business\OrderStateMachine\PersistenceManager;
use Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToOauthClientFacadeBridge;
use SprykerTest\Shared\TaxApp\Plugins\CalculableObjectTaxAppExpanderPlugin;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\TaxApp\Business\TaxAppBusinessFactory getFactory(?string $moduleName = NULL)
 *
 * @SuppressWarnings(\SprykerTest\Zed\TaxApp\PHPMD)
 */
class TaxAppBusinessTester extends Actor
{
    use _generated\TaxAppBusinessTesterActions;

    /**
     * @return void
     */
    public function setQuoteTaxMetadataExpanderPlugins(): void
    {
        $this->setDependency(
            'PLUGINS_CALCULABLE_OBJECT_TAX_APP_EXPANDER',
            [
                new CalculableObjectTaxAppExpanderPlugin(),
                new MerchantProfileAddressCalculableObjectTaxAppExpanderPlugin(),
            ],
        );
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function createCalculableObjectTransfer(StoreTransfer $storeTransfer): CalculableObjectTransfer
    {
        $merchantTransfer1 = $this->haveMerchant();
        $this->haveMerchantProfile($merchantTransfer1);
        $merchantTransfer2 = $this->haveMerchant();
        $this->haveMerchantProfile($merchantTransfer2);

        $addressBuilder = (new AddressBuilder([AddressTransfer::ISO2_CODE => 'FR']));

        $shipmentBuilder = (new ShipmentBuilder())
            ->withShippingAddress($addressBuilder)
            ->withMethod();

        $expenseBuilder = (new ExpenseBuilder([
            ExpenseTransfer::TYPE => 'SHIPMENT_EXPENSE_TYPE',
        ]))->withShipment($shipmentBuilder);

        $quoteTransfer = (new QuoteBuilder())
            ->withCustomer()
            ->withBillingAddress()
            ->withItem(
                (new ItemBuilder([ItemTransfer::MERCHANT_REFERENCE => $merchantTransfer1->getMerchantReference()]))
                    ->withShipment(
                        (new ShipmentBuilder())
                            ->withAnotherShippingAddress(),
                    ),
            )
            ->withAnotherItem(
                (new ItemBuilder([ItemTransfer::MERCHANT_REFERENCE => $merchantTransfer2->getMerchantReference()]))
                    ->withAnotherShipment(
                        (new ShipmentBuilder())
                            ->withAnotherShippingAddress(),
                    ),
            )
            ->withTotals()
            ->withExpense($expenseBuilder)
            ->build();

        $quoteTransfer->setStore($storeTransfer);

        $calculableObjectTransfer = (new CalculableObjectTransfer())->fromArray($quoteTransfer->toArray(), true);
        $calculableObjectTransfer->setOriginalQuote($quoteTransfer);

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function createCalculableObjectTransferWithoutShipment(StoreTransfer $storeTransfer): CalculableObjectTransfer
    {
        $merchantTransfer1 = $this->haveMerchant();
        $this->haveMerchantProfile($merchantTransfer1);
        $merchantTransfer2 = $this->haveMerchant();
        $this->haveMerchantProfile($merchantTransfer2);

        $quoteTransfer = (new QuoteBuilder())
            ->withCustomer()
            ->withBillingAddress()
            ->withItem(
                (new ItemBuilder([ItemTransfer::MERCHANT_REFERENCE => $merchantTransfer1->getMerchantReference()])),
            )
            ->withAnotherItem(
                (new ItemBuilder([ItemTransfer::MERCHANT_REFERENCE => $merchantTransfer2->getMerchantReference()])),
            )
            ->withTotals()
            ->build();

        $quoteTransfer->setStore($storeTransfer);

        $calculableObjectTransfer = (new CalculableObjectTransfer())->fromArray($quoteTransfer->toArray(), true);
        $calculableObjectTransfer->setOriginalQuote($quoteTransfer);

        return $calculableObjectTransfer;
    }

    /**
     * @return void
     */
    public function ensureTaxAppConfigTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getTaxAppConfigQuery());
    }

    /**
     * @return \Orm\Zed\TaxApp\Persistence\SpyTaxAppConfigQuery
     */
    protected function getTaxAppConfigQuery(): SpyTaxAppConfigQuery
    {
        return SpyTaxAppConfigQuery::create();
    }

    /**
     * @param \Generated\Shared\Transfer\TaxAppConfigTransfer $taxAppConfigTransfer
     * @param \Orm\Zed\TaxApp\Persistence\SpyTaxAppConfig|null $taxAppConfigEntity
     *
     * @return void
     */
    public function assertTaxAppConfigStoredProperly(
        TaxAppConfigTransfer $taxAppConfigTransfer,
        ?SpyTaxAppConfig $taxAppConfigEntity = null
    ): void {
        $this->assertNotNull($taxAppConfigEntity);
        $this->assertEquals(
            $taxAppConfigTransfer->getApplicationId(),
            $taxAppConfigEntity->getApplicationId(),
        );
        $this->assertEquals(
            $taxAppConfigTransfer->getApiUrl(),
            $taxAppConfigEntity->getApiUrl(),
        );
        $this->assertEquals(
            $taxAppConfigTransfer->getVendorCode(),
            $taxAppConfigEntity->getVendorCode(),
        );
    }

    /**
     * @param int $idStore
     *
     * @return \Orm\Zed\SearchHttp\Persistence\SpySearchHttpConfig|null
     */
    public function findTaxAppConfigByIdStore(int $idStore): ?SpyTaxAppConfig
    {
        return $this->getTaxAppConfigQuery()
            ->filterByFkStore($idStore)
            ->findOne();
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function createStoreWithStoreReference(): StoreTransfer
    {
        return (new StoreTransfer())
            ->setName('test_store_name')
            ->setIdStore(1)
            ->setStoreReference('test_store_reference');
    }

    /**
     * @return void
     */
    protected function clearPersistenceManagerCache(): void
    {
        $stateCacheProperty = new ReflectionProperty(PersistenceManager::class, 'stateCache');
        $stateCacheProperty->setAccessible(true);
        $stateCacheProperty->setValue([]);
        $processCacheProperty = new ReflectionProperty(PersistenceManager::class, 'processCache');
        $processCacheProperty->setAccessible(true);
        $processCacheProperty->setValue([]);
    }

    /**
     * @param array $activeProcesses
     * @param string|null $xmlFolder
     *
     * @return void
     */
    public function configureTestStateMachine(array $activeProcesses, ?string $xmlFolder = null): void
    {
        $this->clearPersistenceManagerCache();

        if (!$xmlFolder) {
            $xmlFolder = realpath(__DIR__ . '/../../../../../_data/state-machine/');
        }

        $this->setConfig(OmsConstants::PROCESS_LOCATION, $xmlFolder);
        $this->setConfig(OmsConstants::ACTIVE_PROCESSES, $activeProcesses);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function haveCalculableObjectTransferWithMerchantStockAddress(StoreTransfer $storeTransfer): CalculableObjectTransfer
    {
        $merchantTransfer1 = $this->haveMerchant();
        $this->haveMerchantProfile($merchantTransfer1);
        $merchantTransfer2 = $this->haveMerchant();
        $this->haveMerchantProfile($merchantTransfer2);

        $addressBuilder = (new AddressBuilder([AddressTransfer::ISO2_CODE => 'FR']));

        $shipmentBuilder = (new ShipmentBuilder())
            ->withShippingAddress($addressBuilder)
            ->withMethod();

        $expenseBuilder = (new ExpenseBuilder([
            ExpenseTransfer::TYPE => 'SHIPMENT_EXPENSE_TYPE',
        ]))->withShipment($shipmentBuilder);

        $quoteTransfer = (new QuoteBuilder())
            ->withItem(
                (new ItemBuilder([
                    ItemTransfer::MERCHANT_REFERENCE => $merchantTransfer1->getMerchantReference(),
                    ItemTransfer::MERCHANT_STOCK_ADDRESSES => [
                        [
                            MerchantStockAddressTransfer::QUANTITY_TO_SHIP => 3,
                            MerchantStockAddressTransfer::STOCK_ADDRESS => (new StockAddressBuilder(
                                [
                                    StockAddressTransfer::ADDRESS1 => 'address-1-1',
                                    StockAddressTransfer::CITY => 'city-1-1',
                                    StockAddressTransfer::ZIP_CODE => 'zipcode-1-1',
                                ],
                            ))->withCountry()->build(),
                        ],
                        [
                            MerchantStockAddressTransfer::QUANTITY_TO_SHIP => 1,
                            MerchantStockAddressTransfer::STOCK_ADDRESS => (new StockAddressBuilder(
                                [
                                    StockAddressTransfer::ADDRESS1 => 'address-1-2',
                                    StockAddressTransfer::CITY => 'city-1-2',
                                    StockAddressTransfer::ZIP_CODE => 'zipcode-1-2',
                                ],
                            ))->withCountry()->build(),
                        ],
                    ],
                ])),
            )
            ->withAnotherItem(
                (new ItemBuilder([
                    ItemTransfer::MERCHANT_REFERENCE => $merchantTransfer2->getMerchantReference(),
                    ItemTransfer::MERCHANT_STOCK_ADDRESSES => [
                        [
                            MerchantStockAddressTransfer::QUANTITY_TO_SHIP => 10,
                            MerchantStockAddressTransfer::STOCK_ADDRESS => (new StockAddressBuilder(
                                [
                                    StockAddressTransfer::ADDRESS1 => 'address-2-1',
                                    StockAddressTransfer::CITY => 'city-2-1',
                                    StockAddressTransfer::ZIP_CODE => 'zipcode-2-1',
                                ],
                            ))->withCountry()->build(),
                        ],
                    ],
                ])),
            )
            ->withExpense($expenseBuilder)
            ->build();

        $quoteTransfer->setStore($storeTransfer);

        $calculableObjectTransfer = (new CalculableObjectTransfer())->fromArray($quoteTransfer->toArray(), true);
        $calculableObjectTransfer->setOriginalQuote($quoteTransfer);

        return $calculableObjectTransfer;
    }

    /**
     * @return void
     */
    public function mockOauthClient(): void
    {
        $accessTokenResponseTransfer = (new AccessTokenResponseTransfer())
            ->setIsSuccessful(true)
            ->setAccessToken('some-access-token')
            ->setExpiresAt((string)(time() + 86400));

        $oauthClientFacadeBridgeMock = Stub::makeEmpty(TaxAppToOauthClientFacadeBridge::class, [
            'getAccessToken' => $accessTokenResponseTransfer,
        ]);

        $this->mockFactoryMethod('getOauthClientFacade', $oauthClientFacadeBridgeMock);
    }
}
