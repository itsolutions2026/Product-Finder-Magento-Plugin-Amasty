<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Model\Elasticsearch\Adapter\DataMapper;

use Amasty\Finder\Model\Elasticsearch\Adapter\DataMapperInterface;
use Amasty\Finder\Model\ConfigProvider;
use Magento\Framework\App\ResourceConnection;

class Universal implements DataMapperInterface
{
    /**
     * @var ConfigProvider
     */
    private $config;

    /**
     * @var ResourceConnection
     */
    private $resource;

    public function __construct(
        ConfigProvider $config,
        ResourceConnection $resource
    ) {
        $this->config = $config;
        $this->resource = $resource;
    }

    public function map(array $document): array
    {
        $connection = $this->resource->getConnection();

        $univProducts = $connection->select()
            ->from(
                ['finderUnivarsal' => $this->resource->getTableName('amasty_finder_universal')],
                ['sku']
            );

        $query = $connection->select()->from($univProducts, ['sku']);
        $entityIds = $connection->fetchCol($query);
        $value = (int)in_array($document['sku'], $entityIds);

        return ['is_universal' => $value];
    }

    public function isAllowed(): bool
    {
        return $this->config->isUneversalLast();
    }
}
