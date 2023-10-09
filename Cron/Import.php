<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */
/**
 * Copyright © 2015 Amasty. All rights reserved.
 */
namespace Amasty\Finder\Cron;

class Import
{
    /**
     * @var \Amasty\Finder\Model\Import
     */
    private $importModel;

    /**
     * Import constructor.
     * @param \Amasty\Finder\Model\Import $importModel
     */
    public function __construct(
        \Amasty\Finder\Model\Import $importModel
    ) {
        $this->importModel = $importModel;
    }

    public function execute()
    {
        $this->importModel->runAll();
    }
}
