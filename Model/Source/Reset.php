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

class Reset implements \Magento\Framework\Option\ArrayInterface
{
    public const VALUE_HOME = 'home';
    public const VALUE_CURRENT = 'current';
    public const VALUE_DEFAULT = 'default';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::VALUE_HOME, 'label' => __('To Home Page')],
            ['value' => self::VALUE_CURRENT, 'label' => __('To The Same Page')],
            ['value' => self::VALUE_DEFAULT, 'label' => __('To The Result Page')]
        ];
    }
}
