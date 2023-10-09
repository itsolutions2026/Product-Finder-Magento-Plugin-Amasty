<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Model\Source;

class DisplayType implements \Magento\Framework\Option\ArrayInterface
{
    public const DROPDOWN = 0;
    public const LABEL = 1;
    public const IMAGE_LABEL = 2;
    public const IMAGE = 3;

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::DROPDOWN, 'label' => __('Dropdown')],
            ['value' => self::LABEL, 'label' => __('Label')],
            ['value' => self::IMAGE_LABEL, 'label' => __('Image and Label')],
            ['value' => self::IMAGE, 'label' => __('Image')]
        ];
    }
}
