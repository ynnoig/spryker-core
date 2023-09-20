<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business\Facade;

use Codeception\Test\Unit;
use SprykerTest\Zed\PriceProduct\PriceProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProduct
 * @group Business
 * @group Facade
 * @group FindPriceTypeByNameTest
 * Add your own group annotations below this line
 */
class FindPriceTypeByNameTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected PriceProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testFindPriceTypeByName(): void
    {
        // Arrange
        $priceTypeTransfer = $this->tester->havePriceType();

        // Act
        $foundPriceTypeTransfer = $this->tester->getFacade()->findPriceTypeByName($priceTypeTransfer->getName());

        // Assert
        $this->assertSame($priceTypeTransfer->getIdPriceType(), $foundPriceTypeTransfer->getIdPriceType());
    }
}
