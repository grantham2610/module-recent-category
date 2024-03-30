<?php
/**
 *   Copyright © Grantham, Inc. All rights reserved.
 *
 */
declare(strict_types=1);

namespace Grantham\RecentCategory\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    public const MODULE_GENERAL_PATH = 'recent_category/general/enabled';
    public const DEFAULT_CATEGORIES_PATH = 'recent_category/general/categories';

    /**
     * Get Configuration value.
     * @param string $path
     * @return mixed
     */
    public function getConfigValue($path)
    {
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check if module is enabled or not
     *
     * @return bool
     */
    public function isModuleEnabled()
    {
        return (bool)$this->getConfigValue(self::MODULE_GENERAL_PATH);
    }

    /**
     * Get Categories Ids from the configuration
     *
     * @return mixed
     */
    public function getDefaultCategories()
    {
        return $this->getConfigValue(self::DEFAULT_CATEGORIES_PATH);

    }



}
