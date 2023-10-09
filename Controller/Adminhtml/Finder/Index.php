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

class Index extends \Amasty\Finder\Controller\Adminhtml\Finder
{
    /**
     * Items list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Amasty_Finder::finder');
        $resultPage->getConfig()->getTitle()->prepend(__('Product Parts Finder'));
        $resultPage->addBreadcrumb(__('Amasty'), __('Amasty'));
        $resultPage->addBreadcrumb(__('Items'), __('Items'));
        return $resultPage;
    }
}
