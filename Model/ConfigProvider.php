<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Model;

use Magento\Framework\App\Cache\StateInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\CollectionDataSourceInterface;
use Magento\Framework\Search\EngineResolverInterface;
use Magento\PageCache\Model\Config as PageCacheConfig;
use Magento\Store\Model\ScopeInterface;

class ConfigProvider extends \Amasty\Base\Model\ConfigProviderAbstract implements CollectionDataSourceInterface
{
    public const ADVANCED_UNIVERSAL_LAST = 'advanced/universal_last';
    public const PAGE_CACHE_TYPE = 'full_page';
    public const SYSTEM_FPC_CACHING_APPLICATION = 'system/full_page_cache/caching_application';

    /**
     * @var string
     */
    protected $pathPrefix = 'amfinder/';

    /**
     * @var StateInterface
     */
    private $cacheState;

    /**
     * @var EngineResolverInterface
     */
    private $engineResolver;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StateInterface $cacheState,
        EngineResolverInterface $engineResolver
    ) {
        parent::__construct($scopeConfig);
        $this->cacheState = $cacheState;
        $this->engineResolver = $engineResolver;
    }

    /**
     * @param string|int $storeId
     * @return string|bool|int
     */
    public function getConfigValue(string $path, $storeId = null)
    {
        return $this->getValue($path, $storeId);
    }

    public function isVarnish(): bool
    {
        $cachingApplication =  $this->scopeConfig->getValue(
            self::SYSTEM_FPC_CACHING_APPLICATION,
            ScopeInterface::SCOPE_STORE
        );
        return $this->cacheState->isEnabled(self::PAGE_CACHE_TYPE)
            && $cachingApplication == PageCacheConfig::VARNISH;
    }

    public function isUneversalLast(): bool
    {
        return (bool)$this->getConfigValue(self::ADVANCED_UNIVERSAL_LAST);
    }

    public function isMysqlEngine(): bool
    {
        return (bool)strpos($this->engineResolver->getCurrentSearchEngine(), 'mysql') !== false;
    }
}
