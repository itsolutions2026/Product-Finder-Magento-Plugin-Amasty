<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Model\ResourceModel\Value;

use Amasty\Finder\Api\Data\ValueInterface;
use Magento\Framework\DB\Select;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public const FIRST_DROPDOWN = 0;

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Amasty\Finder\Model\Value::class, \Amasty\Finder\Model\ResourceModel\Value::class);
    }

    /**
     * @param \Amasty\Finder\Model\Finder $finder
     * @return $this
     */
    public function joinAllFor(\Amasty\Finder\Model\Finder $finder)
    {
        $select = $this->getSelect();
        $select->reset(Select::FROM);
        $select->reset(Select::COLUMNS);

        $position = self::FIRST_DROPDOWN;
        foreach ($finder->getDropdowns() as $dropdown) {
            $position = $dropdown->getPos();

            if ($position == self::FIRST_DROPDOWN) {
                $table = ["main_table" => $this->getTable('amasty_finder_value')];
                $fields = ["name" . $position => "main_table.name"];
                $select->from($table, $fields);
                $select->where("main_table.dropdown_id=?", $dropdown->getId());
            } else {
                $table = ["dropdown" . $position => $this->getTable('amasty_finder_value')];
                $fields = ["name" . $position => "dropdown" . $position . ".name"];
                $tableWithValue = ($position - 1) ? 'dropdown' . ($position - 1) : 'main_table';
                $bind = "dropdown" . $position . ".parent_id = " . $tableWithValue . ".value_id";
                $select->joinInner($table, $bind, $fields);
            }
        }
        $tableName = $position ? 'dropdown' . $position : 'main_table';

        $select->joinInner(
            ['finderMap' => $this->getTable('amasty_finder_map')],
            $tableName . ".value_id = finderMap.value_id",
            [
                'sku',
                'val' => 'finderMap.value_id',
                'vid' => 'finderMap.id',
                'finder_id' => new \Zend_Db_Expr($finder->getId())
            ]
        );

        return $this;
    }

    /**
     * @param string $colName
     * @return array
     */
    public function getColumnValues($colName)
    {
        if ($colName == ValueInterface::VID) {
            return \Magento\Framework\Data\Collection::getAllIds();
        }

        return parent::getColumnValues($colName);
    }
}
