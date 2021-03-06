<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantShipment\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\MerchantShipmentCriteriaTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

interface MerchantShipmentFacadeInterface
{
    /**
     * Specification:
     * - Finds Shipment by provided params in MerchantShipmentCriteria.
     * - Returns Shipment transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantShipmentCriteriaTransfer $merchantShipmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer|null
     */
    public function findShipment(MerchantShipmentCriteriaTransfer $merchantShipmentCriteriaTransfer): ?ShipmentTransfer;

    /**
     * Specification:
     * - Expects `cartChange.items.shipment` to be set.
     * - Expands `cartChange.items.shipment` transfer object with merchant reference.
     * - Uses `merchantReference` from `cartChange.items`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartChangeShipmentWithMerchantReference(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;

    /**
     * Specification:
     * - Expects `quote.items.shipment` to be set.
     * - Expands `quote.items.shipment` transfer object with merchant reference.
     * - Uses `merchantReference` from `quote.items`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteShipmentWithMerchantReference(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
