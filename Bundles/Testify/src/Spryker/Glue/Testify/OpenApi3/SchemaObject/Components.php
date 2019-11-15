<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\SchemaObject;

use Spryker\Glue\Testify\OpenApi3\Collection\Callbacks;
use Spryker\Glue\Testify\OpenApi3\Collection\Examples;
use Spryker\Glue\Testify\OpenApi3\Collection\Headers;
use Spryker\Glue\Testify\OpenApi3\Collection\Links;
use Spryker\Glue\Testify\OpenApi3\Collection\Parameters;
use Spryker\Glue\Testify\OpenApi3\Collection\RequestBodies;
use Spryker\Glue\Testify\OpenApi3\Collection\Responses;
use Spryker\Glue\Testify\OpenApi3\Collection\Schemas;
use Spryker\Glue\Testify\OpenApi3\Collection\SecuritySchemes;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;

/**
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Schema[] $schemas
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Response[] $responses
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Parameter[] $parameters
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Example[] $examples
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\RequestBody[] $requestBodies
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Header[] $headers
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\SecurityScheme[] $securitySchemes
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Link[] $links
 * @property-read \Spryker\Glue\Testify\OpenApi3\Collection\Callback[] $callbacks
 */
class Components extends AbstractObject
{
    /**
     * @inheritDoc
     */
    public function getObjectSpecification(): ObjectSpecification
    {
        return (new ObjectSpecification())
            ->setProperty('schemas', new PropertyDefinition(Schemas::class))
            ->setProperty('responses', new PropertyDefinition(Responses::class))
            ->setProperty('parameters', new PropertyDefinition(Parameters::class))
            ->setProperty('examples', new PropertyDefinition(Examples::class))
            ->setProperty('requestBodies', new PropertyDefinition(RequestBodies::class))
            ->setProperty('headers', new PropertyDefinition(Headers::class))
            ->setProperty('securitySchemes', new PropertyDefinition(SecuritySchemes::class))
            ->setProperty('links', new PropertyDefinition(Links::class))
            ->setProperty('callbacks', new PropertyDefinition(Callbacks::class));
    }
}