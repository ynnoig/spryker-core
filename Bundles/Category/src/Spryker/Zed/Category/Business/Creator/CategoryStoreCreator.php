<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Creator;

use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface;
use Spryker\Zed\Category\Persistence\CategoryRepositoryInterface;

class CategoryStoreCreator implements CategoryStoreCreatorInterface
{
    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface
     */
    protected $categoryEntityManager;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface $categoryRepository
     * @param \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface $categoryEntityManager
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        CategoryEntityManagerInterface $categoryEntityManager
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->categoryEntityManager = $categoryEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function createCategoryStoreRelations(CategoryTransfer $categoryTransfer): void
    {
        if (!$categoryTransfer->getStoreRelation()) {
            return;
        }

        $storeIdsToAdd = $categoryTransfer->getStoreRelation()->getIdStores();
        if ($categoryTransfer->getParentCategoryNode()) {
            $storeIdsToAdd = $this->filterOutStoreIdsMissingInParentCategoryStoreRelation(
                $categoryTransfer->getParentCategoryNode()->getFkCategoryOrFail(),
                $storeIdsToAdd
            );
        }

        $this->categoryEntityManager->createCategoryStoreRelationForStores(
            $categoryTransfer->getIdCategoryOrFail(),
            $storeIdsToAdd
        );
    }

    /**
     * @param int $parentIdCategory
     * @param int[] $storeIds
     *
     * @return int[]
     */
    protected function filterOutStoreIdsMissingInParentCategoryStoreRelation(int $parentIdCategory, array $storeIds): array
    {
        $parentStoreRelationTransfer = $this->categoryRepository->getCategoryStoreRelationByIdCategory($parentIdCategory);

        return array_intersect($parentStoreRelationTransfer->getIdStores(), $storeIds);
    }
}
