<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\Translation;

use Generated\Shared\Transfer\SalesOrderThresholdTransfer;

/**
 * @deprecated Use {@link \Spryker\Zed\SalesOrderThreshold\Business\Translation\Hydrator\SalesOrderThresholdTranslationHydratorInterface} instead.
 */
interface SalesOrderThresholdTranslationReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    public function hydrateLocalizedMessages(SalesOrderThresholdTransfer $salesOrderThresholdTransfer): SalesOrderThresholdTransfer;
}
