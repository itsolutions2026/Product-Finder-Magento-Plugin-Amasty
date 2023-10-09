<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Plugin\Elasticsearch\Model\Adapter;

class AdditionalFieldMapper
{
    public const ES_DATA_TYPE_STRING = 'string';
    public const FIELD_NAME = 'sku_value';

    /**
     * @param mixed $subject
     * @param array $result
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetAllAttributesTypes($subject, array $result)
    {
        $result[self::FIELD_NAME] = ['type' => self::ES_DATA_TYPE_STRING, "index" => "not_analyzed"];
        return $result;
    }
}
