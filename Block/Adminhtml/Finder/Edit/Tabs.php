<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */
/**
 * Copyright © 2015 Amasty. All rights reserved.
 */
namespace Amasty\Finder\Block\Adminhtml\Finder\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('amasty_finder_finder_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Finder Options'));
    }
}
