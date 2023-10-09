<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Plugin\Elasticsearch\Elasticsearch5\Model\Adapter\FieldMapper;

class AdditionalFieldMapper
{
    public const FIELD_NAME_SKU_VALUE = 'sku_value';
    public const ATTRIBUTE_TYPE_KEYWORD = 'keyword';
    public const ATTRIBUTE_TYPE_TEXT = 'text';
    public const FIELD_NAME_SKU = 'sku';

    /**
     * @param mixed $subject
     * @param array $result
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetAllAttributesTypes($subject, array $result): array
    {
        $result[self::FIELD_NAME_SKU_VALUE] = ['type' => self::ATTRIBUTE_TYPE_KEYWORD];
        $result[self::FIELD_NAME_SKU] += ['type' => self::ATTRIBUTE_TYPE_TEXT, 'fielddata' => true];

        return $result;
    }
}
