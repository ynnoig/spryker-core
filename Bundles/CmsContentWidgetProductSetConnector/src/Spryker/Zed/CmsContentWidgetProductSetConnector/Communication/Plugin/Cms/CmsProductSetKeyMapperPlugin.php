<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetProductSetConnector\Communication\Plugin\Cms;

use Spryker\Zed\CmsContentWidget\Dependency\Plugin\CmsContentWidgetParameterMapperPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsContentWidgetProductSetConnector\Business\CmsContentWidgetProductSetConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsContentWidgetProductSetConnector\CmsContentWidgetProductSetConnectorConfig getConfig()
 * @method \Spryker\Zed\CmsContentWidgetProductSetConnector\Persistence\CmsContentWidgetProductSetConnectorQueryContainerInterface getQueryContainer()
 */
class CmsProductSetKeyMapperPlugin extends AbstractPlugin implements CmsContentWidgetParameterMapperPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $parameters
     *
     * @return array
     */
    public function map(array $parameters)
    {
        return $this->getFacade()->mapProductKeyList($parameters);
    }
}
