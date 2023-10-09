<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

/**
 * Copyright © 2015 Amasty. All rights reserved.
 */
namespace Amasty\Finder\Model\ResourceModel\ImportErrors;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init(\Amasty\Finder\Model\ImportErrors::class, \Amasty\Finder\Model\ResourceModel\ImportErrors::class);
    }
}
