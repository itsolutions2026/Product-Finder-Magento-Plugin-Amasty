<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Model\Elasticsearch\Adapter;

/**
 * Interface DataMapperInterface
 */
interface DataMapperInterface
{
    /**
     * @param array $document
     * @return array
     */
    public function map(array $document): array;

    /**
     * @return bool
     */
    public function isAllowed(): bool;
}
