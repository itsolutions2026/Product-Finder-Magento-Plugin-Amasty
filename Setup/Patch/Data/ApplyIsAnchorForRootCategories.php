<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Setup\Patch\Data;

use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class ApplyIsAnchorForRootCategories implements DataPatchInterface, PatchVersionInterface
{
    /**
     * @var CategoryCollectionFactory
     */
    private $categoryCollectionFactory;

    public function __construct(
        CategoryCollectionFactory $categoryFactory
    ) {
        $this->categoryCollectionFactory = $categoryFactory;
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): void
    {
        try {
            $rootCategories = $this->categoryCollectionFactory->create();
            $rootCategories
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('level', 1);
            foreach ($rootCategories as $category) {
                $category->setIsAnchor(true);
            }

            $rootCategories->save();
        } catch (\Exception $e) {
            // "Invalid attribute name: level" while running unit tests in some cases
            return;
        }
    }

    public static function getVersion(): string
    {
        return '1.7.2';
    }
}
