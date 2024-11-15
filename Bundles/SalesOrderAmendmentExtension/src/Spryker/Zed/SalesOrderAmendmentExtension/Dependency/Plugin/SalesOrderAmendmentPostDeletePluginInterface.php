<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SalesOrderAmendmentTransfer;

/**
 * Implement this plugin interface to add logic after a sales order amendment is deleted.
 */
interface SalesOrderAmendmentPostDeletePluginInterface
{
    /**
     * Specification:
     * - Executed after a sales order amendment is deleted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentTransfer
     */
    public function postDelete(SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer): SalesOrderAmendmentTransfer;
}
