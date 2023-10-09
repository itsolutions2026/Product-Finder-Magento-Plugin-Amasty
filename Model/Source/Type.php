<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */
/**
 * Copyright © 2015 Amasty. All rights reserved.
 */
namespace Amasty\Finder\Model\Source;

class Type implements \Magento\Framework\Option\ArrayInterface
{
    public const ONE_DROPDOWN_SELECTED = 1;
    public const ALL_DROPDOWNS_SELECTED = 0;

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::ONE_DROPDOWN_SELECTED, 'label' => __('At Least One Finder Value is Selected')],
            ['value' => self::ALL_DROPDOWNS_SELECTED, 'label' => __('All Finder Values are Selected')]
        ];
    }
}
