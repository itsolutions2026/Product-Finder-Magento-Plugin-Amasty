<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Setup\Patch\Data;

use Amasty\Base\Helper\Deploy;
use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class DeployImageDirectory implements DataPatchInterface, PatchVersionInterface
{
    /**
     * @var Deploy
     */
    private $deployHelper;

    /**
     * @var DriverInterface
     */
    private $driverInterface;

    public function __construct(
        Deploy $deployHelper,
        DriverInterface $driverInterface
    ) {
        $this->deployHelper = $deployHelper;
        $this->driverInterface = $driverInterface;
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): void
    {
        $this->deployHelper->deployFolder(
            $this->driverInterface->getParentDirectory(__DIR__) . '/../../pub'
        );
    }

    public static function getVersion(): string
    {
        return '1.9.4';
    }
}
