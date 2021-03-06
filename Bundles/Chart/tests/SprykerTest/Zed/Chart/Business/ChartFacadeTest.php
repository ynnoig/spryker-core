<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Chart\Business;

use Codeception\Test\Unit;
use Spryker\Zed\Chart\Business\ChartFacade;
use Spryker\Zed\Chart\ChartConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Chart
 * @group Business
 * @group Facade
 * @group ChartFacadeTest
 * Add your own group annotations below this line
 */
class ChartFacadeTest extends Unit
{
    /**
     * @var \Spryker\Zed\Chart\Business\ChartFacade
     */
    protected $chartFacade;

    /**
     * @var \Spryker\Zed\Chart\ChartConfig
     */
    protected $chartConfig;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->chartFacade = new ChartFacade();
        $this->chartConfig = new ChartConfig();
    }

    /**
     * @return void
     */
    public function testDefaultChartType(): void
    {
        $defaultType = $this->chartFacade->getDefaultChartType();

        $this->assertSame($defaultType, $this->chartConfig->getDefaultChartType());
    }

    /**
     * @return void
     */
    public function testChartTypes(): void
    {
        $types = $this->chartFacade->getChartTypes();

        $this->assertSame($types, $this->chartConfig->getChartTypes());
    }
}
