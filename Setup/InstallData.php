<?php

namespace Excellence\GiftCard\Setup;

use Magento\Cms\Model\PageFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    private $pageFactory;
    private $blockFactory;
    protected $_dataHelper;

    public function __construct(PageFactory $pageFactory,
    \Excellence\GiftCard\Helper\Data $dataHelper)
    {
        $this->pageFactory = $pageFactory;
        $this->_dataHelper = $dataHelper;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        // Url for customer Login
        $redeemPageUrl = $this->_dataHelper->getBaseUrl().'customer/account/';
        $pageContent = "<div>
                <div>
                    <ul>
                        <li>Go To Your Account or <a href=".$redeemPageUrl.">Click Here. </a></li>
                        <li>Select/Click on Gift Cards Menu Link. </li>
                        <li>Here You Will Find a Reedem Option, Simply Put that code you received In Your Mail and Click on Reedem Button. </li>
                        <li>The Amount Will Get Added in Your Account. </li>
                        <li><strong>To Check:</strong> Just Click On StoreCredit Menu From Menu From My Account. </li>
                        <li>There You Will Find Amount Of You Coupon That You Reedemed. </li>
                        <li>Now Just Add Product in Your Shopping Cart and On Checkout Page Apply Your Credit Amount and Place an Order. </li>
                    </ul>
                </div>
            </div>"; // page content
        $redeemCmsPageData = [
            'title' => 'Gift Card Redeemtion', // cms page title
            'page_layout' => '1column', // cms page layout
            'meta_keywords' => 'Redeem Coupon', // cms page meta keywords
            'meta_description' => 'Redeem Coupon Code Steps', // cms page description
            'identifier' => 'gift-card-redeem', // cms page url identifier
            'content_heading' => 'Steps To Redeem Gift Card Coupon Code', // Page heading
            'content' =>  $pageContent, 
            'is_active' => 1, // define active status
            'stores' => [0], // assign to stores
            'sort_order' => 0 // page sort order
        ];

        // create page
        $this->pageFactory->create()->setData($redeemCmsPageData)->save();
    }
}