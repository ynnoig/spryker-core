<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

use InvalidArgumentException;
use Laminas\Config\Factory;
use Laminas\Filter\FilterChain;
use Laminas\Filter\Word\CamelCaseToUnderscore;
use Laminas\Filter\Word\DashToCamelCase;
use Laminas\Filter\Word\UnderscoreToCamelCase;

class TransferDefinitionLoader extends AbstractTransferDefinitionLoader
{
    /**
     * @var string
     */
    public const KEY_TRANSFER = 'transfer';

    /**
     * @var string
     */
    public const TRANSFER_SCHEMA_SUFFIX = '.transfer.xml';

    /**
     * @var string
     */
    protected const KEY_IS_CORE = 'is_core';

    /**
     * @var \Laminas\Filter\FilterChain
     */
    protected static $filter;

    /**
     * @return void
     */
    protected function loadDefinitions(): void
    {
        $xmlTransferDefinitions = $this->finder->getXmlTransferDefinitionFiles();
        foreach ($xmlTransferDefinitions as $xmlTransferDefinition) {
            $bundle = $this->getBundleFromPathName($xmlTransferDefinition->getFilename());
            $containingBundle = $this->getContainingBundleFromPathName($xmlTransferDefinition->getPathname());
            /** @var \Laminas\Config\Config $configObject */
            $configObject = Factory::fromFile($xmlTransferDefinition->getPathname(), true);
            $definition = $configObject->toArray();
            $isCore = str_starts_with($xmlTransferDefinition->getPathname(), $this->transferConfig->getApplicationVendorDirectory());

            $this->addDefinition($definition, $bundle, $containingBundle, $isCore);
        }

        usort($this->transferDefinitions, function ($a, $b) {
            return $a[static::KEY_IS_CORE] <=> $b[static::KEY_IS_CORE];
        });
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    protected function getBundleFromPathName(string $fileName): string
    {
        $filterChain = new FilterChain();
        $filterChain
            ->attach(new UnderscoreToCamelCase())
            ->attach(new DashToCamelCase());

        return $filterChain->filter(str_replace(static::TRANSFER_SCHEMA_SUFFIX, '', $fileName));
    }

    /**
     * @param array<string, mixed> $definition
     * @param string $module
     * @param string $containingModule
     * @param bool $isCore
     *
     * @return void
     */
    protected function addDefinition(array $definition, string $module, string $containingModule, bool $isCore): void
    {
        if (isset($definition[static::KEY_TRANSFER][0])) {
            foreach ($definition[static::KEY_TRANSFER] as $transfer) {
                $this->assertCasing($transfer, $module);

                $transfer[static::KEY_BUNDLE] = $module;
                $transfer[static::KEY_IS_CORE] = $isCore;
                $transfer[static::KEY_CONTAINING_BUNDLE] = $containingModule;

                $transfer = $this->normalize($transfer);
                $this->transferDefinitions[] = $transfer;
            }
        } else {
            $transfer = $definition[static::KEY_TRANSFER];
            $this->assertCasing($transfer, $module);

            $transfer[static::KEY_BUNDLE] = $module;
            $transfer[static::KEY_IS_CORE] = $isCore;
            $transfer[static::KEY_CONTAINING_BUNDLE] = $containingModule;

            $transfer = $this->normalize($transfer);
            $this->transferDefinitions[] = $transfer;
        }
    }

    /**
     * @param array<string, mixed> $transfer
     * @param string $bundle
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    protected function assertCasing(array $transfer, string $bundle): void
    {
        $name = $transfer['name'];

        $filteredName = $this->getFilter()->filter($name);

        if ($name !== $filteredName) {
            throw new InvalidArgumentException(
                sprintf(
                    'Transfer name `%s` does not match expected name `%s` for module `%s`',
                    $name,
                    $filteredName,
                    $bundle,
                ),
            );
        }
    }

    /**
     * @return \Laminas\Filter\FilterChain
     */
    protected function getFilter(): FilterChain
    {
        if (static::$filter === null) {
            $filter = new FilterChain();
            $filter->attach(new CamelCaseToUnderscore());
            $filter->attach(new UnderscoreToCamelCase());

            static::$filter = $filter;
        }

        return static::$filter;
    }

    /**
     * We need to shim casing issues for property names or singular names for BC reasons.
     *
     * @param array<string, mixed> $transfer
     *
     * @return array<string, mixed>
     */
    protected function normalize(array $transfer): array
    {
        if (empty($transfer['property'])) {
            return $transfer;
        }

        if (isset($transfer['property'][0])) {
            foreach ($transfer['property'] as $key => $property) {
                $transfer['property'][$key]['name'] = lcfirst($property['name']);
                if (!empty($property['singular'])) {
                    $transfer['property'][$key]['singular'] = lcfirst($property['singular']);
                }
            }

            return $transfer;
        }

        $transfer['property']['name'] = lcfirst($transfer['property']['name']);
        if (!empty($transfer['property']['singular'])) {
            $transfer['property']['singular'] = lcfirst($transfer['property']['singular']);
        }

        return $transfer;
    }
}
