<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

/**
 * Copyright © 2015 Amasty. All rights reserved.
 */
namespace Amasty\Finder\Model\ResourceModel\ImportHistory;

use Amasty\Finder\Api\Data\ImportHistoryInterface;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \Amasty\Finder\Model\ImportHistory::class,
            \Amasty\Finder\Model\ResourceModel\ImportHistory::class
        );
    }

    /**
     * @param string $colName
     * @return array
     */
    public function getColumnValues($colName)
    {
        if ($colName == ImportHistoryInterface::FILE_ID) {
            return \Magento\Framework\Data\Collection::getAllIds();
        }

        return parent::getColumnValues($colName);
    }
}
