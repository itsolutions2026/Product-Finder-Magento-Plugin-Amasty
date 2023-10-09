<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;

class Uninstall implements UninstallInterface
{
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $this
            ->uninstallTables($setup)
            ->uninstallConfigData($setup);
    }

    private function uninstallTables(SchemaSetupInterface $setup): self
    {
        $tablesToDrop = [
            'amasty_finder_finder',
            'amasty_finder_dropdown',
            'amasty_finder_import_file_log',
            'amasty_finder_import_file_log_history',
            'amasty_finder_import_file_log_errors',
            'amasty_finder_map',
            'amasty_finder_universal',
            'amasty_finder_value'
        ];
        foreach ($tablesToDrop as $table) {
            $setup->getConnection()->dropTable(
                $setup->getTable($table)
            );
        }

        return $this;
    }

    private function uninstallConfigData(SchemaSetupInterface $setup): self
    {
        $configTable = $setup->getTable('core_config_data');
        $setup->getConnection()->delete($configTable, "`path` LIKE 'amfinder%'");

        return $this;
    }
}
