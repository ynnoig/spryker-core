<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\EventJournal\Model\Collector;

use Spryker\Zed\Library\Generator\StringGenerator;
use Symfony\Component\HttpFoundation\Request;

class RequestDataCollector extends AbstractDataCollector
{

    const TYPE = 'request';

    const FIELD_REQUEST_ID = 'request_id';

    const FIELD_MICROTIME = 'microtime';

    const FIELD_REQUEST_PARAMS = 'request_params';

    /**
     * @var string
     */
    public static $idRequest;

    public function __construct(array $options)
    {
        parent::__construct($options);

        if (self::$idRequest === null) {
            self::$idRequest = $this->getRandomString();
        }
    }

    /**
     * @return string
     */
    private function getRandomString()
    {
        $generator = new StringGenerator();

        return $generator->generateRandomString();
    }

    /**
     * @return array
     */
    public function getData()
    {
        $fields = [
            self::FIELD_REQUEST_ID => self::$idRequest,
            self::FIELD_MICROTIME => microtime(true),
            self::FIELD_REQUEST_PARAMS => $this->getRequestParams(),
        ];

        return $fields;
    }

    /**
     * @return array
     */
    protected function getRequestParams()
    {
        return $_REQUEST;
    }

}
