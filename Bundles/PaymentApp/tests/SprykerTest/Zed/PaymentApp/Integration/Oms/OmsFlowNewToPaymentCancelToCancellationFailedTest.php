<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\PaymentApp\Integration\Oms;

use Codeception\Test\Unit;
use SprykerTest\Zed\PaymentApp\PaymentAppIntegrationTester;
use SprykerTest\Zed\SalesPayment\Helper\SalesPaymentHelper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PaymentApp
 * @group Integration
 * @group Oms
 * @group OmsFlowNewToPaymentCancelToCancellationFailedTest
 * Add your own group annotations below this line
 */
class OmsFlowNewToPaymentCancelToCancellationFailedTest extends Unit
{
    protected PaymentAppIntegrationTester $tester;

    /**
     * @return void
     */
    public function testMoveAnItemFromNewToPaymentCancelToCancellationFailed(): void
    {
        $this->tester->haveOrderItemInState(SalesPaymentHelper::STATE_NEW);
        $this->tester->receivePaymentAuthorizedMessage();

        $this->tester->tryToTransitionOrderItems();
        $this->tester->assertOrderItemIsInState(SalesPaymentHelper::STATE_PAYMENT_AUTHORIZED);

        $this->tester->tryToTransitionOrderItems(SalesPaymentHelper::EVENT_CANCEL);
        $this->tester->assertOrderItemIsInState(SalesPaymentHelper::STATE_PAYMENT_CANCELLATION_PENDING);

        $this->tester->receivePaymentCancellationFailedMessage();

        $this->tester->tryToTransitionOrderItems();
        $this->tester->assertOrderItemIsInState(SalesPaymentHelper::STATE_PAYMENT_CANCELLATION_FAILED);
    }
}
