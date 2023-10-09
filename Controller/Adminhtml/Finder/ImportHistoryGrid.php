<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */
/**
 * Copyright © 2015 Amasty. All rights reserved.
 */

namespace Amasty\Finder\Controller\Adminhtml\Finder;

use Magento\Framework\Controller\ResultFactory;

class ImportHistoryGrid extends \Amasty\Finder\Controller\Adminhtml\Finder
{
    use \Amasty\Finder\MyTrait\FinderController;

    /**
     * @return \Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        $this->_initFinder();
        /** @var \Magento\Framework\View\Result\Layout $resultLayout */
        $resultLayout = $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);
        return $resultLayout;
    }
}
