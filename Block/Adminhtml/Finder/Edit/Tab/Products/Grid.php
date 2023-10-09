<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */
/**
 * Copyright © 2015 Amasty. All rights reserved.
 */

namespace Amasty\Finder\Block\Adminhtml\Finder\Edit\Tab\Products;

class Grid extends \Amasty\Finder\Block\Adminhtml\Finder\Edit\Tab\AbstractGrid
{

    /**
     * @return \Amasty\Finder\Block\Adminhtml\Finder\Edit\Tab\AbstractGrid
     */
    protected function _prepareCollection()
    {
        $finder = $this->getFinder();
        $this->productCollection->joinAllFor($finder);
        $this->setCollection($this->productCollection);
        return parent::_prepareCollection();
    }
}
