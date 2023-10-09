<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */

namespace Amasty\Finder\Api\Data;

interface ImportErrorsInterface
{
    /**
     * Constants defined for keys of data array
     */
    public const ERROR_ID = 'error_id';
    public const IMPORT_FILE_LOG_ID = 'import_file_log_id';
    public const IMPORT_FILE_LOG_HISTORY_ID = 'import_file_log_history_id';
    public const CREATED_AT = 'created_at';
    public const LINE = 'line';
    public const MESSAGE = 'message';

    /**
     * @return int
     */
    public function getErrorId();

    /**
     * @param int $errorId
     *
     * @return $this
     */
    public function setErrorId($errorId);

    /**
     * @return int|null
     */
    public function getImportFileLogId();

    /**
     * @param int|null $importFileLogId
     *
     * @return $this
     */
    public function setImportFileLogId($importFileLogId);

    /**
     * @return int|null
     */
    public function getImportFileLogHistoryId();

    /**
     * @param int|null $importFileLogHistoryId
     *
     * @return $this
     */
    public function setImportFileLogHistoryId($importFileLogHistoryId);

    /**
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * @param string|null $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * @return int
     */
    public function getLine();

    /**
     * @param int $line
     *
     * @return $this
     */
    public function setLine($line);

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param string $message
     *
     * @return $this
     */
    public function setMessage($message);
}
