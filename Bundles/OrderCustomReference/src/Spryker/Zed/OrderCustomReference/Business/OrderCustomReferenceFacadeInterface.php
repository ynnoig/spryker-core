<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReference\Business;

use Generated\Shared\Transfer\QuoteTransfer;

interface OrderCustomReferenceFacadeInterface
{
    /**
     * Specification:
     * - Returns order custom reference related Quote fields allowed for saving.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    public function getOrderCustomReferenceQuoteFieldsAllowedForSaving(QuoteTransfer $quoteTransfer): array;
}
