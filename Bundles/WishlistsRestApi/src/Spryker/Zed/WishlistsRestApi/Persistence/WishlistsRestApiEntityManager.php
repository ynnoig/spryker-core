<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WishlistsRestApi\Persistence;

use Orm\Zed\Wishlist\Persistence\SpyWishlist;
use Orm\Zed\Wishlist\Persistence\SpyWishlistItem;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\WishlistsRestApi\Persistence\WishlistsRestApiPersistenceFactory getFactory()
 */
class WishlistsRestApiEntityManager extends AbstractEntityManager implements WishlistsRestApiEntityManagerInterface
{
    /**
     * @param \Orm\Zed\Wishlist\Persistence\SpyWishlist $wishlist
     *
     * @return void
     */
    public function saveWishlistEntity(SpyWishlist $wishlist): void
    {
        $wishlist->save();
    }

    /**
     * @param \Orm\Zed\Wishlist\Persistence\SpyWishlistItem $wishlistItem
     *
     * @return void
     */
    public function saveWishlistItemEntity(SpyWishlistItem $wishlistItem): void
    {
        $wishlistItem->save();
    }
}
