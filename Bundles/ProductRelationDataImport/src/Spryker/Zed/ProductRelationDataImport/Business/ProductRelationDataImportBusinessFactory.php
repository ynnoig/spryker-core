<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\ProductRelationDataImport\Business\Writer\ProductRelation\ProductRelationTypeWriterStep;
use Spryker\Zed\ProductRelationDataImport\Business\Writer\ProductRelation\ProductRelationWriterStep;
use Spryker\Zed\ProductRelationDataImport\Business\Writer\ProductRelation\SkuToIdProductAbstractStep;
use Spryker\Zed\ProductRelationDataImport\Business\Writer\ProductRelationStore\ProductRelationKeyToIdProductRelationStep;
use Spryker\Zed\ProductRelationDataImport\Business\Writer\ProductRelationStore\ProductRelationStoreWriterStep;
use Spryker\Zed\ProductRelationDataImport\Business\Writer\ProductRelationStore\StoreNameToIdStoreStep;

/**
 * @method \Spryker\Zed\ProductRelationDataImport\ProductRelationDataImportConfig getConfig()
 */
class ProductRelationDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getProductRelationDataImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductRelationDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createSkuToIdProductAbstractStep())
            ->addStep($this->createProductRelationTypeWriterStep())
            ->addStep($this->createProductRelationWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getProductRelationStoreDataImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductRelationStoreDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createProductRelationKeyToIdProductRelationStep())
            ->addStep($this->createStoreNameToIdStoreStep())
            ->addStep($this->createProductRelationStoreWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductRelationTypeWriterStep(): DataImportStepInterface
    {
        return new ProductRelationTypeWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductRelationWriterStep(): DataImportStepInterface
    {
        return new ProductRelationWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createSkuToIdProductAbstractStep(): DataImportStepInterface
    {
        return new SkuToIdProductAbstractStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductRelationKeyToIdProductRelationStep(): DataImportStepInterface
    {
        return new ProductRelationKeyToIdProductRelationStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createStoreNameToIdStoreStep(): DataImportStepInterface
    {
        return new StoreNameToIdStoreStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductRelationStoreWriterStep(): DataImportStepInterface
    {
        return new ProductRelationStoreWriterStep();
    }
}
