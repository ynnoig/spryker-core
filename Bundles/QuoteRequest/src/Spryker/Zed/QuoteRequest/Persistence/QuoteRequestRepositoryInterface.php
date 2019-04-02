<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Persistence;

use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;

interface QuoteRequestRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    public function getQuoteRequestCollectionByFilter(
        QuoteRequestFilterTransfer $quoteRequestFilterTransfer
    ): QuoteRequestCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer $quoteRequestVersionFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionCollectionTransfer
     */
    public function getQuoteRequestVersionCollectionByFilter(
        QuoteRequestVersionFilterTransfer $quoteRequestVersionFilterTransfer
    ): QuoteRequestVersionCollectionTransfer;

    /**
     * @param int $idCompanyUser
     *
     * @return int
     */
    public function countCustomerQuoteRequests(int $idCompanyUser): int;

    /**
     * @param int $idQuoteRequest
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer|null
     */
    public function findQuoteRequestLatestVisibleVersion(int $idQuoteRequest): ?QuoteRequestVersionTransfer;
}
