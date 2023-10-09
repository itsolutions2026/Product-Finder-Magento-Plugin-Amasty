<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */
/**
 * Copyright © 2015 Amasty. All rights reserved.
 */


namespace Amasty\Finder\Model;


use Amasty\Finder\Api\Data\KtypeCacheInterface;

class KtypeCache extends \Magento\Framework\Model\AbstractModel implements KtypeCacheInterface
{

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init(\Amasty\Finder\Model\ResourceModel\KtypeCache::class);
    }

    /**
     * @return int
     */
    public function getId(){
        return $this->_getData(KtypeCacheInterface::ID);
    }

    /**
     * @param int $Id
     *
     * @return $this
     */
    public function setId($Id)
    {
        $this->setData(KtypeCacheInterface::ID, $Id);

        return $this;
    }

    /**
     * @return string
     */
    public function getRego()
    {
        return $this->_getData(ktypeCacheInterface::REGO);
    }

    /**
     * @param string $rego
     *
     * @return $this
     */
    public function setRego($rego)
    {
        $this->setData(KtypeCacheInterface::REGO, $rego);

        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->_getData(ktypeCacheInterface::STATE);
    }

    /**
     * @param string $state
     *
     * @return $this
     */
    public function setState($state)
    {
        $this->setData(KtypeCacheInterface::STATE, $state);

        return $this;
    }

    /**
     * @return string
     */
    public function getVin()
    {
        $this->_getData(KtypeCacheInterface::VIN);
    }

    /**
     * @param string $vin
     *
     * @return $this
     */
    public function setVin($vin)
    {
        $this->setData(KtypeCacheInterface::VIN, $vin);

        return $this;
    }


    /**
     * @return string
     */
    public function getContent()
    {
        return $this->_getData(KtypeCacheInterface::CONTENT);
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->setData(KtypeCacheInterface::CONTENT, $content);

        return $this;
    }
}