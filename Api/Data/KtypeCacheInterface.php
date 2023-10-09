<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Api\Data;


interface KtypeCacheInterface
{
    /**
     * Constants defined for keys of data array
     */
    public const ID = 'id';
    public const REGO = 'rego';
    public const STATE = 'state';
    public const VIN = 'vin';
    public const DATE_ADDED = 'date_added';
    public const CONTENT = 'content';


    /**
     * @return int
     */

    public function getId();

    /**
     * @param int $Id
     *
     * @return $this
     */
    public function setId($Id);

    /**
     * @return string
     */
    public function getRego();

    /**
     * @param string $rego
     *
     * @return $this
     */
    public function setRego($rego);

    /**
     * @return string
     */
    public function getVin();

    /**
     * @param string $vin
     *
     * @return $this
     */
    public function setVin($vin);

    /**
     * @return string
     */
    public function getContent();

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent($content);
}