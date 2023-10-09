<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Model\ResourceModel\Finder;

use Magento\Framework\App\ResourceConnection;

class LoadFinderIdByValueId
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    public function __construct(ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
    }

    public function execute(int $valueId): int
    {
        $select = $this->resourceConnection->getConnection()->select()->from(
            ['afd' => $this->resourceConnection->getTableName('amasty_finder_dropdown')],
            ['finder_id']
        )->join(
            ['afv' => $this->resourceConnection->getTableName('amasty_finder_value')],
            'afd.dropdown_id = afv.dropdown_id',
            []
        )->where('value_id = ?', $valueId);

        return (int) $this->resourceConnection->getConnection()->fetchOne($select);
    }
}
