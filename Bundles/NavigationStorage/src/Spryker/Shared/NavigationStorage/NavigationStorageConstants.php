<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\NavigationStorage;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class NavigationStorageConstants
{
    /**
     * Specification:
     * - Queue name as used for processing translation messages
     *
     * @api
     */
    public const NAVIGATION_SYNC_STORAGE_QUEUE = 'sync.storage.category';

    /**
     * Specification:
     * - Queue name as used for processing translation messages
     *
     * @api
     */
    public const NAVIGATION_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.category.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    public const RESOURCE_NAME = 'navigation';

    /**
     * Specification:
     * - Enables/disables storage synchronization.
     *
     * @api
     *
     * @uses \Spryker\Shared\Synchronization\SynchronizationConstants::STORAGE_SYNC_ENABLED
     */
    public const STORAGE_SYNC_ENABLED = 'SYNCHRONIZATION:STORAGE_SYNC_ENABLED';
}
