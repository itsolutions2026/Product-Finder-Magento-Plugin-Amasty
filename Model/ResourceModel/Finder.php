<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Model\ResourceModel;

use Amasty\Finder\Api\Data\FinderInterface;
use Amasty\Finder\Controller\Adminhtml\Finder\ImportUniversal;
use Amasty\Finder\Model\FileValidator;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;

class Finder extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public const MAX_LINE = 2000;
    public const BATCH_SIZE = 1000;

    /**
     * @var \Amasty\Finder\Model\Finder\SearchCriteriaBuilderFactory
     */
    private $searchCriteriaBuilderFactory;

    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    private $metaData;

    /**
     * @var FileValidator
     */
    private $fileValidator;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $fileDriver;

    /**
     * @var \Amasty\Finder\Model\ConfigProvider
     */
    private $configHelper;

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Amasty\Finder\Model\ConfigProvider $configHelper,
        \Amasty\Finder\Model\Finder\SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        \Magento\Framework\App\ProductMetadataInterface $metaData,
        \Magento\Framework\Filesystem\Driver\File $fileDriver,
        FileValidator $fileValidator,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->configHelper = $configHelper;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->metaData = $metaData;
        $this->fileValidator = $fileValidator;
        $this->fileDriver = $fileDriver;
    }
    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('amasty_finder_finder', 'finder_id');
    }

    /**
     * @TODO Refactoring required
     * @param $finder
     * @param $file
     * @return array
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function importUniversal($finder, $file)
    {
        $listErrors = [];
        $connection = $this->getConnection();
        $finderId = (int) $finder->getId();

        $this->clearUniversalByFinderId($connection, $finder);
        if (empty($file['name'])) {
            return $listErrors;
        }

        $this->fileValidator->validateUniversalFile($file);

        $file = $this->fileDriver->fileOpen($file['tmp_name'], 'r');
        while (($line = $this->fileDriver->fileGetCsv($file, Finder::MAX_LINE, ',', '"')) !== false) {
            foreach ($line as $sku) {
                if (strcasecmp($sku, 'sku') === 0) {
                    continue;
                }
                $connection->insertOnDuplicate($this->getTable('amasty_finder_universal'), [
                    'finder_id' => $finderId,
                    'sku' => trim($sku, "\r\n\t' " . '"'),
                    'pid' => 0
                ]);
            }
        }

        $table1 = $this->getTable('amasty_finder_universal');
        $table2 = $this->getTable('catalog_product_entity');

        $connection->update(
            new \Zend_Db_Expr($table1 . ',' . $table2),
            ['pid' => new \Zend_Db_Expr($table2 . '.entity_id')],
            [$table1 . '.sku=' . $table2 . '.sku' => 0]
        );
        return $listErrors;
    }

    private function clearUniversalByFinderId(AdapterInterface $connection, FinderInterface $finder)
    {
        $finderId = $finder->getFinderId();
        if ($finder->getData(ImportUniversal::IMPORTUNIVERSAL_CLEAR) && $finder->getFinderId()) {
            $connection->delete($this->getTable('amasty_finder_universal'), ['finder_id = ?' => $finderId]);
        }
    }

    /**
     * @return array
     */
    public function updateLinks()
    {
        $connection = $this->getConnection();
        $table1 = $this->getTable('amasty_finder_map');
        $table2 = $this->getTable('catalog_product_entity');

        $connection->update(
            new \Zend_Db_Expr($table1 . ',' . $table2),
            ['pid' => new \Zend_Db_Expr($table2 . '.entity_id')],
            [$table1 . '.sku=' . $table2 . '.sku' => 0]
        );

        $sql = $connection->select()->from($table1, ['sku'])->where('pid=0')->limit(10);
        return $connection->fetchCol($sql);
    }

    /**
     * @param $collection
     * @param $valueId
     * @param $countEmptyDropdowns
     * @param $finderId
     * @param $isUniversal
     * @param $isUniversalLast
     * @return bool
     */
    public function addConditionToProductCollection(
        $collection,
        $valueId,
        $countEmptyDropdowns,
        $finderId,
        $isUniversal,
        $isUniversalLast
    ) {
        /** @var \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection $collection */
        $connection = $this->getConnection();

        $ids = [$valueId];
        for ($i = 0; $i < $countEmptyDropdowns; $i++) {
            $selectChild = $connection->select()
                ->from(['finderValue' => $collection->getTable('amasty_finder_value')], 'value_id')
                ->where('finderValue.parent_id IN (?)', $ids);
            $ids = $connection->fetchCol($selectChild);
        }

        $select = $collection->getSelect();

        if ($isUniversal) {
            // we need sub selects
            $univProducts = $connection->select()
                ->from(
                    ['finderUnivarsal' => $collection->getTable('amasty_finder_universal')],
                    ['sku']
                )
                ->where('finderUnivarsal.finder_id = ?', $finderId);

            $productIdsSelect = $connection->select()
                ->from(['finderMap' => $collection->getTable('amasty_finder_map')], ['sku'])
                ->where('finderMap.value_id IN (?)', $ids);

            $allProducts = $connection->select()->union([$univProducts, $productIdsSelect]);

            $query = $connection->select()->from($allProducts, ['sku']);

            $entityIds = $connection->fetchCol($query);

            if ($isUniversalLast) {
                if ($this->configHelper->isMysqlEngine()) {
                    $from = $select->getPart(\Magento\Framework\DB\Select::FROM);

                    if (!isset($from['finderUnivarsal'])) {
                        $select->distinct()
                            ->joinLeft(
                                ['finderUnivarsal' => $collection->getTable('amasty_finder_universal')],
                                'finderUnivarsal.pid = e.entity_id',
                                []
                            )
                            ->order('IF(ISNULL(finderUnivarsal.pid), 0, 1)');
                    }

                } else {
                    $collection->setOrder('is_universal', Select::SQL_ASC);
                }
            }
        } else {
            $entityIds = $connection->fetchCol($connection->select()
                ->from($collection->getTable('amasty_finder_map'), ['sku'])->where('value_id IN(?)', $ids));
        }

        $this->searchCriteriaBuilderFactory->get()
            ->addCollectionFilter($collection, 'sku', $entityIds);

        return true;
    }

    /**
     * @param $mapId
     * @return bool
     */
    public function isDeletable($mapId)
    {
        $connection = $this->getConnection();
        $table = $this->getTable('amasty_finder_map');
        $selectSql = $connection->select()->from($table)->where('value_id = ?', $mapId);
        $result = $connection->fetchRow($selectSql);

        if (isset($result['value_id'])) {
            if ($result['value_id']) {
                return false;
            }
        }

        $table2 = $this->getTable('amasty_finder_value');
        $selectSql2 = $connection->select()->from($table2)->where('parent_id = ?', $mapId);

        $result2 = $connection->fetchRow($selectSql2);
        if (isset($result2['value_id'])) {
            if ($result2['value_id']) {
                return false;
            }
        }
        return true;
    }
}
