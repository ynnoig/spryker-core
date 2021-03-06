<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider;

interface StoreFilterOptionsProviderInterface
{
    /**
     * @phpstan-return array<int, string>
     *
     * @return string[]
     */
    public function getStoreOptions(): array;
}
