<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityStorage;

use Spryker\AvailabilityStorage\src\Spryker\Client\AvailabilityStorage\Expander\ProductViewAvailabilityExpander;
use Spryker\AvailabilityStorage\src\Spryker\Client\AvailabilityStorage\Expander\ProductViewAvailabilityExpanderInterface;
use Spryker\Client\AvailabilityStorage\Storage\AvailabilityStorageReader;
use Spryker\Client\Kernel\AbstractFactory;

class AvailabilityStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\AvailabilityStorage\Storage\AvailabilityStorageReaderInterface
     */
    public function createAvailabilityStorageReader()
    {
        return new AvailabilityStorageReader($this->getStorage(), $this->getSynchronizationService());
    }

    /**
     * @return \Spryker\AvailabilityStorage\src\Spryker\Client\AvailabilityStorage\Expander\ProductViewAvailabilityExpanderInterface
     */
    public function createProductViewAvailabilityExpander(): ProductViewAvailabilityExpanderInterface
    {
        return new ProductViewAvailabilityExpander(
            $this->createAvailabilityStorageReader(),
            $this->getPostProductViewAvailabilityStorageExpandPlugins()
        );
    }

    /**
     * @return \Spryker\Client\AvailabilityStorageExtension\Dependency\Plugin\PostProductViewAvailabilityStorageExpandPluginInterface[]
     */
    public function getPostProductViewAvailabilityStorageExpandPlugins(): array
    {
        return $this->getProvidedDependency(AvailabilityStorageDependencyProvider::PLUGINS_POST_PRODUCT_VIEW_AVAILABILITY_EXPAND);
    }

    /**
     * @return \Spryker\Client\AvailabilityStorage\Dependency\Client\AvailabilityStorageToStorageClientInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(AvailabilityStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\AvailabilityStorage\Dependency\Service\AvailabilityStorageToSynchronizationServiceBridge
     */
    protected function getSynchronizationService()
    {
        return $this->getProvidedDependency(AvailabilityStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
