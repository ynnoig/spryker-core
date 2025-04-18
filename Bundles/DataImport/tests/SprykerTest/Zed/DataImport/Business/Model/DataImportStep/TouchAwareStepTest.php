<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business\Model\DataImportStep;

use Codeception\Test\Unit;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\TouchAwareStep;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToTouchBridge;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToTouchInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group DataImportStep
 * @group TouchAwareStepTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Zed\DataImport\DataImportBusinessTester $tester
 */
class TouchAwareStepTest extends Unit
{
    /**
     * @var string
     */
    public const MAIN_TOUCHABLE_KEY = 'main touchable key';

    /**
     * @var string
     */
    public const SUB_TOUCHABLE_KEY_A = 'sub touchable key a';

    /**
     * @var string
     */
    public const SUB_TOUCHABLE_KEY_B = 'sub touchable key b';

    /**
     * @return void
     */
    public function testAfterExecuteWillReturnIfNoTouchableApplied(): void
    {
        $touchAwareStep = new TouchAwareStep($this->getTouchFacadeMock(0));

        $touchAwareStep->afterExecute();
    }

    /**
     * @dataProvider noBulkSizeDataProvider
     *
     * @param string $method
     * @param string $itemEvent
     *
     * @return void
     */
    public function testAfterExecuteTriggersTouchForMainTouchableWhenBulkSizeNotSet(string $method, string $itemEvent): void
    {
        $touchAwareStep = new TouchAwareStep($this->getTouchFacadeMock(1, $method));
        $touchAwareStep->addMainTouchable(static::MAIN_TOUCHABLE_KEY, 1, $itemEvent);

        $touchAwareStep->afterExecute();
    }

    /**
     * @return array
     */
    public function noBulkSizeDataProvider(): array
    {
        return [
            ['bulkTouchSetActive', SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE],
            ['bulkTouchSetInActive', SpyTouchTableMap::COL_ITEM_EVENT_INACTIVE],
            ['bulkTouchSetDeleted', SpyTouchTableMap::COL_ITEM_EVENT_DELETED],
        ];
    }

    /**
     * @return void
     */
    public function testAfterExecuteTriggersTouchForMainTouchableWhenBulkSizeOne(): void
    {
        $touchAwareStep = new TouchAwareStep($this->getTouchFacadeMock(), 1);
        $touchAwareStep->addMainTouchable(static::MAIN_TOUCHABLE_KEY, 1);

        $touchAwareStep->afterExecute();
    }

    /**
     * @return void
     */
    public function testAfterExecuteTriggersTouchWhenMainTouchableCountEqualsBulkSize(): void
    {
        $touchAwareStep = new TouchAwareStep($this->getTouchFacadeMock(), 2);
        $touchAwareStep->addMainTouchable(static::MAIN_TOUCHABLE_KEY, 1);
        $touchAwareStep->addMainTouchable(static::MAIN_TOUCHABLE_KEY, 2);

        $touchAwareStep->afterExecute();
    }

    /**
     * @return void
     */
    public function testAfterExecuteTriggersTouchForSubTouchableWhenMainTouchableCountEqualsBulkSize(): void
    {
        $touchAwareStep = new TouchAwareStep($this->getTouchFacadeMock(2), 2);
        $touchAwareStep->addMainTouchable(static::MAIN_TOUCHABLE_KEY, 1);
        $touchAwareStep->addSubTouchable(static::SUB_TOUCHABLE_KEY_A, 1);
        $touchAwareStep->addSubTouchable(static::SUB_TOUCHABLE_KEY_A, 2);

        $touchAwareStep->addMainTouchable(static::MAIN_TOUCHABLE_KEY, 2);
        $touchAwareStep->addSubTouchable(static::SUB_TOUCHABLE_KEY_A, 3);
        $touchAwareStep->addSubTouchable(static::SUB_TOUCHABLE_KEY_A, 4);

        $touchAwareStep->afterExecute();
    }

    /**
     * @return void
     */
    public function testAfterExecuteTriggersTouchForEachSubTouchableWhenMainTouchableCountEqualsBulkSize(): void
    {
        $touchAwareStep = new TouchAwareStep($this->getTouchFacadeMock(3), 2);
        $touchAwareStep->addMainTouchable(static::MAIN_TOUCHABLE_KEY, 1);
        $touchAwareStep->addSubTouchable(static::SUB_TOUCHABLE_KEY_A, 1);
        $touchAwareStep->addSubTouchable(static::SUB_TOUCHABLE_KEY_B, 2);

        $touchAwareStep->addMainTouchable(static::MAIN_TOUCHABLE_KEY, 2);
        $touchAwareStep->addSubTouchable(static::SUB_TOUCHABLE_KEY_A, 2);
        $touchAwareStep->addSubTouchable(static::SUB_TOUCHABLE_KEY_B, 2);

        $touchAwareStep->afterExecute();
    }

    /**
     * @param int $calledCount
     * @param string $method
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\DataImport\Dependency\Facade\DataImportToTouchInterface
     */
    private function getTouchFacadeMock(int $calledCount = 1, string $method = 'bulkTouchSetActive'): DataImportToTouchInterface
    {
        $mockBuilder = $this->getMockBuilder(DataImportToTouchInterface::class)
            ->onlyMethods(['bulkTouchSetActive', 'bulkTouchSetInactive', 'bulkTouchSetDeleted']);

        $dataImportToTouchInterfaceMock = $mockBuilder->getMock();
        $dataImportToTouchInterfaceMock->expects($this->exactly($calledCount))->method($method);

        $dataImportToTouchBridge = new DataImportToTouchBridge($dataImportToTouchInterfaceMock);

        return $dataImportToTouchBridge;
    }
}
