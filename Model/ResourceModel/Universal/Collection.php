<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */
/**
 * Copyright © 2015 Amasty. All rights reserved.
 */

namespace Amasty\Finder\Model\ResourceModel\Universal;

use Amasty\Finder\Api\Data\UniversalInterface;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Amasty\Finder\Model\Universal::class, \Amasty\Finder\Model\ResourceModel\Universal::class);
    }

    /**
     * @param string $colName
     * @return array
     */
    public function getColumnValues($colName)
    {
        if ($colName == UniversalInterface::UNIVERSAL_ID) {
            return \Magento\Framework\Data\Collection::getAllIds();
        }

        return parent::getColumnValues($colName);
    }
}
