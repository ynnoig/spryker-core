<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\ServicePoint\Business\Validator\Util\ErrorAdderInterface;

class ServicePointUuidUniquenessServicePointAddressValidatorRule implements ServicePointAddressValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_SERVICE_POINT_UUID_IS_NOT_UNIQUE = 'service_point.validation.service_point_uuid_is_not_unique';

    /**
     * @var \Spryker\Zed\ServicePoint\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @param \Spryker\Zed\ServicePoint\Business\Validator\Util\ErrorAdderInterface $errorAdder
     */
    public function __construct(ErrorAdderInterface $errorAdder)
    {
        $this->errorAdder = $errorAdder;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointAddressTransfer> $servicePointAddressTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $servicePointAddressTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        $uuidIndex = [];

        foreach ($servicePointAddressTransfers as $entityIdentifier => $servicePointAddressTransfer) {
            $uuid = $servicePointAddressTransfer->getServicePointOrFail()->getUuidOrFail();

            if (!isset($uuidIndex[$uuid])) {
                $uuidIndex[$uuid] = true;

                continue;
            }

            $this->errorAdder->addError(
                $errorCollectionTransfer,
                $entityIdentifier,
                static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_SERVICE_POINT_UUID_IS_NOT_UNIQUE,
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $initialErrorTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $postValidationErrorTransfers
     *
     * @return bool
     */
    public function isTerminated(
        ArrayObject $initialErrorTransfers,
        ArrayObject $postValidationErrorTransfers
    ): bool {
        return $postValidationErrorTransfers->count() > $initialErrorTransfers->count();
    }
}
