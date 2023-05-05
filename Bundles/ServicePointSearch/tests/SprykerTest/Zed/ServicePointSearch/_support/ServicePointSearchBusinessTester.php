<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointSearch;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ServicePointAddressBuilder;
use Generated\Shared\DataBuilder\ServicePointBuilder;
use Generated\Shared\Search\ServicePointIndexMap;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\RegionTransfer;
use Generated\Shared\Transfer\ServicePointAddressTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ServicePointSearch\Persistence\SpyServicePointSearchQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\ServicePointSearch\Business\ServicePointSearchFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ServicePointSearchBusinessTester extends Actor
{
    use _generated\ServicePointSearchBusinessTesterActions;

    /**
     * @var string
     */
    protected const TEST_STORE_DE = 'DE';

    /**
     * @var string
     */
    protected const TEST_STORE_AT = 'AT';

    /**
     * @var string
     */
    protected const COUNTRY_ISO2_CODE = '00';

    /**
     * @var string
     */
    protected const COUNTRY_ISO3_CODE = '000';

    /**
     * @return void
     */
    public function setDependencies(): void
    {
        $this->setQueueAdaptersDependency();
    }

    /**
     * @return void
     */
    public function cleanUpDatabase(): void
    {
        $this->cleanUpServicePointSearchTable();
    }

    /**
     * @param list<int> $storeIds
     * @param array<string, mixed> $servicePointData
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function createServicePointWithStoreRelation(array $storeIds, array $servicePointData = []): ServicePointTransfer
    {
        $servicePointBuilder = (new ServicePointBuilder($servicePointData));
        $storeRelationData = [];

        foreach ($storeIds as $idStore) {
            $storeRelationData[StoreRelationTransfer::STORES][] = [
                StoreTransfer::ID_STORE => $idStore,
            ];
        }

        $servicePointBuilder->withStoreRelation($storeRelationData);

        return $this->haveServicePoint($servicePointBuilder->build()->toArray());
    }

    /**
     * @param array<int> $ids
     *
     * @return array<\Generated\Shared\Transfer\EventEntityTransfer>
     */
    public function createEventEntityTransfersFromIds(array $ids): array
    {
        $eventEntityTransfers = [];

        foreach ($ids as $id) {
            $eventEntityTransfers[] = (new EventEntityTransfer())->setId($id);
        }

        return $eventEntityTransfers;
    }

    /**
     * @param string $key
     * @param array<int> $ids
     *
     * @return array<\Generated\Shared\Transfer\EventEntityTransfer>
     */
    public function createEventEntityTransfersFromForeignKeys(string $key, array $ids): array
    {
        $eventEntityTransfers = [];

        foreach ($ids as $id) {
            $eventEntityTransfers[] = (new EventEntityTransfer())->setForeignKeys([$key => $id]);
        }

        return $eventEntityTransfers;
    }

    /**
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function createPublishedServicePointForTwoStores(): ServicePointTransfer
    {
        $storeDE = $this->haveStore([StoreTransfer::NAME => static::TEST_STORE_DE]);
        $storeAT = $this->haveStore([StoreTransfer::NAME => static::TEST_STORE_AT]);

        $servicePointTransfer = $this->createServicePointWithStoreRelation(
            [$storeDE->getIdStoreOrFail(), $storeAT->getIdStoreOrFail()],
            [ServicePointTransfer::IS_ACTIVE => true],
        );

        $eventEntityTransfers = $this->createEventEntityTransfersFromIds([$servicePointTransfer->getIdServicePointOrFail()]);
        $this->getFacade()->writeCollectionByServicePointEvents($eventEntityTransfers);

        return $servicePointTransfer;
    }

    /**
     * @param list<int> $servicePointIds
     * @param list<string>|null $storeNames
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ServicePointSearch\Persistence\SpyServicePointSearch>
     */
    public function getServicePointSearchEntitiesByServicePointIds(
        array $servicePointIds = [],
        ?array $storeNames = []
    ): ObjectCollection {
        $servicePointSearchQuery = $this->createServicePointSearchQuery();

        if ($servicePointIds) {
            $servicePointSearchQuery->filterByFkServicePoint_In($servicePointIds);
        }

        if ($storeNames) {
            $servicePointSearchQuery->filterByStore_In($storeNames);
        }

        return $servicePointSearchQuery->find();
    }

    /**
     * @return list<int>
     */
    public function createTwoServicePointsForTwoStores(): array
    {
        $storeDE = $this->haveStore([StoreTransfer::NAME => static::TEST_STORE_DE]);
        $storeAT = $this->haveStore([StoreTransfer::NAME => static::TEST_STORE_AT]);

        $firstServicePointTransfer = $this->createServicePointWithStoreRelation(
            [$storeDE->getIdStoreOrFail(), $storeAT->getIdStoreOrFail()],
            [ServicePointTransfer::IS_ACTIVE => true],
        );
        $secondServicePointTransfer = $this->createServicePointWithStoreRelation(
            [$storeDE->getIdStoreOrFail(), $storeAT->getIdStoreOrFail()],
            [ServicePointTransfer::IS_ACTIVE => true],
        );

        $this->createServicePointSearchEntities([
            $firstServicePointTransfer->getIdServicePointOrFail() => [
                static::TEST_STORE_DE,
                static::TEST_STORE_AT,
            ],
            $secondServicePointTransfer->getIdServicePointOrFail() => [
                static::TEST_STORE_DE,
                static::TEST_STORE_AT,
            ],
        ]);

        return [
            $firstServicePointTransfer->getIdServicePointOrFail(),
            $secondServicePointTransfer->getIdServicePointOrFail(),
        ];
    }

    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressTransfer
     */
    public function createServicePointAddressTransferWithRelations(array $seed = []): ServicePointAddressTransfer
    {
        $servicePointAddressTransfer = (new ServicePointAddressBuilder($seed))->build();

        $countryTransfer = $this->haveCountryTransfer([
            CountryTransfer::ISO2_CODE => static::COUNTRY_ISO2_CODE,
            CountryTransfer::ISO3_CODE => static::COUNTRY_ISO3_CODE,
        ]);

        if (!$servicePointAddressTransfer->getServicePoint()) {
            $storeTransfer = $this->haveStore([StoreTransfer::NAME => static::TEST_STORE_DE]);
            $servicePointTransfer = $this->createServicePointWithStoreRelation(
                [$storeTransfer->getIdStoreOrFail()],
                [ServicePointTransfer::IS_ACTIVE => true],
            );
            $servicePointAddressTransfer->setServicePoint($servicePointTransfer);
        }

        if (!$servicePointAddressTransfer->getCountry()) {
            $servicePointAddressTransfer->setCountry($countryTransfer);
        }

        if (!$servicePointAddressTransfer->getRegion()) {
            $regionTransfer = $this->haveRegion([
                RegionTransfer::FK_COUNTRY => $countryTransfer->getIdCountry(),
            ]);
            $servicePointAddressTransfer->setRegion($regionTransfer);
        }

        return $servicePointAddressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     * @param array $data
     *
     * @return void
     */
    public function assertServicePointData(ServicePointTransfer $servicePointTransfer, array $data): void
    {
        $this->assertSame('service_point', $data[ServicePointIndexMap::TYPE]);
        $this->assertSame(static::TEST_STORE_DE, $data[ServicePointIndexMap::STORE]);
        $this->assertSame([$servicePointTransfer->getNameOrFail()], $data[ServicePointIndexMap::FULL_TEXT_BOOSTED]);
        $this->assertSame([$servicePointTransfer->getNameOrFail()], $data[ServicePointIndexMap::FULL_TEXT]);
        $this->assertSame([$servicePointTransfer->getNameOrFail()], $data[ServicePointIndexMap::SUGGESTION_TERMS]);
        $this->assertSame([$servicePointTransfer->getNameOrFail()], $data[ServicePointIndexMap::COMPLETION_TERMS]);
        $this->assertSame([], $data[ServicePointIndexMap::STRING_SORT]);
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     * @param array $data
     *
     * @return void
     */
    public function assertServicePointSearchData(ServicePointTransfer $servicePointTransfer, array $data): void
    {
        $this->assertSame($servicePointTransfer->getIdServicePointOrFail(), $data[ServicePointTransfer::ID_SERVICE_POINT]);
        $this->assertSame($servicePointTransfer->getUuidOrFail(), $data[ServicePointTransfer::UUID]);
        $this->assertSame($servicePointTransfer->getNameOrFail(), $data[ServicePointTransfer::NAME]);
        $this->assertSame($servicePointTransfer->getKeyOrFail(), $data[ServicePointTransfer::KEY]);
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressTransfer $servicePointAddressTransfer
     * @param array $data
     *
     * @return void
     */
    public function assertServicePointAddressData(ServicePointAddressTransfer $servicePointAddressTransfer, array $data): void
    {
        $this->assertSame('service_point', $data[ServicePointIndexMap::TYPE]);
        $this->assertSame(static::TEST_STORE_DE, $data[ServicePointIndexMap::STORE]);
        $this->assertSame([$servicePointAddressTransfer->getServicePointOrFail()->getNameOrFail()], $data[ServicePointIndexMap::FULL_TEXT_BOOSTED]);
        $this->assertSame([$servicePointAddressTransfer->getServicePointOrFail()->getNameOrFail()], $data[ServicePointIndexMap::SUGGESTION_TERMS]);
        $this->assertSame([$servicePointAddressTransfer->getServicePointOrFail()->getNameOrFail()], $data[ServicePointIndexMap::COMPLETION_TERMS]);
        $this->assertSame([ServicePointAddressTransfer::CITY => $servicePointAddressTransfer->getCityOrFail()], $data[ServicePointIndexMap::STRING_SORT]);
        $this->assertSame([
            $servicePointAddressTransfer->getServicePointOrFail()->getNameOrFail(),
            $servicePointAddressTransfer->getCityOrFail(),
            $servicePointAddressTransfer->getZipCodeOrFail(),
            $servicePointAddressTransfer->getCountryOrFail()->getNameOrFail(),
            trim(sprintf('%s %s %s', $servicePointAddressTransfer->getAddress1OrFail(), $servicePointAddressTransfer->getAddress2OrFail(), $servicePointAddressTransfer->getAddress3())),
            $servicePointAddressTransfer->getRegionOrFail()->getNameOrFail(),
        ], $data[ServicePointIndexMap::FULL_TEXT]);
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressTransfer $servicePointAddressTransfer
     * @param array $data
     *
     * @return void
     */
    public function assertServicePointAddressSearchData(ServicePointAddressTransfer $servicePointAddressTransfer, array $data): void
    {
        $this->assertSame(
            $servicePointAddressTransfer->getIdServicePointAddressOrFail(),
            $data[ServicePointTransfer::ADDRESS][ServicePointAddressTransfer::ID_SERVICE_POINT_ADDRESS],
        );
        $this->assertSame(
            $servicePointAddressTransfer->getUuidOrFail(),
            $data[ServicePointTransfer::ADDRESS][ServicePointAddressTransfer::UUID],
        );
        $this->assertSame(
            $servicePointAddressTransfer->getAddress1OrFail(),
            $data[ServicePointTransfer::ADDRESS][ServicePointAddressTransfer::ADDRESS1],
        );
        $this->assertSame(
            $servicePointAddressTransfer->getAddress2OrFail(),
            $data[ServicePointTransfer::ADDRESS][ServicePointAddressTransfer::ADDRESS2],
        );
        $this->assertSame(
            $servicePointAddressTransfer->getAddress3(),
            $data[ServicePointTransfer::ADDRESS][ServicePointAddressTransfer::ADDRESS3],
        );
        $this->assertSame(
            $servicePointAddressTransfer->getCityOrFail(),
            $data[ServicePointTransfer::ADDRESS][ServicePointAddressTransfer::CITY],
        );
        $this->assertSame(
            $servicePointAddressTransfer->getZipCodeOrFail(),
            $data[ServicePointTransfer::ADDRESS][ServicePointAddressTransfer::ZIP_CODE],
        );
        $this->assertSame(
            $servicePointAddressTransfer->getCountryOrFail()->getNameOrFail(),
            $data[ServicePointTransfer::ADDRESS][ServicePointAddressTransfer::COUNTRY][CountryTransfer::NAME],
        );
        $this->assertSame(
            $servicePointAddressTransfer->getCountryOrFail()->getIso2CodeOrFail(),
            $data[ServicePointTransfer::ADDRESS][ServicePointAddressTransfer::COUNTRY][CountryTransfer::ISO2_CODE],
        );
        $this->assertSame(
            $servicePointAddressTransfer->getRegionOrFail()->getNameOrFail(),
            $data[ServicePointTransfer::ADDRESS][ServicePointAddressTransfer::REGION][RegionTransfer::NAME],
        );
    }

    /**
     * @param array<int, list<string> $servicePointStoreNames
     *
     * @return void
     */
    protected function createServicePointSearchEntities(array $servicePointStoreNames): void
    {
        foreach ($servicePointStoreNames as $idServicePoint => $storeNames) {
            foreach ($storeNames as $storeName) {
                $servicePointSearchEntity = $this->createServicePointSearchQuery()
                    ->filterByFkServicePoint($idServicePoint)
                    ->filterByStore($storeName)
                    ->findOneOrCreate();

                $servicePointSearchEntity
                    ->setData([])
                    ->setStructuredData('');

                $servicePointSearchEntity->save();
            }
        }
    }

    /**
     * @return void
     */
    protected function setQueueAdaptersDependency(): void
    {
        $this->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });
    }

    /**
     * @return void
     */
    protected function cleanUpServicePointSearchTable(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->createServicePointSearchQuery());
    }

    /**
     * @return \Orm\Zed\ServicePointSearch\Persistence\SpyServicePointSearchQuery
     */
    protected function createServicePointSearchQuery(): SpyServicePointSearchQuery
    {
        return SpyServicePointSearchQuery::create();
    }
}
