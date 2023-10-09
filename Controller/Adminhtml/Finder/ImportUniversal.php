<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Controller\Adminhtml\Finder;

class ImportUniversal extends \Amasty\Finder\Controller\Adminhtml\Finder
{
    public const IMPORTUNIVERSAL_CLEAR = 'importuniversal_clear';

    /**
     * Dispatch request
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        try {
            $model = $this->finderRepository->getFinderModel();
            $finderId = $this->getRequest()->getParam('id');
            if ($finderId) {
                $model = $this->finderRepository->getById($finderId);
                if ($finderId != $model->getId()) {
                    throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
                }
            }
            $model->setData(self::IMPORTUNIVERSAL_CLEAR, $this->getRequest()->getParam(self::IMPORTUNIVERSAL_CLEAR));
            $resUniversal = $model->importUniversal($this->getRequest()->getFiles('importuniversal_file'));
            foreach ($resUniversal as $errorMesages) {
                $this->messageManager->addErrorMessage($errorMesages);
            }

            //phpcs:ignore
            $this->_redirect('amasty_finder/*/edit', ['id' => $finderId]);
            return;
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $finderId = (int)$this->getRequest()->getParam('id');
            if (!empty($finderId)) {
                //phpcs:ignore
                $this->_redirect('amasty_finder/*/edit', ['id' => $finderId]);
            } else {
                //phpcs:ignore
                $this->_redirect('amasty_finder/*/new');
            }
            return;
        }
    }
}
