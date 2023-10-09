<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class MoveToAdvanced implements DataPatchInterface, PatchVersionInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $setup;

    public function __construct(
        ModuleDataSetupInterface $setup
    ) {
        $this->setup = $setup;
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
        $connection = $this->setup->getConnection();
        $tableName = $this->setup->getTable('core_config_data');
        $changedSettings = [
            '"amfinder/general/universal"',
            '"amfinder/general/universal_last"',
            '"amfinder/general/auto_submit"',
            '"amfinder/general/clear_other_conditions"',
            '"amfinder/general/redirect_single_product"',
            '"amfinder/general/is_chosen_enable"',
            '"amfinder/general/show_active_finder_options"',
            '"amfinder/general/name_finder_options_tab"',
        ];

        $whereSettings = '';
        foreach ($changedSettings as $setting) {
            $whereSettings .= '"' . $setting . '",';
        }

        $select = $this->setup->getConnection()->select()
            ->from($tableName, ['config_id', 'path'])
            ->where('path IN (' . implode(',', $changedSettings) . ')');

        $settings = $connection->fetchPairs($select);

        foreach ($settings as $key => $value) {
            $value = str_replace('general', 'advanced', $value);
            $connection->update($tableName, ['path' => $value], ['config_id = ?' => $key]);
        }
    }

    public static function getVersion(): string
    {
        return '1.9.0';
    }
}
