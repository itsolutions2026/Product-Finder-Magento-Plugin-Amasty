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

class ErrorsGrid extends \Amasty\Finder\Controller\Adminhtml\Finder
{
    public function execute()
    {
        $this->_forward('errors');
    }
}
