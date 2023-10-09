<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Model\Finder\Query;

use Amasty\Finder\Model\ResourceModel\Finder\LoadFinderIdByValueId;

class GetFinderIdByValueId
{
    /**
     * @var array
     */
    private $cache = [];

    /**
     * @var LoadFinderIdByValueId
     */
    private $loadFinderIdByValueId;

    public function __construct(LoadFinderIdByValueId $loadFinderIdByValueId)
    {
        $this->loadFinderIdByValueId = $loadFinderIdByValueId;
    }

    public function execute(int $valueId): int
    {
        if (!isset($this->cache[$valueId])) {
            $this->cache[$valueId] = $this->loadFinderIdByValueId->execute($valueId);
        }

        return $this->cache[$valueId];
    }
}
