<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Reader;

use Generated\Shared\Transfer\OrderTransfer;

interface OrderReaderInterface
{
    /**
     * @param string $orderReference
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function findCustomerOrder(string $orderReference, string $customerReference): ?OrderTransfer;
}
