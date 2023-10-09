<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Parts Finder for Magento 2
 */
/**
 * Copyright © 2016 Amasty. All rights reserved.
 */

namespace Amasty\Finder\Helper;

class Url
{
    public const FINDER_URL_PARAM = 'find';

    public const REPLACE_FROM_PARTS = [
        '%2F',
        '%2B'
    ];

    public const REPLACE_TO_PARTS = [
        '/',
        '+'
    ];

    /**
     * @var \Magento\Framework\Math\Random
     */
    private $random;

    public function __construct(\Magento\Framework\Math\Random $random)
    {
        $this->random = $random;
    }

    /**
     * @param $targetUrl
     * @param $param
     * @return string
     */
    public function getUrlWithFinderParam($targetUrl, $param, $addRandomParam = false)
    {
        $path = $targetUrl;
        $query = [];

        if (strpos($targetUrl, '?') !== false) {
            list($path, $query) = explode('?', $targetUrl, 2);
            if ($query) {
                $query = explode('&', $query);
                $params = [];
                foreach ($query as $pair) {
                    if (strpos($pair, '=') !== false) {
                        $pair = explode('=', $pair);
                        if ($pair[0] !== 'p') {
                            $params[$pair[0]] = $pair[1];
                        }
                    }
                }
                $query = $params;
            }
        }

        $query[self::FINDER_URL_PARAM] = $param;

        if ($addRandomParam) {
            $query['sid'] = $this->random->getRandomString(10);
        }

        $query = http_build_query($query);
        $query = str_replace(
            self::REPLACE_FROM_PARTS,
            self::REPLACE_TO_PARTS,
            $query
        );

        if ($query) {
            $query = '?' . $query;
        }

        $backUrl = $path . $query;
        $backUrl = urldecode($backUrl);

        return $backUrl;
    }

    /**
     * @param $targetUri
     * @return bool
     */
    public function hasFinderParamInUri($targetUri)
    {
        if (strpos($targetUri, '&' . self::FINDER_URL_PARAM . '=') !== false ||
            strpos($targetUri, '?' . self::FINDER_URL_PARAM . '=') !== false ||
            strpos($targetUri, '&amp;' . self::FINDER_URL_PARAM . '=') !== false
        ) {
            return true;
        }
        return false;
    }
}
