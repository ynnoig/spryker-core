<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Handler;

use Exception;
use Generated\Shared\Transfer\CategoryResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface;

class CategoryDeleteFormHandler implements CategoryDeleteFormHandlerInterface
{
    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @param \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface $categoryFacade
     */
    public function __construct(CategoryGuiToCategoryFacadeInterface $categoryFacade)
    {
        $this->categoryFacade = $categoryFacade;
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryResponseTransfer
     */
    public function deleteCategory(int $idCategory): CategoryResponseTransfer
    {
        $categoryResponseTransfer = (new CategoryResponseTransfer())
            ->setIsSuccessful(true);

        try {
            $this->categoryFacade->delete($idCategory);
            $categoryResponseTransfer
                ->addMessage((new MessageTransfer())->setValue('The category was deleted successfully.'));
        } catch (Exception $e) {
            $categoryResponseTransfer
                ->addMessage((new MessageTransfer())->setValue($e->getMessage()))
                ->setIsSuccessful(false);
        }

        return $categoryResponseTransfer;
    }
}
