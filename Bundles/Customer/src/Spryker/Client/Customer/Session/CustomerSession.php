<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Customer\Session;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Customer\Exception\EmptyCustomerTransferCacheException;
use Spryker\Client\Session\SessionClientInterface;
use Spryker\Shared\Customer\CustomerConfig;

class CustomerSession implements CustomerSessionInterface
{
    /**
     * @var string
     */
    public const SESSION_KEY = 'customer data';

    /**
     * @var \Spryker\Client\Session\SessionClientInterface
     */
    protected $sessionClient;

    /**
     * @var array<\Spryker\Client\Customer\Dependency\Plugin\CustomerSessionGetPluginInterface>
     */
    protected $customerSessionGetPlugins;

    /**
     * @var array<\Spryker\Client\Customer\Dependency\Plugin\CustomerSessionSetPluginInterface>
     */
    protected $customerSessionSetPlugins;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected static $customerTransferCache;

    /**
     * @param \Spryker\Client\Session\SessionClientInterface $sessionClient
     * @param array<\Spryker\Client\Customer\Dependency\Plugin\CustomerSessionGetPluginInterface> $customerSessionGetPlugins
     * @param array<\Spryker\Client\Customer\Dependency\Plugin\CustomerSessionSetPluginInterface> $customerSessionSetPlugins
     */
    public function __construct(
        SessionClientInterface $sessionClient,
        array $customerSessionGetPlugins = [],
        array $customerSessionSetPlugins = []
    ) {
        $this->sessionClient = $sessionClient;
        $this->customerSessionGetPlugins = $customerSessionGetPlugins;
        $this->customerSessionSetPlugins = $customerSessionSetPlugins;
    }

    /**
     * @return void
     */
    public function logout()
    {
        $this->sessionClient->remove(static::SESSION_KEY);
        $this->invalidateCustomerTransferCache();
    }

    /**
     * @return bool
     */
    public function hasCustomer()
    {
        return $this->hasCustomerTransferCache() || $this->sessionClient->has(static::SESSION_KEY);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function getCustomer()
    {
        if ($this->hasCustomerTransferCache()) {
            return $this->getCustomerTransferCache();
        }

        $customerTransfer = $this->sessionClient->get(static::SESSION_KEY);

        if ($customerTransfer === null) {
            return null;
        }

        foreach ($this->customerSessionGetPlugins as $customerSessionGetPlugin) {
            $customerSessionGetPlugin->execute($customerTransfer);
        }

        $this->cacheCustomerTransfer($customerTransfer);

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function setCustomer(CustomerTransfer $customerTransfer)
    {
        $this->sessionClient->set(
            static::SESSION_KEY,
            $customerTransfer,
        );

        foreach ($this->customerSessionSetPlugins as $customerSessionSetPlugin) {
            $customerSessionSetPlugin->execute($customerTransfer);
        }

        $this->invalidateCustomerTransferCache();

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function setCustomerRawData(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $this->sessionClient->set(
            static::SESSION_KEY,
            $customerTransfer,
        );

        $this->invalidateCustomerTransferCache();

        return $customerTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function findCustomerRawData(): ?CustomerTransfer
    {
        $customerTransfer = $this->sessionClient->get(static::SESSION_KEY);

        if ($customerTransfer === null) {
            return null;
        }

        return $customerTransfer;
    }

    /**
     * @return void
     */
    public function markCustomerAsDirty()
    {
        if ($this->hasCustomer() !== false) {
            $this->getCustomer()->setIsDirty(true);
            $this->invalidateCustomerTransferCache();
        }
    }

    /**
     * @return bool
     */
    protected function hasCustomerTransferCache(): bool
    {
        return static::$customerTransferCache !== null;
    }

    /**
     * @throws \Spryker\Client\Customer\Exception\EmptyCustomerTransferCacheException
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomerTransferCache(): CustomerTransfer
    {
        if (static::$customerTransferCache === null) {
            throw new EmptyCustomerTransferCacheException();
        }

        return static::$customerTransferCache;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function cacheCustomerTransfer(CustomerTransfer $customerTransfer): void
    {
        static::$customerTransferCache = $customerTransfer;
    }

    /**
     * @return void
     */
    protected function invalidateCustomerTransferCache(): void
    {
        static::$customerTransferCache = null;
    }

    /**
     * @return string
     */
    public function getUserIdentifier(): string
    {
        $customerTransfer = $this->getCustomer();

        return $customerTransfer ? $customerTransfer->getCustomerReference() : $this->sessionClient->get(CustomerConfig::ANONYMOUS_SESSION_KEY, '');
    }
}
