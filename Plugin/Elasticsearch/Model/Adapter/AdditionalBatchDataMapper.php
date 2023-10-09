<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Plugin\Elasticsearch\Model\Adapter;

use Amasty\Finder\Model\Elasticsearch\Adapter\DataMapperInterface;

class AdditionalBatchDataMapper
{
    public const FIELD_NAME = 'sku_value';
    public const DOCUMENT_FIELD_NAME = 'sku';
    public const INDEX_DOCUMENT = 'document';

    /**
     * @var DataMapperInterface[]
     */
    private $dataMappers = [];

    public function __construct(
        array $dataMappers = []
    ) {
        $this->dataMappers = $dataMappers;
    }

    /**
     * Prepare index data for using in search engine metadata.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @param $subject
     * @param callable $proceed
     * @param array $documentData
     * @param $storeId
     * @param array $context
     * @return array
     */
    public function aroundMap(
        $subject,
        callable $proceed,
        array $documentData,
        $storeId,
        $context = []
    ) {
        $documentData = $proceed($documentData, $storeId, $context);

        foreach ($documentData as $productId => $document) {
            foreach ($this->dataMappers as $mapper) {
                if ($mapper instanceof DataMapperInterface && $mapper->isAllowed()) {
                    //@codingStandardsIgnoreLine
                    $document = array_merge($document, $mapper->map($document));
                }
            }

            $value = $document[self::DOCUMENT_FIELD_NAME];
            $document[self::FIELD_NAME] = $value;

            $documentData[$productId] = $document;
        }

        return $documentData;
    }
}
