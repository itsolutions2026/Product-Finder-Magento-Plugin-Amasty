<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Cron;

use Amasty\Finder\Api\ImportHistoryRepositoryInterface;
use Amasty\Finder\Model\ConfigProvider;

class ImportLogHistoryCleaner
{
    /**
     * @var ConfigProvider
     */
    private $configHelper;

    /**
     * @var ImportHistoryRepositoryInterface
     */
    private $historyRepository;

    public function __construct(
        ConfigProvider $configHelper,
        ImportHistoryRepositoryInterface $historyRepository
    ) {
        $this->configHelper = $configHelper;
        $this->historyRepository = $historyRepository;
    }

    public function execute()
    {
        $lifetime = $this->configHelper->getConfigValue('import/archive_lifetime');
        $date = date('Y-m-d H:i:s', strtotime("-{$lifetime} days"));

        $this->historyRepository->clearLogHistory($date);
    }
}
