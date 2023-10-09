<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Api\Data;

interface MapInterface
{
    /**
     * Constants defined for keys of data array
     */
    public const ID = 'id';
    public const VALUE_ID = 'value_id';
    public const PID = 'pid';
    public const SKU = 'sku';

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id);

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
    public function getPid();

    /**
     * @param int $pid
     *
     * @return $this
     */
    public function setPid($pid);

    /**
     * @return string
     */
    public function getSku();

    /**
     * @param string $sku
     *
     * @return $this
     */
    public function setSku($sku);
}
