<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesConfigurableBundle\Business\Deleter\SalesOrderConfiguredBundleItemDeleter;
use Spryker\Zed\SalesConfigurableBundle\Business\Deleter\SalesOrderConfiguredBundleItemDeleterInterface;
use Spryker\Zed\SalesConfigurableBundle\Business\Expander\OrderItemExpander;
use Spryker\Zed\SalesConfigurableBundle\Business\Expander\OrderItemExpanderInterface;
use Spryker\Zed\SalesConfigurableBundle\Business\Expander\SalesOrderConfiguredBundleExpander;
use Spryker\Zed\SalesConfigurableBundle\Business\Expander\SalesOrderConfiguredBundleExpanderInterface;
use Spryker\Zed\SalesConfigurableBundle\Business\Extractor\ConfigurableBundleItemExtractor;
use Spryker\Zed\SalesConfigurableBundle\Business\Extractor\ConfigurableBundleItemExtractorInterface;
use Spryker\Zed\SalesConfigurableBundle\Business\Hydrator\CartReorderItemHydrator;
use Spryker\Zed\SalesConfigurableBundle\Business\Hydrator\CartReorderItemHydratorInterface;
use Spryker\Zed\SalesConfigurableBundle\Business\Merger\CartReorderItemMerger;
use Spryker\Zed\SalesConfigurableBundle\Business\Merger\CartReorderItemMergerInterface;
use Spryker\Zed\SalesConfigurableBundle\Business\Transformer\ConfigurableBundleItemTransformer;
use Spryker\Zed\SalesConfigurableBundle\Business\Transformer\ConfigurableBundleItemTransformerInterface;
use Spryker\Zed\SalesConfigurableBundle\Business\Writer\SalesOrderConfiguredBundleWriter;
use Spryker\Zed\SalesConfigurableBundle\Business\Writer\SalesOrderConfiguredBundleWriterInterface;
use Spryker\Zed\SalesConfigurableBundle\Dependency\Facade\SalesConfigurableBundleToGlossaryFacadeInterface;
use Spryker\Zed\SalesConfigurableBundle\Dependency\Service\SalesConfigurableBundleToConfigurableBundleServiceInterface;
use Spryker\Zed\SalesConfigurableBundle\SalesConfigurableBundleDependencyProvider;

/**
 * @method \Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesConfigurableBundle\SalesConfigurableBundleConfig getConfig()
 */
class SalesConfigurableBundleBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesConfigurableBundle\Business\Writer\SalesOrderConfiguredBundleWriterInterface
     */
    public function createSalesOrderConfiguredBundleWriter(): SalesOrderConfiguredBundleWriterInterface
    {
        return new SalesOrderConfiguredBundleWriter(
            $this->getEntityManager(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesConfigurableBundle\Business\Expander\SalesOrderConfiguredBundleExpanderInterface
     */
    public function createSalesOrderConfiguredBundleExpander(): SalesOrderConfiguredBundleExpanderInterface
    {
        return new SalesOrderConfiguredBundleExpander(
            $this->getRepository(),
            $this->getGlossaryFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesConfigurableBundle\Business\Transformer\ConfigurableBundleItemTransformerInterface
     */
    public function createConfigurableBundleItemTransformer(): ConfigurableBundleItemTransformerInterface
    {
        return new ConfigurableBundleItemTransformer();
    }

    /**
     * @return \Spryker\Zed\SalesConfigurableBundle\Business\Expander\OrderItemExpanderInterface
     */
    public function createOrderItemExpander(): OrderItemExpanderInterface
    {
        return new OrderItemExpander(
            $this->getRepository(),
            $this->getGlossaryFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesConfigurableBundle\Business\Merger\CartReorderItemMergerInterface
     */
    public function createCartReorderItemMerger(): CartReorderItemMergerInterface
    {
        return new CartReorderItemMerger($this->createConfigurableBundleItemExtractor());
    }

    /**
     * @return \Spryker\Zed\SalesConfigurableBundle\Business\Hydrator\CartReorderItemHydratorInterface
     */
    public function createCartReorderItemHydrator(): CartReorderItemHydratorInterface
    {
        return new CartReorderItemHydrator(
            $this->createConfigurableBundleItemExtractor(),
            $this->getConfigurableBundleService(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesConfigurableBundle\Business\Extractor\ConfigurableBundleItemExtractorInterface
     */
    public function createConfigurableBundleItemExtractor(): ConfigurableBundleItemExtractorInterface
    {
        return new ConfigurableBundleItemExtractor();
    }

    /**
     * @return \Spryker\Zed\SalesConfigurableBundle\Business\Deleter\SalesOrderConfiguredBundleItemDeleterInterface
     */
    public function createSalesOrderConfiguredBundleItemDeleter(): SalesOrderConfiguredBundleItemDeleterInterface
    {
        return new SalesOrderConfiguredBundleItemDeleter(
            $this->getEntityManager(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesConfigurableBundle\Dependency\Facade\SalesConfigurableBundleToGlossaryFacadeInterface
     */
    protected function getGlossaryFacade(): SalesConfigurableBundleToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(SalesConfigurableBundleDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\SalesConfigurableBundle\Dependency\Service\SalesConfigurableBundleToConfigurableBundleServiceInterface
     */
    public function getConfigurableBundleService(): SalesConfigurableBundleToConfigurableBundleServiceInterface
    {
        return $this->getProvidedDependency(SalesConfigurableBundleDependencyProvider::SERVICE_CONFIGURABLE_BUNDLE);
    }
}
