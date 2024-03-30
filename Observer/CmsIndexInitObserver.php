<?php
/**
 *   Copyright © Grantham, Inc. All rights reserved.
 *
 */
declare(strict_types=1);

namespace Grantham\RecentCategory\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Grantham\RecentCategory\Helper\Data;

class CmsIndexInitObserver implements ObserverInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Data $helper
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Data $helper
    ) {

        $this->scopeConfig = $scopeConfig;
        $this->helper = $helper;
    }

    /**
     * Method to set and get localstorage from configuration
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if ($this->helper->isModuleEnabled()) {
            // Check if cookies and local storage already have values and if keys exist
            $cookieValue = isset($_COOKIE['visited_categories']) ? $_COOKIE['visited_categories'] : null;
            $localStorageValue = "<script>localStorage.getItem('viewed_categories');</script>";

            if ((!$cookieValue || !$localStorageValue) && !($this->isCookieSet('visited_categories'))) {

                $categoryIds = explode(',', trim($this->helper->getDefaultCategories()
                ), ',');
                // Remove empty values
                $categoryIds = array_filter($categoryIds);
                // Remove duplicate values
                $categoryIds = array_unique($categoryIds);

                // Fetch the last 3 category IDs from the array
                $viewedCategories = array_slice($categoryIds, -3);

                // Encode the category IDs to JSON
                $data = json_encode($viewedCategories);

                // Set the cookies with the category IDs
                setcookie('visited_categories', $data, time() + 86400, '/');

                // Set the local storage with the category IDs
                echo "<script>localStorage.setItem('viewed_categories', '$data');</script>";
            } else {
                error_log("no need to execute", 3, 'kanan.log');
            }
        }
    }
    // Function to check if the cookie is set
    private function isCookieSet($key)
    {
        return isset($_COOKIE[$key]);
    }
}
