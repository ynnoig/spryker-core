<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUser\Business\Reader;

use Generated\Shared\Transfer\CompanyUserAccessTokenRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

interface CompanyUserAccessTokenReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserAccessTokenRequestTransfer $companyUserAccessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerByAccessToken(CompanyUserAccessTokenRequestTransfer $companyUserAccessTokenRequestTransfer): CustomerTransfer;
}
