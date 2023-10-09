<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Api;

/**
 * @api
 */
interface ProductRepositoryInterface
{
    /**
     * @param \Amasty\Finder\Api\Data\FinderOptionInterface[] $finderOptions
     *
     * @return \Magento\Catalog\Api\Data\ProductInterface[]
     */
    public function getProductsByFinderValues($finderOptions);
}
