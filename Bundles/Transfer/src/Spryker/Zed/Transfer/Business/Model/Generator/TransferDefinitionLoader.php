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

class TransferDefinitionLoader implements LoaderInterface
{
    public const KEY_BUNDLE = 'bundle';
    public const KEY_CONTAINING_BUNDLE = 'containing bundle';
    public const KEY_TRANSFER = 'transfer';
    public const TRANSFER_SCHEMA_SUFFIX = '.transfer.xml';

    /**
     * @var \Spryker\Zed\Transfer\Business\Model\Generator\FinderInterface
     */
    protected $finder;

    /**
     * @var \Spryker\Zed\Transfer\Business\Model\Generator\DefinitionNormalizerInterface
     */
    protected $definitionNormalizer;

    /**
     * @var array
     */
    protected $transferDefinitions = [];

    /**
     * @var \Laminas\Filter\FilterChain
     */
    protected static $filter;

    /**
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\FinderInterface $finder
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\DefinitionNormalizerInterface $normalizer
     */
    public function __construct(FinderInterface $finder, DefinitionNormalizerInterface $normalizer)
    {
        $this->finder = $finder;
        $this->definitionNormalizer = $normalizer;
    }

    /**
     * @return array
     */
    public function getDefinitions()
    {
        $this->loadDefinitions();
        $this->transferDefinitions = $this->definitionNormalizer->normalizeDefinitions(
            $this->transferDefinitions
        );

        return $this->transferDefinitions;
    }

    /**
     * @return void
     */
    protected function loadDefinitions()
    {
        $xmlTransferDefinitions = $this->finder->getXmlTransferDefinitionFiles();
        foreach ($xmlTransferDefinitions as $xmlTransferDefinition) {
            $bundle = $this->getBundleFromPathName($xmlTransferDefinition->getFilename());
            $containingBundle = $this->getContainingBundleFromPathName($xmlTransferDefinition->getPathname());
            $definition = Factory::fromFile($xmlTransferDefinition->getPathname(), true)->toArray();
            $this->addDefinition($definition, $bundle, $containingBundle);
        }
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    protected function getBundleFromPathName($fileName)
    {
        $filterChain = new FilterChain();
        $filterChain
            ->attach(new UnderscoreToCamelCase())
            ->attach(new DashToCamelCase());

        return $filterChain->filter(str_replace(self::TRANSFER_SCHEMA_SUFFIX, '', $fileName));
    }

    /**
     * @param string $filePath
     *
     * @return string
     */
    protected function getContainingBundleFromPathName($filePath)
    {
        $pathParts = explode(DIRECTORY_SEPARATOR, $filePath);
        $sharedDirectoryPosition = array_search('Shared', array_values($pathParts));

        $containingBundle = $pathParts[$sharedDirectoryPosition + 1];

        return $containingBundle;
    }

    /**
     * @param array $definition
     * @param string $module
     * @param string $containingModule
     *
     * @return void
     */
    protected function addDefinition(array $definition, $module, $containingModule)
    {
        if (isset($definition[self::KEY_TRANSFER][0])) {
            foreach ($definition[self::KEY_TRANSFER] as $transfer) {
                $this->assertCasing($transfer, $module);

                $transfer[self::KEY_BUNDLE] = $module;
                $transfer[self::KEY_CONTAINING_BUNDLE] = $containingModule;

                $transfer = $this->normalize($transfer);
                $this->transferDefinitions[] = $transfer;
            }
        } else {
            $transfer = $definition[self::KEY_TRANSFER];
            $this->assertCasing($transfer, $module);

            $transfer[self::KEY_BUNDLE] = $module;
            $transfer[self::KEY_CONTAINING_BUNDLE] = $containingModule;

            $transfer = $this->normalize($transfer);
            $this->transferDefinitions[] = $transfer;
        }
    }

    /**
     * @param array $transfer
     * @param string $bundle
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    protected function assertCasing(array $transfer, $bundle)
    {
        $name = $transfer['name'];

        $filteredName = $this->getFilter()->filter($name);

        if ($name !== $filteredName) {
            throw new InvalidArgumentException(
                sprintf(
                    'Transfer name `%s` does not match expected name `%s` for module `%s`',
                    $name,
                    $filteredName,
                    $bundle
                )
            );
        }
    }

    /**
     * @return \Laminas\Filter\FilterChain
     */
    protected function getFilter()
    {
        if (self::$filter === null) {
            $filter = new FilterChain();
            $filter->attach(new CamelCaseToUnderscore());
            $filter->attach(new UnderscoreToCamelCase());

            self::$filter = $filter;
        }

        return self::$filter;
    }

    /**
     * We need to shim casing issues for property names or singular names for BC reasons.
     *
     * @param array $transfer
     *
     * @return array
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
