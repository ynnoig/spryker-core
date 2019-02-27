<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationsRestApi\Processor\Navigation;

use Generated\Shared\Transfer\NavigationStorageTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestNavigationAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\NavigationsRestApi\Dependency\Client\NavigationsRestApiToNavigationStorageClientInterface;
use Spryker\Glue\NavigationsRestApi\NavigationsRestApiConfig;
use Spryker\Glue\NavigationsRestApi\Processor\Mapper\NavigationMapperInterface;
use Symfony\Component\HttpFoundation\Response;

class NavigationReader implements NavigationReaderInterface
{
    /**
     * @var \Spryker\Glue\NavigationsRestApi\Dependency\Client\NavigationsRestApiToNavigationStorageClientInterface
     */
    protected $navigationStorageClient;

    /**
     * @var \Spryker\Glue\NavigationsRestApi\Processor\Mapper\NavigationMapperInterface
     */
    protected $navigationMapper;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\NavigationsRestApiExtension\Dependency\Plugin\NavigationsResourceExpanderPluginInterface[]
     */
    protected $navigationsResourceExpanderPlugins;

    /**
     * @param \Spryker\Glue\NavigationsRestApi\Dependency\Client\NavigationsRestApiToNavigationStorageClientInterface $navigationStorageClient
     * @param \Spryker\Glue\NavigationsRestApi\Processor\Mapper\NavigationMapperInterface $navigationMapper
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\NavigationsRestApiExtension\Dependency\Plugin\NavigationsResourceExpanderPluginInterface[] $navigationsResourceExpanderPlugins
     */
    public function __construct(
        NavigationsRestApiToNavigationStorageClientInterface $navigationStorageClient,
        NavigationMapperInterface $navigationMapper,
        RestResourceBuilderInterface $restResourceBuilder,
        array $navigationsResourceExpanderPlugins
    ) {
        $this->navigationStorageClient = $navigationStorageClient;
        $this->navigationMapper = $navigationMapper;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->navigationsResourceExpanderPlugins = $navigationsResourceExpanderPlugins;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getNavigationByKey(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $navigationTreeKey = $restRequest->getResource()->getId();
        if (!$navigationTreeKey) {
            return $restResponse->addError($this->createNavigationIdMissingError());
        }

        $restResource = $this->findNavigationTreeByKey($restRequest);
        if (!$restResource) {
            return $restResponse->addError($this->createNavigationNotFoundError());
        }

        return $restResponse->addResource($restResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    protected function findNavigationTreeByKey(RestRequestInterface $restRequest): ?RestResourceInterface
    {
        $navigationStorageTransfer = $this->navigationStorageClient->findNavigationTreeByKey(
            $restRequest->getResource()->getId(),
            $restRequest->getMetadata()->getLocale()
        );

        if (!$navigationStorageTransfer || !$navigationStorageTransfer->getKey()) {
            return null;
        }

        return $this->buildNavigationResource($navigationStorageTransfer, $restRequest);
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationStorageTransfer $navigationStorageTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function buildNavigationResource(
        NavigationStorageTransfer $navigationStorageTransfer,
        RestRequestInterface $restRequest
    ): RestResourceInterface {
        $restNavigationAttributesTransfer = $this->navigationMapper
            ->mapNavigationStorageTransferToRestNavigationAttributesTransfer(
                $navigationStorageTransfer,
                new RestNavigationAttributesTransfer()
            );
        $restNavigationAttributesTransfer = $this->expandRestNavigationsAttributesTransfer($restNavigationAttributesTransfer, $restRequest);

        return $this->restResourceBuilder->createRestResource(
            NavigationsRestApiConfig::RESOURCE_NAVIGATIONS,
            $navigationStorageTransfer->getKey(),
            $restNavigationAttributesTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RestNavigationAttributesTransfer $restNavigationAttributesTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestNavigationAttributesTransfer
     */
    protected function expandRestNavigationsAttributesTransfer(
        RestNavigationAttributesTransfer $restNavigationAttributesTransfer,
        RestRequestInterface $restRequest
    ): RestNavigationAttributesTransfer {
        foreach ($this->navigationsResourceExpanderPlugins as $navigationsResourceExpanderPlugin) {
            $restNavigationAttributesTransfer = $navigationsResourceExpanderPlugin->expand($restNavigationAttributesTransfer, $restRequest);
        }

        return $restNavigationAttributesTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createNavigationIdMissingError(): RestErrorMessageTransfer
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(NavigationsRestApiConfig::RESPONSE_CODE_NAVIGATION_ID_IS_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(NavigationsRestApiConfig::RESPONSE_DETAILS_NAVIGATION_ID_IS_NOT_SPECIFIED);

        return $restErrorTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createNavigationNotFoundError(): RestErrorMessageTransfer
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(NavigationsRestApiConfig::RESPONSE_CODE_NAVIGATION_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(NavigationsRestApiConfig::RESPONSE_DETAILS_NAVIGATION_NOT_FOUND);

        return $restErrorTransfer;
    }
}
