<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Persistence;

use Generated\Shared\Transfer\ServicePointAddressCollectionTransfer;
use Generated\Shared\Transfer\ServicePointAddressCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointCollectionTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;

interface ServicePointRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointCriteriaTransfer $servicePointCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointCollectionTransfer
     */
    public function getServicePointCollection(
        ServicePointCriteriaTransfer $servicePointCriteriaTransfer
    ): ServicePointCollectionTransfer;

    /**
     * @param list<string> $servicePointUuids
     *
     * @return array<string, int>
     */
    public function getServicePointIdsIndexedByServicePointUuid(array $servicePointUuids): array;

    /**
     * @param list<int> $servicePointIds
     *
     * @return array<int, list<\Generated\Shared\Transfer\StoreTransfer>>
     */
    public function getServicePointStoresGroupedByIdServicePoint(array $servicePointIds): array;

    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressCriteriaTransfer $servicePointAddressCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressCollectionTransfer
     */
    public function getServicePointAddressCollection(
        ServicePointAddressCriteriaTransfer $servicePointAddressCriteriaTransfer
    ): ServicePointAddressCollectionTransfer;
}
