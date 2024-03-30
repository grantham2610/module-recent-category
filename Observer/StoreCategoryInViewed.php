<?php
/**
 *   Copyright © Grantham, Inc. All rights reserved.
 *
 */
declare(strict_types=1);

namespace Grantham\RecentCategory\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Registry;
use Grantham\RecentCategory\Helper\Data;

class StoreCategoryInViewed implements ObserverInterface
{
    /**
     * @var Registry
     */
    protected $registry;
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @param Registry $registry
     * @param Data $helper
     */
    public function __construct(
        Registry $registry,
        Data $helper

    ) {
        $this->registry = $registry;

        $this->helper = $helper;
    }

    /**
     * Handler for catalog_controller_category_init_after event.
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if ($this->helper->isModuleEnabled()) {
            $categoryId = $this->registry->registry('current_category')->getId();

            $visitedCategories = json_decode($_COOKIE['visited_categories'] ?? '[]', true);
            $visitedCategories[] = $categoryId;
            $visitedCategories = array_unique($visitedCategories);
            $viewedCategories = array_slice($visitedCategories, -3);
            $data = json_encode($viewedCategories);
            setcookie('visited_categories', $data, time() + 86400, '/');
            echo "<script>localStorage.setItem('viewed_categories', '$data');</script>";
        }
    }
}
