<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider;

use ArrayObject;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider\ProductAttributeTableDataProviderInterface;

class ProductAttributeGuiTableConfigurationProvider implements ProductAttributeGuiTableConfigurationProviderInterface
{
    public const COL_KEY_ATTRIBUTE_NAME = 'attribute_name';
    public const COL_KEY_ATTRIBUTE_DEFAULT = 'attribute_default';

    protected const TITLE_COLUMN_ATTRIBUTE_NAME = 'Attribute';
    protected const TITLE_COLUMN_ATTRIBUTE_DEFAULT = 'Default';

    /**
     * @var \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    protected $guiTableFactory;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider\ProductAttributeTableDataProviderInterface
     */
    protected $productAttributeTableDataProvider;

    /**
     * @param \Spryker\Shared\GuiTable\GuiTableFactoryInterface $guiTableFactory
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider\ProductAttributeTableDataProviderInterface $productAttributeTableDataProvider
     */
    public function __construct(
        GuiTableFactoryInterface $guiTableFactory,
        ProductAttributeTableDataProviderInterface $productAttributeTableDataProvider
    ) {
        $this->guiTableFactory = $guiTableFactory;
        $this->productAttributeTableDataProvider = $productAttributeTableDataProvider;
    }

    /**
     * @phpstan-param ArrayObject<string, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributeTransfers
     *
     * @param string[] $attributes
     * @param \ArrayObject|\Generated\Shared\Transfer\LocalizedAttributesTransfer[] $localizedAttributeTransfers
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(array $attributes, ArrayObject $localizedAttributeTransfers): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationBuilder = $this->guiTableFactory->createConfigurationBuilder();

        $guiTableConfigurationBuilder->addColumnText(static::COL_KEY_ATTRIBUTE_NAME, static::TITLE_COLUMN_ATTRIBUTE_NAME, true, false)
            ->addColumnText(static::COL_KEY_ATTRIBUTE_DEFAULT, static::TITLE_COLUMN_ATTRIBUTE_DEFAULT, true, false);

        foreach ($localizedAttributeTransfers as $localizedAttributesTransfer) {
            $localeTransfer = $localizedAttributesTransfer->getLocaleOrFail();

            $guiTableConfigurationBuilder->addColumnText(
                $localeTransfer->getLocaleNameOrFail(),
                $localeTransfer->getLocaleNameOrFail(),
                true,
                true
            );
        }

        $guiTableConfigurationBuilder
            ->setDataSourceInlineData($this->productAttributeTableDataProvider->getData($attributes, $localizedAttributeTransfers))
            ->setIsPaginationEnabled(false)
            ->isSearchEnabled(false);

        return $guiTableConfigurationBuilder->createConfiguration();
    }
}
