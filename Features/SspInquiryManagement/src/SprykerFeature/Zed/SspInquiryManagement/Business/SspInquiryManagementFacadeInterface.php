<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;

interface SspInquiryManagementFacadeInterface
{
    /**
     * Specification:
     * - Creates ssp inquiries.
     * - Executes a stack of `\SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PreCreate\SspInquiryPreCreateHookInterface`.
     * - Executes a stack of `\SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PostCreate\SspInquiryPostCreateHookInterface`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer
     */
    public function createSspInquiryCollection(
        SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer
    ): SspInquiryCollectionResponseTransfer;

    /**
     * Specification:
     * - Finds ssp inquiries by criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function getSspInquiryCollection(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer;

    /**
     * Specification:
     * - Cancels ssp inquiries from provided collection.
     * - Requires SspInquiryCollectionRequestTransfer.sspInquiries.reference to be provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer
     */
    public function cancelSspInquiryCollection(
        SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer
    ): SspInquiryCollectionResponseTransfer;

    /**
     * Specification:
     * - Imports Ssp Inquiry.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importSspInquiry(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer): DataImporterReportTransfer;
}
