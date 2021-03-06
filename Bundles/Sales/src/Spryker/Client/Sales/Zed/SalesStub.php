<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Sales\Zed;

use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\OrderCancelRequestTransfer;
use Generated\Shared\Transfer\OrderCancelResponseTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Generated\Shared\Transfer\OrderListRequestTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Client\ZedRequest\ZedRequestClient;

class SalesStub implements SalesStubInterface
{
    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClient
     */
    protected $zedStub;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClient $zedStub
     */
    public function __construct(ZedRequestClient $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOrders(OrderListTransfer $orderListTransfer)
    {
        /** @var \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer */
        $orderListTransfer = $this->zedStub->call('/sales/gateway/get-orders', $orderListTransfer);

        return $orderListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getPaginatedOrders(OrderListTransfer $orderListTransfer)
    {
        /** @var \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer */
        $orderListTransfer = $this->zedStub->call('/sales/gateway/get-paginated-orders', $orderListTransfer);

        return $orderListTransfer;
    }

    /**
     * @uses \Spryker\Zed\Sales\Communication\Controller\GatewayController::getOffsetPaginatedCustomerOrderListAction
     *
     * @param \Generated\Shared\Transfer\OrderListRequestTransfer $orderListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOffsetPaginatedCustomerOrderList(OrderListRequestTransfer $orderListRequestTransfer): OrderListTransfer
    {
        /** @var \Generated\Shared\Transfer\OrderListTransfer $orderListRequestTransfer */
        $orderListRequestTransfer = $this->zedStub->call('/sales/gateway/get-offset-paginated-customer-order-list', $orderListRequestTransfer);

        return $orderListRequestTransfer;
    }

    /**
     * @uses \Spryker\Zed\Sales\Communication\Controller\GatewayController::getPaginatedCustomerOrdersOverviewAction
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getPaginatedCustomerOrdersOverview(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        /** @var \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer */
        $orderListTransfer = $this->zedStub->call('/sales/gateway/get-paginated-customer-orders-overview', $orderListTransfer);

        return $orderListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderDetails(OrderTransfer $orderTransfer)
    {
        /** @var \Generated\Shared\Transfer\OrderTransfer $orderTransfer */
        $orderTransfer = $this->zedStub->call('/sales/gateway/get-order-details', $orderTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getCustomerOrderByOrderReference(OrderTransfer $orderTransfer): OrderTransfer
    {
        /** @var \Generated\Shared\Transfer\OrderTransfer $orderTransfer */
        $orderTransfer = $this->zedStub->call('/sales/gateway/get-customer-order-by-order-reference', $orderTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderItemFilterTransfer $orderItemFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function getOrderItems(OrderItemFilterTransfer $orderItemFilterTransfer): ItemCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\ItemCollectionTransfer $itemCollectionTransfer */
        $itemCollectionTransfer = $this->zedStub->call('/sales/gateway/get-order-items', $orderItemFilterTransfer);

        return $itemCollectionTransfer;
    }

    /**
     * @uses \Spryker\Zed\Sales\Communication\Controller\GatewayController::searchOrdersAction()
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function searchOrders(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        /** @var \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer */
        $orderListTransfer = $this->zedStub->call('/sales/gateway/search-orders', $orderListTransfer);

        return $orderListTransfer;
    }

    /**
     * @uses \Spryker\Zed\Sales\Communication\Controller\GatewayController::cancelOrderAction()
     *
     * @param \Generated\Shared\Transfer\OrderCancelRequestTransfer $orderCancelRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCancelResponseTransfer
     */
    public function cancelOrder(OrderCancelRequestTransfer $orderCancelRequestTransfer): OrderCancelResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\OrderCancelResponseTransfer $orderCancelResponseTransfer */
        $orderCancelResponseTransfer = $this->zedStub->call('/sales/gateway/cancel-order', $orderCancelRequestTransfer);

        return $orderCancelResponseTransfer;
    }
}
