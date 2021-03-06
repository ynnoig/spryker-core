<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer;

use Generated\Shared\Transfer\PluginAnnotationsTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;

interface ResourceRelationshipsPluginAnnotationAnalyzerInterface
{
    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface $plugin
     *
     * @return \Generated\Shared\Transfer\PluginAnnotationsTransfer
     */
    public function getResourceAttributesFromResourceRelationshipPlugin(ResourceRelationshipPluginInterface $plugin): PluginAnnotationsTransfer;
}
