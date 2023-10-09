<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Finder
 */


namespace Amasty\Finder\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * @var Operation\MoveToAdvanced
     */
    private $moveToAdvanced;

    /**
     * @var Operation\AddDropdownDisplayType
     */
    private $addDropdownDisplayType;

    /**
     * @var Operation\RemoveImageDirectory
     */
    private $removeImageDirectory;

    public function __construct(
        \Amasty\Finder\Setup\Operation\MoveToAdvanced $moveToAdvanced,
        \Amasty\Finder\Setup\Operation\AddDropdownDisplayType $addDropdownDisplayType,
        \Amasty\Finder\Setup\Operation\RemoveImageDirectory $removeImageDirectory
    ) {
        $this->moveToAdvanced = $moveToAdvanced;
        $this->addDropdownDisplayType = $addDropdownDisplayType;
        $this->removeImageDirectory = $removeImageDirectory;
    }

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1.0', '<')) {
            $this->addDefaultCategorySetting($setup);
        }

        if (version_compare($context->getVersion(), '1.6.0', '<')) {
            $this->addCategories($setup);
            $this->addFinderPosition($setup);
        }

        if (version_compare($context->getVersion(), '1.7.1', '<')) {
            $this->addSearchPage($setup);
        }

        if (version_compare($context->getVersion(), '1.8.0', '<')) {
            $this->addImages($setup);
        }

        if (version_compare($context->getVersion(), '1.9.0', '<')) {
            $this->moveToAdvanced->execute($setup);
            $this->addDropdownDisplayType->execute($setup);
        }

        if (version_compare($context->getVersion(), '1.9.4', '<')) {
            $this->removeImageDirectory->execute($setup);
        }

        $this->ktypeCacheTable($setup);

        $setup->endSetup();
    }
    /**
     * @param SchemaSetupInterface $setup
     */

    private function ktypeCacheTable(SchemaSetupInterface $setup){
        if(!$setup->getConnection()->isTableExists('amasty_finder_ktype_cache')){
            $table = $setup
                ->getConnection()
                ->newTable($setup->getTable('amasty_finder_ktype_cache'))
                ->addColumn(
                    'id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true]
                )
                ->addColumn(
                    'rego',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    7,
                    ['default' => null, 'nullable' => true],
                    'Count dropdowns'
                )
                ->addColumn(
                    'vin',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    17,
                    ['default' => null, 'nullable' => false],
                    'Name'
                )
                ->addColumn(
                    'content',
                    'json',
                    \Magento\Framework\DB\Ddl\Table::MAX_TEXT_SIZE,
                    ['default' => null, 'nullable' => false],
                    'Template'
                );

            $setup->getConnection()->createTable($table);
        }
    }

    /**
     * @param SchemaSetupInterface $setup
     */

    private function addDefaultCategorySetting(SchemaSetupInterface $setup)
    {
        $tableName = $setup->getTable('amasty_finder_finder');
        $setup->getConnection()->addColumn(
            $tableName,
            'default_category',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => false,
                'default' => 0,
                'comment' => 'Add on default category',
            ]
        );
        $setup->getConnection()->addColumn(
            $tableName,
            'hide_finder',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => false,
                'default' => 0,
                'comment' => 'Hide on default category',
            ]
        );
    }

    /**
     * @param SchemaSetupInterface $setup
     */
    private function addCategories(SchemaSetupInterface $setup)
    {
        $tableName = $setup->getTable('amasty_finder_finder');
        $setup->getConnection()->addColumn(
            $tableName,
            'categories',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => false,
                'default' => '',
                'comment' => 'Add on categories',
            ]
        );
    }

    /**
     * @param SchemaSetupInterface $setup
     */
    private function addFinderPosition(SchemaSetupInterface $setup)
    {
        $tableName = $setup->getTable('amasty_finder_finder');
        $setup->getConnection()->addColumn(
            $tableName,
            'position',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => false,
                'default' => '',
                'comment' => 'Add in block',
            ]
        );
    }

    /**
     * @param SchemaSetupInterface $setup
     */
    private function addSearchPage(SchemaSetupInterface $setup)
    {
        $tableName = $setup->getTable('amasty_finder_finder');
        $setup->getConnection()->addColumn(
            $tableName,
            'search_page',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => false,
                'default' => 0,
                'comment' => 'Add on search page',
            ]
        );
    }

    /**
     * @param SchemaSetupInterface $setup
     */
    private function addImages(SchemaSetupInterface $setup)
    {
        $tableName = $setup->getTable('amasty_finder_value');
        $setup->getConnection()->addColumn(
            $tableName,
            'image',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => false,
                'default' => '',
                'comment' => 'Add images to options',
            ]
        );
    }
}
