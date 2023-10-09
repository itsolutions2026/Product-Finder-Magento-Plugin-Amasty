<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */
/**
 * Copyright © 2015 Amasty. All rights reserved.
 */

namespace Amasty\Finder\Controller\Index;
use Amasty\Finder\Model\KtypeCacheFactory;
use Amasty\Finder\Model\ResourceModel\KtypeCache;
use Amasty\Finder\Model\ResourceModel\KtypeCache\CollectionFactory;

use Magento\Framework\App\Action\Context;
use Magento\Setup\Exception;

class Ktype extends \Magento\Framework\App\Action\Action
{

    /**
     * @var KtypeCacheFactory
     */
    private $ktypeCacheFactory;

    /**
     * @var CollectionFactory
     */
    private $ktypeCacheCollectionFactory;

    /**
     * @var KtypeCache
     */
    private $ktypeCache;

    /**
     * @var \Magento\Framework\App\Action\Contex
     */
    private $context;

    public function __construct(
        Context $context,
        KtypeCacheFactory $ktypeCacheFactory,
        KtypeCache $ktypeCache,
        CollectionFactory $ktypeCacheCollectionFactory
    ) {
        parent::__construct($context);
        $this->context           = $context;
        $this->ktypeCacheFactory = $ktypeCacheFactory;
        $this->ktypeCacheCollectionFactory = $ktypeCacheCollectionFactory;
        $this->ktypeCache        = $ktypeCache;
    }

    public function execute()
    {

        try{
            $search_type = $this->getRequest()->getPostValue('search_type');
            $ktypeCache = $this->ktypeCacheFactory->create();
            $response = '';

            if($search_type === "rego_search"){

                $rego_num = $this->getRequest()->getPostValue('value');
                $state = $this->getRequest()->getPostValue('state');
                $response = $this->fetchByRego($rego_num, $state, $ktypeCache);

            }
            else if ($search_type === "vin_search"){

                $vin_number = $this->getRequest()->getPostValue('value');
                $response = $this->fetchByVin($vin_number, $ktypeCache);

            }

            $cars = json_decode($response, true);

            if(!empty($cars['data']['datacar']) && count($cars['data']['datacar']) > 0){

                $response = array(
                    'status' => true,
                    'count' => count($cars['data']['datacar']),
                    'cars' => $cars['data']['datacar']
                );

                echo json_encode($response);
            }
            else{
                $response = array(
                    'status' => true,
                    'count' => 0
                );
                echo json_encode($response);
            }
            exit;
        }
        catch (Exception $exception){

            $writer = new \Zend_Log_Writer_Stream(BP .'/var/log/customfile.log');
            $logger = new \Zend_Log();
            $logger->addWriter($writer);

            $logger->info(print_r($exception->getMessage(), 1));

            $response = array(
                'status' => false,
                'error_message' => $exception->getMessage()
            );
            echo json_encode($response);
            exit;
        }
    }


    private function fetchByVin($vin_number, $ktypeCache){

        if(empty($vin_number) || !preg_match('/^( ?[a-zA-Z0-9]){17}$/', $vin_number)){
            throw new \Exception('Invalid Vin Number!');
        }

        $ktypeCache->setVin($vin_number);

        $factory = $this->ktypeCacheCollectionFactory->create();
        $factory->addFieldToFilter(\Amasty\Finder\Api\Data\KtypeCacheInterface::VIN, $vin_number);
        $collections = $factory->getData();

        if ($factory->getSize() == 1 && !empty($collections[0]['content'])) {

            $response = $collections[0]['content'];
            $ktypeCacheUpdate = $this->ktypeCacheFactory->create();
            $this->ktypeCache->Load($ktypeCacheUpdate, $vin_number, \Amasty\Finder\Api\Data\KtypeCacheInterface::VIN);
            !empty($ktypeCache->getRego())? $ktypeCacheUpdate->setRego($ktypeCache->getRego()): '';
            !empty($ktypeCache->getState())? $ktypeCacheUpdate->setState($ktypeCache->getState()) : '';
            $this->ktypeCache->save($ktypeCacheUpdate);

        }

        else{

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://54.38.179.43:150/bovsoft.regnum.run?id=298&seccode=fadde8d4428e47f991b8f5ee345331a9&regnum='.$vin_number.'&nameservice=getktypeforvincode',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $response = curl_exec($curl);
            $ktypeCache->setContent($response);
            $ktypeCache->save();
            curl_close($curl);

        }


        return $response;
    }

    private function fetchByRego($rego_num, $state, $ktypeCache){


        $factory = $this->ktypeCacheCollectionFactory->create();

        if(empty($rego_num) && preg_match('/^( ?[a-zA-Z0-9]){1,7}$/', $rego_num) && empty($state)){
            throw new \Exception('Invalid Vin Number!');
        }

        $factory->addFieldToFilter(\Amasty\Finder\Api\Data\KtypeCacheInterface::STATE, $state);
        $factory->addFieldToFilter(\Amasty\Finder\Api\Data\KtypeCacheInterface::REGO, $rego_num);
        $collections = $factory->getData();

        if ($factory->getSize() == 1 && !empty($collections[0]['content'])) {
            $response = $collections[0]['content'];
        } else {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'www.carregistrationapi.com/api/reg.asmx/CheckAustralia?RegistrationNumber=' . $rego_num . '&State=' . $state . '&username=FebestAus',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));

            $response = curl_exec($curl);

            $processed = simplexml_load_string($response);
            $vin_number = json_decode($processed->vehicleJson)->VechileIdentificationNumber;

            $ktypeCache->setState($state);
            $ktypeCache->setRego($rego_num);

            $response = $this->fetchByVin($vin_number, $ktypeCache);
        }

        return $response;
    }
}
