<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOptionCartConnector\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToProductOptionFacadeBridge;
use Spryker\Zed\ProductOptionCartConnector\ProductOptionCartConnectorDependencyProvider;
use SprykerTest\Shared\ProductOption\Helper\ProductOptionGroupDataHelper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOptionCartConnector
 * @group Business
 * @group Facade
 * @group ProductOptionCartConnectorFacadeTest
 * Add your own group annotations below this line
 */
class ProductOptionCartConnectorFacadeTest extends Unit
{
    /**
     * @var int
     */
    public const ID_PRODUCT_OPTION = 5;

    /**
     * @var int
     */
    public const DUMMY_PRICE = 1500;

    /**
     * @var \SprykerTest\Zed\ProductOptionCartConnector\ProductOptionCartConnectorBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductOptionCartConnector\Business\ProductOptionCartConnectorFacadeInterface
     */
    protected $productOptionCartConnectorFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->productOptionCartConnectorFacade = $this->tester->getLocator()->productOptionCartConnector()->facade();
        $this->tester->addDependencies();
        /*
         * Used in a context of {@link \Spryker\Zed\Cart\Communication\Controller\GatewayController}
         */
        $this->tester->addCurrentStore($this->tester->haveStore([StoreTransfer::NAME => 'DE']));
    }

    /**
     * @return void
     */
    public function testExpandProductOptionsSanitizesNetPriceWhenGrossPriceModeIsActive(): void
    {
        // Assign
        $this->mockProductOptionFacade(
            (new ProductOptionTransfer())
                ->setIdProductOptionValue(static::ID_PRODUCT_OPTION)
                ->setUnitNetPrice(static::DUMMY_PRICE),
        );

        $cartChangeTransfer = $this->createCartChangeTransferWithDefaultItem($this->getGrossPriceModeIdentifier());
        $expectedResult = 0;

        // Act
        $actualResult = $this->productOptionCartConnectorFacade->expandProductOptions($cartChangeTransfer);

        // Assert
        $this->assertSame($expectedResult, $actualResult->getItems()[0]->getProductOptions()[0]->getUnitNetPrice());
    }

    /**
     * @return void
     */
    public function testExpandProductOptionsSanitizesGrossPriceWhenNetPriceModeIsActive(): void
    {
        // Assign
        $this->mockProductOptionFacade(
            (new ProductOptionTransfer())
                ->setIdProductOptionValue(static::ID_PRODUCT_OPTION)
                ->setUnitGrossPrice(static::DUMMY_PRICE),
        );

        $cartChangeTransfer = $this->createCartChangeTransferWithDefaultItem($this->getNetPriceModeIdentifier());
        $expectedResult = 0;

        // Act
        $actualResult = $this->productOptionCartConnectorFacade->expandProductOptions($cartChangeTransfer);

        // Assert
        $this->assertSame($expectedResult, $actualResult->getItems()[0]->getProductOptions()[0]->getUnitGrossPrice());
    }

    /**
     * @return void
     */
    public function testExpandProductOptionsExpandsProductOptions(): void
    {
        // Assign
        $expectedResult = (new ProductOptionTransfer())
            ->setIdProductOptionValue(static::ID_PRODUCT_OPTION)
            ->setGroupName('test')
            ->setUnitGrossPrice(0);
        $this->mockProductOptionFacade(clone $expectedResult);

        $cartChangeTransfer = $this->createCartChangeTransferWithDefaultItem($this->getNetPriceModeIdentifier());

        // Act
        $actualResult = $this->productOptionCartConnectorFacade->expandProductOptions($cartChangeTransfer);

        // Assert
        $this->assertEquals($expectedResult->toArray(), $actualResult->getItems()[0]->getProductOptions()[0]->toArray());
    }

    /**
     * @return void
     */
    public function testValidateProductOptionValuePricesValidatesAllOptions(): void
    {
        // Assign
        $cartPriceMode = $this->getDefaultPriceMode();
        $currentStoreName = $this->getCurrentStoreName();
        $currentCurrencyCode = $this->getCurrentCurrencyCode();
        $expectedProductOptionErrorCount = 3;

        $productOptionCollection = [];
        $productOptionCollection[0] = $this->createProductOption(
            [
                $this->createPriceData($currentCurrencyCode, $currentStoreName, null, null),
            ],
        );
        $productOptionCollection[1] = $this->createProductOption(
            [
                $this->createPriceData($currentCurrencyCode, $currentStoreName, null, null),
            ],
        );
        $productOptionCollection[2] = $this->createProductOption(
            [
                $this->createPriceData($currentCurrencyCode, $currentStoreName, null, null),
            ],
        );

        $cartChangeTransfer = $this->createCartChangeTransfer($cartPriceMode);
        $cartChangeTransfer->addItem(
            (new ItemTransfer())
                ->setSku('exampleSku1')
                ->addProductOption($productOptionCollection[0])
                ->addProductOption($productOptionCollection[1]),
        );
        $cartChangeTransfer->addItem(
            (new ItemTransfer())
                ->setSku('exampleSku2')
                ->addProductOption($productOptionCollection[2]),
        );

        // Act
        $actualResult = $this->productOptionCartConnectorFacade->validateProductOptionValuePrices($cartChangeTransfer);

        // Assert
        $this->assertSame($expectedProductOptionErrorCount, $actualResult->getMessages()->count());
    }

    /**
     * @return void
     */
    public function testValidateProductOptionValuePricesReturnsSuccessFlagWhenNoViolationWasFound(): void
    {
        // Assign
        $expectedResult = true;
        $cartPriceMode = $this->getDefaultPriceMode();
        $currentStoreName = $this->getCurrentStoreName();
        $currentCurrencyCode = $this->getCurrentCurrencyCode();

        $productOptionCollection = [];
        $productOptionCollection[0] = $this->createProductOption(
            [
                $this->createPriceData($currentCurrencyCode, $currentStoreName, 100, 200),
            ],
        );
        $productOptionCollection[1] = $this->createProductOption(
            [
                $this->createPriceData($currentCurrencyCode, $currentStoreName, 100, 200),
            ],
        );
        $productOptionCollection[2] = $this->createProductOption(
            [
                $this->createPriceData($currentCurrencyCode, $currentStoreName, 100, 200),
            ],
        );

        $cartChangeTransfer = $this->createCartChangeTransfer($cartPriceMode);
        $cartChangeTransfer->addItem(
            (new ItemTransfer())
                ->setSku('exampleSku1')
                ->addProductOption($productOptionCollection[0])
                ->addProductOption($productOptionCollection[1]),
        );
        $cartChangeTransfer->addItem(
            (new ItemTransfer())
                ->setSku('exampleSku2')
                ->addProductOption($productOptionCollection[2]),
        );

        // Act
        $actualResponse = $this->productOptionCartConnectorFacade->validateProductOptionValuePrices($cartChangeTransfer);

        // Assert
        $this->assertSame($expectedResult, $actualResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateProductOptionValuePricesReturnsUnsuccessfulFlagWhenViolationWasFound(): void
    {
        // Assign
        $expectedResult = false;
        $isCurrentPriceModeNet = $this->getDefaultPriceMode() === $this->getNetPriceModeIdentifier();
        $cartPriceMode = $this->getDefaultPriceMode();
        $currentStoreName = $this->getCurrentStoreName();
        $currentCurrencyCode = $this->getCurrentCurrencyCode();

        $productOptionCollection = [];
        $productOptionCollection[0] = $this->createProductOption(
            [
                $this->createPriceData($currentCurrencyCode, $currentStoreName, 100, 200),
            ],
        );
        $productOptionCollection[1] = $this->createProductOption(
            [
                $this->createPriceData($currentCurrencyCode, $currentStoreName, 100, 200),
            ],
        );
        $productOptionCollection[2] = $this->createProductOption(
            [
                $this->createPriceData(
                    $currentCurrencyCode,
                    $currentStoreName,
                    // current price mode should not have price, but the other
                    $isCurrentPriceModeNet ? 100 : null,
                    $isCurrentPriceModeNet ? null : 100,
                ),
            ],
        );

        $cartChangeTransfer = $this->createCartChangeTransfer($cartPriceMode);
        $cartChangeTransfer->addItem(
            (new ItemTransfer())
                ->setSku('exampleSku1')
                ->addProductOption($productOptionCollection[0])
                ->addProductOption($productOptionCollection[1]),
        );
        $cartChangeTransfer->addItem(
            (new ItemTransfer())
                ->setSku('exampleSku2')
                ->addProductOption($productOptionCollection[2]),
        );

        // Act
        $actualResponse = $this->productOptionCartConnectorFacade->validateProductOptionValuePrices($cartChangeTransfer);

        // Assert
        $this->assertSame($expectedResult, $actualResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateProductOptionValuePricesUsesDefaultPriceModeForValidationWhenPriceModeIsNotDefined(): void
    {
        // Assign
        $expectedResult = true;
        $cartPriceMode = null;
        $isCurrentPriceModeNet = $this->getDefaultPriceMode() === $this->getNetPriceModeIdentifier();
        $currentStoreName = $this->getCurrentStoreName();
        $currentCurrencyCode = $this->getCurrentCurrencyCode();

        $productOptionCollection = [];
        $productOptionCollection[0] = $this->createProductOption(
            [
                $this->createPriceData(
                    $currentCurrencyCode,
                    $currentStoreName,
                    // only current price mode should have price
                    $isCurrentPriceModeNet ? null : 100,
                    $isCurrentPriceModeNet ? 100 : null,
                ),
            ],
        );

        $cartChangeTransfer = $this->createCartChangeTransfer($cartPriceMode);
        $cartChangeTransfer->addItem(
            (new ItemTransfer())
                ->setSku('exampleSku1')
                ->addProductOption($productOptionCollection[0]),
        );

        // Act
        $actualResponse = $this->productOptionCartConnectorFacade->validateProductOptionValuePrices($cartChangeTransfer);

        // Assert
        $this->assertSame($expectedResult, $actualResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateProductOptionValuePricesUsesNetPricesForValidationWhenPriceModeIsSetAsNet(): void
    {
        // Assign
        $expectedResult = true;
        $cartPriceMode = $this->getNetPriceModeIdentifier();
        $currentStoreName = $this->getCurrentStoreName();
        $currentCurrencyCode = $this->getCurrentCurrencyCode();

        $productOptionCollection = [];
        $productOptionCollection[0] = $this->createProductOption(
            [
                $this->createPriceData($currentCurrencyCode, $currentStoreName, null, 100),
            ],
        );

        $cartChangeTransfer = $this->createCartChangeTransfer($cartPriceMode);
        $cartChangeTransfer->addItem(
            (new ItemTransfer())
                ->setSku('exampleSku1')
                ->addProductOption($productOptionCollection[0]),
        );

        // Act
        $actualResponse = $this->productOptionCartConnectorFacade->validateProductOptionValuePrices($cartChangeTransfer);

        // Assert
        $this->assertSame($expectedResult, $actualResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateProductOptionValuePricesUsesGrossPricesForValidationWhenPriceModeIsSetAsGross(): void
    {
        // Assign
        $expectedResult = true;
        $cartPriceMode = $this->getGrossPriceModeIdentifier();
        $currentStoreName = $this->getCurrentStoreName();
        $currentCurrencyCode = $this->getCurrentCurrencyCode();

        $productOptionCollection = [];
        $productOptionCollection[0] = $this->createProductOption(
            [
                $this->createPriceData($currentCurrencyCode, $currentStoreName, 100, null),
            ],
        );

        $cartChangeTransfer = $this->createCartChangeTransfer($cartPriceMode);
        $cartChangeTransfer->addItem(
            (new ItemTransfer())
                ->setSku('exampleSku1')
                ->addProductOption($productOptionCollection[0]),
        );

        // Act
        $actualResponse = $this->productOptionCartConnectorFacade->validateProductOptionValuePrices($cartChangeTransfer);

        // Assert
        $this->assertSame($expectedResult, $actualResponse->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckProductOptionExistenceShouldWriteErrorWhenOptionDoesNotExist(): void
    {
        $cartChangeTransfer = new CartChangeTransfer();

        $itemTransfer = new ItemTransfer();

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setIdProductOptionValue(0);
        $itemTransfer->addProductOption($productOptionTransfer);

        $cartChangeTransfer->addItem($itemTransfer);

        $cartPreCheckResponseTransfer = $this->productOptionCartConnectorFacade->checkProductOptionExistence($cartChangeTransfer);

        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertCount(1, $cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return string
     */
    protected function getGrossPriceModeIdentifier(): string
    {
        return $this->tester->getLocator()->price()->facade()->getGrossPriceModeIdentifier();
    }

    /**
     * @return string
     */
    protected function getNetPriceModeIdentifier(): string
    {
        return $this->tester->getLocator()->price()->facade()->getNetPriceModeIdentifier();
    }

    /**
     * @param string|null $priceMode
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransfer(?string $priceMode): CartChangeTransfer
    {
        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode($priceMode)
            ->setCurrency((new CurrencyTransfer())->setCode($this->getCurrentCurrencyCode()));

        $cartChangeTransfer = (new CartChangeTransfer())
            ->setQuote($quoteTransfer);

        return $cartChangeTransfer;
    }

    /**
     * @param string|null $priceMode
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransferWithDefaultItem(?string $priceMode): CartChangeTransfer
    {
        $cartChangeTransfer = $this->createCartChangeTransfer($priceMode);
        $this->addItemToCart($cartChangeTransfer, new ArrayObject([
            (new ProductOptionValueTransfer())->setIdProductOptionValue(static::ID_PRODUCT_OPTION),
        ]));

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cart
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductOptionTransfer> $productOptionCollection
     *
     * @return void
     */
    protected function addItemToCart(CartChangeTransfer $cart, ArrayObject $productOptionCollection): void
    {
        $item = new ItemTransfer();
        $item->setProductOptions($productOptionCollection);

        $cart->addItem($item);
    }

    /**
     * @uses ProductOptionFacadeInterface::getProductOptionValue()
     *
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     *
     * @return void
     */
    protected function mockProductOptionFacade(ProductOptionTransfer $productOptionTransfer): void
    {
        $productOptionFacadeMock = $this->getMockBuilder(ProductOptionCartConnectorToProductOptionFacadeBridge::class)
            ->onlyMethods(['getProductOptionValueById'])
            ->disableOriginalConstructor()
            ->getMock();

        $productOptionFacadeMock->expects($this->any())
            ->method('getProductOptionValueById')
            ->willReturn($productOptionTransfer);

        $this->tester->setDependency(
            ProductOptionCartConnectorDependencyProvider::FACADE_PRODUCT_OPTION,
            $productOptionFacadeMock,
        );

        // Reload facade instance to have the new dependency
        $this->productOptionCartConnectorFacade = $this->tester->getLocator()->productOptionCartConnector()->facade();
    }

    /**
     * @return string
     */
    protected function getDefaultPriceMode(): string
    {
        return $this->tester->getLocator()->price()->facade()->getDefaultPriceMode();
    }

    /**
     * @return string
     */
    protected function getCurrentCurrencyCode(): string
    {
        return $this->tester->getLocator()->currency()->facade()->getCurrent()->getCode();
    }

    /**
     * @return string
     */
    protected function getCurrentStoreName(): string
    {
        return $this->tester->getLocator()->store()->facade()->getCurrentStore()->getName();
    }

    /**
     * @param string $currencyCode
     * @param string|null $storeName
     * @param int|null $grossAmount
     * @param int|null $netAmount
     *
     * @return array
     */
    protected function createPriceData(string $currencyCode, ?string $storeName, ?int $grossAmount, ?int $netAmount): array
    {
        return [
            ProductOptionGroupDataHelper::CURRENCY_CODE => $currencyCode,
            ProductOptionGroupDataHelper::STORE_NAME => $storeName,
            MoneyValueTransfer::GROSS_AMOUNT => $grossAmount,
            MoneyValueTransfer::NET_AMOUNT => $netAmount,
        ];
    }

    /**
     * @param array $prices
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    protected function createProductOption(array $prices): ProductOptionTransfer
    {
        $productOptionGroupTransfer = $this->tester->haveProductOptionGroupWithValues(
            [],
            [
                [
                    [],
                    $prices,
                ],
            ],
        );

        $productOptionTransfer = (new ProductOptionTransfer())
            ->setIdProductOptionValue($productOptionGroupTransfer->getProductOptionValues()[0]->getIdProductOptionValue());

        return $productOptionTransfer;
    }
}
