<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ContentProductSetDataImport\Dependency\Facade;

use Generated\Shared\Transfer\ContentTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;

interface ContentProductSetDataImportToContentInterface
{
    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     *
     * @return \Generated\Shared\Transfer\ContentValidationResponseTransfer
     */
    public function validateContent(ContentTransfer $contentTransfer): ContentValidationResponseTransfer;
}
