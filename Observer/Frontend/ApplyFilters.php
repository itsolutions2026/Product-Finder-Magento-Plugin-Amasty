<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Observer\Frontend;

use Amasty\Finder\Model\Layer\FilterServiceInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ApplyFilters implements ObserverInterface
{
    /**
     * @var FilterServiceInterface
     */
    private $filterService;

    public function __construct(
        FilterServiceInterface $filterService
    ) {
        $this->filterService = $filterService;
    }

    public function execute(Observer $observer)
    {
        $this->filterService->apply();
    }
}
