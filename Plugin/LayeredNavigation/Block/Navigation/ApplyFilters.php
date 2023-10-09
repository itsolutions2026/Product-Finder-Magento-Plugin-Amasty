<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Plugin\LayeredNavigation\Block\Navigation;

use Amasty\Finder\Model\Layer\FilterServiceInterface;
use Magento\LayeredNavigation\Block\Navigation;

/**
 * We need to apply filters before rendering the navigation block,
 * because it triggers initialization of the search result.
 * @see \Amasty\Shopby\Model\ResourceModel\Fulltext\Collection::_renderFiltersBefore
 */
class ApplyFilters
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

    public function beforeToHtml(Navigation $subject): void
    {
        $this->filterService->apply();
    }
}
