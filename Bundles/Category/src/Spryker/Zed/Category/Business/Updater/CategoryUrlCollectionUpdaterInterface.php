<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Updater;

use Generated\Shared\Transfer\CategoryUrlCollectionRequestTransfer;
use Generated\Shared\Transfer\CategoryUrlCollectionResponseTransfer;

interface CategoryUrlCollectionUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryUrlCollectionRequestTransfer $categoryUrlCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryUrlCollectionResponseTransfer
     */
    public function updateCategoryUrlCollection(
        CategoryUrlCollectionRequestTransfer $categoryUrlCollectionRequestTransfer
    ): CategoryUrlCollectionResponseTransfer;
}
