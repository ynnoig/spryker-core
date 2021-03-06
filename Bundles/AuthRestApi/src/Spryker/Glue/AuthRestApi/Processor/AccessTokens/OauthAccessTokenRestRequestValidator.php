<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi\Processor\AccessTokens;

use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\AuthRestApi\AuthRestApiConfig;
use Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OauthAccessTokenRestRequestValidator implements OauthAccessTokenValidatorInterface
{
    protected const REQUEST_ATTRIBUTE_IS_PROTECTED = 'is-protected';

    /**
     * @var \Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface
     */
    protected $oauthClient;

    /**
     * @param \Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface $oauthClient
     */
    public function __construct(AuthRestApiToOauthClientInterface $oauthClient)
    {
        $this->oauthClient = $oauthClient;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    public function validate(Request $request, RestRequestInterface $restRequest): ?RestErrorCollectionTransfer
    {
        $isProtected = $request->attributes->get(static::REQUEST_ATTRIBUTE_IS_PROTECTED, false);

        $authorizationToken = $request->headers->get(AuthRestApiConfig::HEADER_AUTHORIZATION);
        if (!$authorizationToken && $isProtected === true) {
            return (new RestErrorCollectionTransfer())->addRestError(
                $this->createErrorMessageTransfer(
                    AuthRestApiConfig::RESPONSE_DETAIL_MISSING_ACCESS_TOKEN,
                    Response::HTTP_FORBIDDEN,
                    AuthRestApiConfig::RESPONSE_CODE_FORBIDDEN
                )
            );
        }

        if (!$authorizationToken) {
            return null;
        }

        if (!$this->validateAccessToken($authorizationToken)) {
            return (new RestErrorCollectionTransfer())->addRestError(
                $this->createErrorMessageTransfer(
                    AuthRestApiConfig::RESPONSE_DETAIL_INVALID_ACCESS_TOKEN,
                    Response::HTTP_UNAUTHORIZED,
                    AuthRestApiConfig::RESPONSE_CODE_ACCESS_CODE_INVALID
                )
            );
        }

        return null;
    }

    /**
     * @param string $detail
     * @param int $status
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createErrorMessageTransfer(string $detail, int $status, string $code): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setDetail($detail)
            ->setStatus($status)
            ->setCode($code);
    }

    /**
     * @param string $authorizationToken
     *
     * @return string|null
     */
    protected function extractToken(string $authorizationToken): ?string
    {
        return preg_split('/\s+/', $authorizationToken)[1] ?? null;
    }

    /**
     * @param string $authorizationToken
     *
     * @return string|null
     */
    protected function extractTokenType(string $authorizationToken): ?string
    {
        return preg_split('/\s+/', $authorizationToken)[0] ?? null;
    }

    /**
     * @param string $authorizationToken
     *
     * @return bool
     */
    protected function validateAccessToken(string $authorizationToken): bool
    {
        $accessToken = $this->extractToken($authorizationToken);
        $type = $this->extractTokenType($authorizationToken);
        if (!$accessToken || !$type) {
            return false;
        }

        $authAccessTokenValidationRequestTransfer = new OauthAccessTokenValidationRequestTransfer();
        $authAccessTokenValidationRequestTransfer
            ->setAccessToken($accessToken)
            ->setType($type);

        $authAccessTokenValidationResponseTransfer = $this->oauthClient->validateOauthAccessToken(
            $authAccessTokenValidationRequestTransfer
        );

        return $authAccessTokenValidationResponseTransfer->getIsValid();
    }
}
