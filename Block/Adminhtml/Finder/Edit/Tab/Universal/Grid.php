<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */
/**
 * Copyright © 2015 Amasty. All rights reserved.
 */

namespace Amasty\Finder\Block\Adminhtml\Finder\Edit\Tab\Universal;

class Grid extends \Amasty\Finder\Block\Adminhtml\Finder\Edit\Tab\AbstractGrid
{
    /**
     * @return \Amasty\Finder\Block\Adminhtml\Finder\Edit\Tab\AbstractGrid
     */
    protected function _prepareCollection()
    {
        $finder = $this->getFinder();
        $this->universalCollection->addFieldToFilter('finder_id', $finder->getId());
        $this->setCollection($this->universalCollection);
        return parent::_prepareCollection();
    }
}
