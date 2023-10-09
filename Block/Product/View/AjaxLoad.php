<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Block\Product\View;

class AjaxLoad extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /**
     * @var \Amasty\Finder\Model\ConfigProvider
     */
    private $configHelper;

    /** @var \Magento\Framework\Json\EncoderInterface */
    private $jsonEncoder;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Amasty\Finder\Model\ConfigProvider $configHelper,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->configHelper = $configHelper;
        $this->jsonEncoder = $jsonEncoder;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->configHelper->getConfigValue('advanced/show_active_finder_options')) {
            return '';
        }

        $this->setTitle($this->configHelper->getConfigValue('advanced/name_finder_options_tab'));

        return parent::_toHtml();
    }

    /**
     * @return string
     */
    public function getJsonConfig()
    {
        $productId = $this->coreRegistry->registry('product')->getId();
        $securedFlag = $this->_storeManager->getStore()->isCurrentlySecure();
        $url = $this->getUrl('amfinder/product/options', ['_secure' => $securedFlag]);

        return $this->jsonEncoder->encode(['ajaxUrl' => $url, 'productId' => $productId]);
    }
}
