<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Persistence;

use Generated\Shared\Transfer\QuoteTransfer;

interface MultiCartRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function findSimilarCustomerQuoteNames(QuoteTransfer $quoteTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function checkQuoteNameAvailability(QuoteTransfer $quoteTransfer): bool;

    /**
     * @param string $customerReference
     *
     * @return array
     */
    public function findCustomerQuoteData(string $customerReference): array;

    /**
     * @param int $idQuote
     * @param string $customerReference
     *
     * @return bool
     */
    public function isQuoteDefault(int $idQuote, string $customerReference): bool;
}
