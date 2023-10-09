<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Api\Data;

interface ValueInterface
{
    /**
     * Constants defined for keys of data array
     */
    public const VALUE_ID = 'value_id';
    public const VID = 'vid';
    public const PARENT_ID = 'parent_id';
    public const DROPDOWN_ID = 'dropdown_id';
    public const NAME = 'name';

    /**
     * @return int
     */
    public function getValueId();

    /**
     * @param int $valueId
     *
     * @return $this
     */
    public function setValueId($valueId);

    /**
     * @return int
     */
    public function getParentId();

    /**
     * @param int $parentId
     *
     * @return $this
     */
    public function setParentId($parentId);

    /**
     * @return int
     */
    public function getDropdownId();

    /**
     * @param int $dropdownId
     *
     * @return $this
     */
    public function setDropdownId($dropdownId);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name);
}
