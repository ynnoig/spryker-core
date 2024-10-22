<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyRole\Plugin\Permission;

use Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface;

class SeeCompanyRolesPermissionPlugin implements PermissionPluginInterface
{
    /**
     * @var string
     */
    protected const KEY = 'SeeCompanyRolesPermissionPlugin';

    /**
     * {@inheritDoc}
     * - Returns plugin name as a key to permission to see company roles.
     *
     * @api
     *
     * @return string
     */
    public function getKey(): string
    {
        return static::KEY;
    }
}
