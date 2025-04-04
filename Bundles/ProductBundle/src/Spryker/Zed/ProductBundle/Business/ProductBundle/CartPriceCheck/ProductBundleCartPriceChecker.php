<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\CartPriceCheck;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReaderInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceProductFacadeInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface;

class ProductBundleCartPriceChecker implements ProductBundleCartPriceCheckerInterface
{
    /**
     * @var string
     */
    public const CART_PRE_CHECK_PRICE_FAILED_TRANSLATION_KEY = 'cart.pre.check.price.failed';

    /**
     * @var string
     */
    protected const TRANSLATION_PARAMETER_SKU = '%sku%';

    /**
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface $productBundleRepository
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReaderInterface $productBundleReader
     */
    public function __construct(
        protected ProductBundleRepositoryInterface $productBundleRepository,
        protected ProductBundleToPriceProductFacadeInterface $priceProductFacade,
        protected ProductBundleReaderInterface $productBundleReader
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkCartPrices(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $cartPreCheckResponseTransfer = (new CartPreCheckResponseTransfer())
            ->setIsSuccess(true);

        $productConcreteSkus = $this->getProductCocnreteSkusFromCartChangeTransfer($cartChangeTransfer);
        $productBundleSkus = array_keys($this->productBundleReader->getProductForBundleTransfersByProductConcreteSkus($productConcreteSkus));

        $checkedItems = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!in_array($itemTransfer->getSku(), $productBundleSkus) || in_array($itemTransfer->getSkuOrFail(), $checkedItems, true)) {
                continue;
            }

            $itemTransfer->requireSku()
                ->requireQuantity();

            $cartPreCheckResponseTransfer = $this->checkItemPrice($itemTransfer, $cartPreCheckResponseTransfer, $cartChangeTransfer);
            $checkedItems[] = $itemTransfer->getSkuOrFail();
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductForBundleTransfer $productForBundleTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    protected function createPriceProductFilter(
        ProductForBundleTransfer $productForBundleTransfer,
        QuoteTransfer $quoteTransfer
    ): PriceProductFilterTransfer {
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setSku($productForBundleTransfer->getSku())
            ->setPriceMode($quoteTransfer->getPriceMode())
            ->setCurrencyIsoCode($quoteTransfer->getCurrency()->getCode())
            ->setStoreName($quoteTransfer->getStore()->getName())
            ->setQuote($quoteTransfer);

        return $priceProductFilterTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function checkItemPrice(
        ItemTransfer $itemTransfer,
        CartPreCheckResponseTransfer $cartPreCheckResponseTransfer,
        CartChangeTransfer $cartChangeTransfer
    ): CartPreCheckResponseTransfer {
        $bundledProducts = $this->productBundleRepository->findBundledProductsBySku($itemTransfer->getSku());

        foreach ($bundledProducts as $bundledProduct) {
            $priceProductFilterTransfer = $this->createPriceProductFilter($bundledProduct, $cartChangeTransfer->getQuote());
            if ($this->priceProductFacade->hasValidPriceFor($priceProductFilterTransfer)) {
                continue;
            }

            return $cartPreCheckResponseTransfer
                ->setIsSuccess(false)
                ->addMessage($this->createMessage($bundledProduct));
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductForBundleTransfer $productForBundleTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessage(ProductForBundleTransfer $productForBundleTransfer): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue(static::CART_PRE_CHECK_PRICE_FAILED_TRANSLATION_KEY)
            ->setParameters([static::TRANSLATION_PARAMETER_SKU => $productForBundleTransfer->getSku()]);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array<string>
     */
    protected function getProductCocnreteSkusFromCartChangeTransfer(CartChangeTransfer $cartChangeTransfer): array
    {
        $productConcreteSkus = [];

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $productConcreteSkus[] = $itemTransfer->getSku();
        }

        return $productConcreteSkus;
    }
}
