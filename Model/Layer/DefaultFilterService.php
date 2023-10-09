<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Model\Layer;

use Amasty\Finder\Api\FinderRepositoryInterface;
use Amasty\Finder\Model\ConfigProvider;
use Amasty\Finder\Model\Finder\Query\GetFinderIdByValueId;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Resolver as LayerResolver;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;

class DefaultFilterService implements FilterServiceInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var FinderRepositoryInterface
     */
    private $finderRepository;

    /**
     * @var ConfigProvider
     */
    private $configHelper;

    /**
     * @var Layer
     */
    private $layer;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var GetFinderIdByValueId
     */
    private $getFinderIdByValueId;

    /**
     * @var bool
     */
    private $filtersApplied = false;

    public function __construct(
        RequestInterface $request,
        FinderRepositoryInterface $finderRepository,
        ConfigProvider $configHelper,
        LayerResolver $layerResolver,
        UrlInterface $urlBuilder,
        GetFinderIdByValueId $getFinderIdByValueId
    ) {
        $this->request = $request;
        $this->finderRepository = $finderRepository;
        $this->configHelper = $configHelper;
        $this->layer = $layerResolver->get();
        $this->urlBuilder = $urlBuilder;
        $this->getFinderIdByValueId = $getFinderIdByValueId;
    }

    public function apply(): void
    {
        if ($this->filtersApplied) {
            return;
        }

        foreach ($this->getValues() as $valueId) {
            if (!$valueId) {
                continue;
            }

            $finderId = $this->getFinderIdByValueId->execute($valueId);
            $finder = $this->finderRepository->getById($finderId);

            $current = $finder->getSavedValue('current');
            if ($current != $valueId) {
                // url has higher priority than session
                $dropdowns = $finder->getDropdownsByCurrent($valueId);
                $finder->saveFilter($dropdowns, $this->getCurrentCategoryId(), [$this->getCurrentApplyUrl()]);
            }

            $isUniversal = (bool) $this->configHelper->getConfigValue('advanced/universal');
            $isUniversalLast = (bool) $this->configHelper->getConfigValue('advanced/universal_last');

            $finder->applyFilter($this->layer, $isUniversal, $isUniversalLast);
        }

        $this->filtersApplied = true;
    }

    private function getValues(): array
    {
        $valueIds = [];

        $urlParams = $this->request->getParam('find', '');
        $urlParams = explode('&', $urlParams);

        foreach ($urlParams as $urlParam) {
            if ($urlParam) {
                $valueIds[] = $this->parseUrlParam($urlParam);
            }
        }

        return $valueIds;
    }

    /**
     * Get last `number` part from the year-make-model-number.html string
     *
     * @param string $param like year-make-model-number.html
     * @return int like number
     */
    private function parseUrlParam(string $param): int
    {
        $sep = (string) $this->configHelper->getConfigValue('general/separator');
        $suffix = (string) $this->configHelper->getConfigValue('general/suffix');

        $param = explode($sep, $param);
        $param = str_replace($suffix, '', $param[count($param) - 1]);

        return (int) $param;
    }

    private function getCurrentCategoryId(): int
    {
        return (int) $this->layer->getCurrentCategory()->getId();
    }

    private function getCurrentApplyUrl(): string
    {
        $currentUrl = $this->urlBuilder->getCurrentUrl();
        $currentUrl = explode('?', $currentUrl);

        return array_shift($currentUrl);
    }
}
