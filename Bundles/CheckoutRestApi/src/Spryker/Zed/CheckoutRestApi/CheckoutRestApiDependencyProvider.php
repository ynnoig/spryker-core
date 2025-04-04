<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi;

use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCalculationFacadeBridge;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartFacadeBridge;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartsRestApiFacadeBridge;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCheckoutFacadeBridge;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCustomerFacadeBridge;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToPaymentFacadeBridge;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToQuoteFacadeBridge;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToShipmentFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CheckoutRestApi\CheckoutRestApiConfig getConfig()
 */
class CheckoutRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_CART = 'FACADE_CART';

    /**
     * @var string
     */
    public const FACADE_CARTS_REST_API = 'FACADE_CARTS_REST_API';

    /**
     * @var string
     */
    public const FACADE_CHECKOUT = 'FACADE_CHECKOUT';

    /**
     * @var string
     */
    public const FACADE_CUSTOMER = 'FACADE_CUSTOMER';

    /**
     * @var string
     */
    public const FACADE_PAYMENT = 'FACADE_PAYMENT';

    /**
     * @var string
     */
    public const FACADE_QUOTE = 'FACADE_QUOTE';

    /**
     * @var string
     */
    public const FACADE_SHIPMENT = 'FACADE_SHIPMENT';

    /**
     * @var string
     */
    public const FACADE_CALCULATION = 'FACADE_CALCULATION';

    /**
     * @var string
     */
    public const PLUGINS_QUOTE_MAPPER = 'PLUGINS_QUOTE_MAPPER';

    /**
     * @var string
     */
    public const PLUGINS_CHECKOUT_DATA_VALIDATOR = 'PLUGINS_CHECKOUT_DATA_VALIDATOR';

    /**
     * @var string
     */
    public const PLUGINS_CHECKOUT_DATA_VALIDATOR_FOR_ORDER_AMENDMENT = 'PLUGINS_CHECKOUT_DATA_VALIDATOR_FOR_ORDER_AMENDMENT';

    /**
     * @var string
     */
    public const PLUGINS_READ_CHECKOUT_DATA_VALIDATOR = 'PLUGINS_READ_CHECKOUT_DATA_VALIDATOR';

    /**
     * @var string
     */
    public const PLUGINS_CHECKOUT_DATA_EXPANDER = 'PLUGINS_CHECKOUT_DATA_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addCartFacade($container);
        $container = $this->addCartsRestApiFacade($container);
        $container = $this->addCheckoutFacade($container);
        $container = $this->addCustomerFacade($container);
        $container = $this->addPaymentFacade($container);
        $container = $this->addQuoteFacade($container);
        $container = $this->addShipmentFacade($container);
        $container = $this->addCalculationFacade($container);
        $container = $this->addQuoteMapperPlugins($container);
        $container = $this->addCheckoutDataValidatorPlugins($container);
        $container = $this->addCheckoutDataValidatorPluginsForOrderAmendment($container);
        $container = $this->addReadCheckoutDataValidatorPlugins($container);
        $container = $this->addCheckoutDataExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartFacade(Container $container): Container
    {
        $container->set(static::FACADE_CART, function (Container $container) {
            return new CheckoutRestApiToCartFacadeBridge($container->getLocator()->cart()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartsRestApiFacade(Container $container): Container
    {
        $container->set(static::FACADE_CARTS_REST_API, function (Container $container) {
            return new CheckoutRestApiToCartsRestApiFacadeBridge($container->getLocator()->cartsRestApi()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCheckoutFacade(Container $container): Container
    {
        $container->set(static::FACADE_CHECKOUT, function (Container $container) {
            return new CheckoutRestApiToCheckoutFacadeBridge($container->getLocator()->checkout()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerFacade(Container $container): Container
    {
        $container->set(static::FACADE_CUSTOMER, function (Container $container) {
            return new CheckoutRestApiToCustomerFacadeBridge($container->getLocator()->customer()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPaymentFacade(Container $container): Container
    {
        $container->set(static::FACADE_PAYMENT, function (Container $container) {
            return new CheckoutRestApiToPaymentFacadeBridge($container->getLocator()->payment()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteFacade(Container $container): Container
    {
        $container->set(static::FACADE_QUOTE, function (Container $container) {
            return new CheckoutRestApiToQuoteFacadeBridge($container->getLocator()->quote()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addShipmentFacade(Container $container): Container
    {
        $container->set(static::FACADE_SHIPMENT, function (Container $container) {
            return new CheckoutRestApiToShipmentFacadeBridge($container->getLocator()->shipment()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCalculationFacade(Container $container): Container
    {
        $container->set(static::FACADE_CALCULATION, function (Container $container) {
            return new CheckoutRestApiToCalculationFacadeBridge($container->getLocator()->calculation()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteMapperPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_QUOTE_MAPPER, function () {
            return $this->getQuoteMapperPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCheckoutDataValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CHECKOUT_DATA_VALIDATOR, function () {
            return $this->getCheckoutDataValidatorPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCheckoutDataValidatorPluginsForOrderAmendment(Container $container): Container
    {
        $container->set(static::PLUGINS_CHECKOUT_DATA_VALIDATOR_FOR_ORDER_AMENDMENT, function () {
            return $this->getCheckoutDataValidatorPluginsForOrderAmendment();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addReadCheckoutDataValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_READ_CHECKOUT_DATA_VALIDATOR, function () {
            return $this->getReadCheckoutDataValidatorPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCheckoutDataExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CHECKOUT_DATA_EXPANDER, function () {
            return $this->getCheckoutDataExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface>
     */
    protected function getQuoteMapperPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataValidatorPluginInterface>
     */
    protected function getCheckoutDataValidatorPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataValidatorPluginInterface>
     */
    protected function getCheckoutDataValidatorPluginsForOrderAmendment(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\ReadCheckoutDataValidatorPluginInterface>
     */
    protected function getReadCheckoutDataValidatorPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataExpanderPluginInterface>
     */
    protected function getCheckoutDataExpanderPlugins(): array
    {
        return [];
    }
}
