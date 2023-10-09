<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Plugin\Elasticsearch\SearchAdapter\Filter\Builder;

use Magento\Elasticsearch\Model\Adapter\FieldMapper\FieldMapperResolver;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Search\Request\FilterInterface as RequestFilterInterface;

class Term
{
    public const SKU_FIELD = 'sku';

    /**
     * @var \Magento\Elasticsearch\Model\Adapter\FieldMapper\FieldMapperResolve
     */
    protected $fieldMapper = null;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        if (class_exists(FieldMapperResolver::class)) {
            $this->fieldMapper = $objectManager->create(FieldMapperResolver::class);
        }
    }

    /**
     * @param $subject
     * @param callable $proceed
     * @param RequestFilterInterface $filter
     * @return array
     */
    public function aroundBuildFilter(
        $subject,
        callable $proceed,
        RequestFilterInterface $filter
    ) {
        if (in_array($this->fieldMapper->getFieldName($filter->getField()), [self::SKU_FIELD])
            && ($this->fieldMapper !== null)
        ) {
            $filterQuery = [];
            if ($filter->getValue()) {
                $operator = is_array($filter->getValue()) ? 'terms' : 'term';
                $filterQuery []= [
                    $operator => [
                        $this->fieldMapper->getFieldName($filter->getField()) . '_value' => $filter->getValue(),
                    ],
                ];
            }
            return $filterQuery;
        }
        return $proceed($filter);
    }
}
