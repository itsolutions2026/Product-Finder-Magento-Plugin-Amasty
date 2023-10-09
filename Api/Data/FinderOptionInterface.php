<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Api\Data;

interface FinderOptionInterface
{
    public const ID = 'id';
    public const VALUE = 'value';

    /**
     * @return int
     */
    public function getDropdownId();

    /**
     * @param int $id
     * @return mixed
     */
    public function setDropdownId($id);

    /**
     * @return string
     */
    public function getValue();

    /**
     * @param string $value
     * @return mixed
     */
    public function setValue($value);
}
