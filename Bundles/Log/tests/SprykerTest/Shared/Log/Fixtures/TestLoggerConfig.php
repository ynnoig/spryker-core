<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Log\Fixtures;

use Spryker\Shared\Log\Config\LoggerConfigInterface;

class TestLoggerConfig implements LoggerConfigInterface
{
    /**
     * @return string
     */
    public function getChannelName(): string
    {
        return 'test';
    }

    /**
     * @return \Monolog\Handler\HandlerInterface[]
     */
    public function getHandlers(): array
    {
        return [];
    }

    /**
     * @return callable[]
     */
    public function getProcessors(): array
    {
        return [];
    }
}
