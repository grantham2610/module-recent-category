<?php
/**
 *   Copyright © Grantham, Inc. All rights reserved.
 *
 */
declare(strict_types=1);

namespace Grantham\RecentCategory\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class RecentlyViewedCategories extends Template
{
    protected $_jsonEncoder;

    public function __construct(
        Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        array $data = []
    ) {
        $this->_jsonEncoder = $jsonEncoder;
        parent::__construct($context, $data);
    }

    public function getRecentlyViewedCategoryIds()
    {
        $localStorageKey = 'viewed_categories'; // Adjust this to match your localStorage key
        $recentlyViewedCategories = json_decode($_COOKIE[$localStorageKey] ?? '[]', true);
        return $recentlyViewedCategories;
    }

}
