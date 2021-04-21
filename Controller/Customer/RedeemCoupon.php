<?php

namespace Excellence\GiftCard\Controller\Customer;

use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Session;
use \Psr\Log\LoggerInterface;

class RedeemCoupon extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_session;
    /**
     * @var \Magento\Framework\Controller\Result\Redirect
     */
    protected $_resultRedirect;
    /**
     * @var \Excellence\GiftCard\Model\GiftCardFactory
     */
    protected $_giftCard;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;
    /**
     * @var \Excellence\StoreCredit\Model\StorecreditFactory
     */
    protected $_credit;
    /**
     * @var \Excellence\GiftCard\Helper\Data
     */
    protected $_helper;
    /**
     * @var \Excellence\StoreCredit\Helper\Data
     */
    protected $_cerditHelper;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $_customerRepositoryInterface;

    protected $_storeManager;

    protected $_messageManager;

    public function __construct(
        Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Customer\Model\Session $session,
        \Magento\Framework\Controller\Result\Redirect $resultRedirect,
        \Excellence\GiftCard\Model\GiftCardFactory $giftCard,
        LoggerInterface $logger,
        \Excellence\StoreCredit\Model\StorecreditFactory $credit,
        \Excellence\GiftCard\Helper\Data $helper,
        \Excellence\StoreCredit\Helper\Data $creditHelper,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface
    ){
        $this->_resultRedirect = $resultRedirect;
        $this->_session = $session;
        $this->_pageFactory = $pageFactory;
        $this->_giftCard = $giftCard;
        $this->_logger = $logger;
        $this->_credit = $credit;
        $this->_helper = $helper;
        $this->_cerditHelper = $creditHelper;
        $this->_customerSession = $customerSession;
        $this->_storeManager = $storeManager;
        $this->_messageManager = $messageManager;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
        parent::__construct($context);
    }

    /**
     * 
     * 
     */
    public function execute(){
        $requestedCoupon = $this->getRequest()->getPostValue('giftcard');
        $found = false;
        try {
                $couponCollection = $this->_giftCard->create()
                                        ->getCollection()
                                        ->addFieldToFilter("is_coupon_used",["eq"=>0]);
                foreach($couponCollection as $item){
                    $giftCardValue = $item->getcardValue();
                    $actualCoupon = $item->getcouponCode();
                    if($actualCoupon == $requestedCoupon){
                        $item->setisCouponUsed(1);
                        $item->save();
                        $this->updateStoreCerdit($giftCardValue,$actualCoupon);
                        $this->_messageManager->addSuccess("Coupon Redeemed Successfully !");
                        $this->_redirect('storecredit/customer/index');
                        $found = true;
                        break;
                    }
                }
                if(!count($couponCollection) || $found == false){
                    $this->_messageManager->addError("Invalid coupon or already used before !");
                    $this->_redirect('storecredit/customer/index');
                }
                $this->_logger->info('Succesfully Redeemed coupon code '.$requestedCoupon.' :-- Excellence-GiftCard');
            } catch (Exception $ex) {
                $this->_logger->info('Problem While Redeeming Coupon Code !');
                echo $ex->getMessage();
            }
    }
    /**
     * 
     */
    public function updateStoreCerdit($giftCardValue,$couponCode){
        
        $customer = $this->_customerRepositoryInterface->getById($this->_customerSession->getcustomerId());
        $customerName = $customer->getfirstName() ." ". $customer->getlastName();
        $customerEmail = $customer->getEmail();
        $store = $this->_storeManager->getStore()->getFrontendName();

        $message = 'Congratulation! Your account has been credited with ' . $giftCardValue . ' store credit points against redeeming coupon code ' . $couponCode;
        $reason = "Redeemed Coupon Code <strong>" . $couponCode."</strong> Successfully.";
        $emailTempVar = array('name' => $customerName, 'email' => $customerEmail, 'message' => $message, 'reason' => $reason, 'subject' => $store . ' credit');
        $model = $this->_credit->create();
        $model->setData('time', $this->_helper->getFormattedTime());
        $model->setData('credit', $giftCardValue);
        $model->setData('date', $this->_helper->getFormattedDate());
        $model->setData('reason', $reason);
        $model->setData('customer_id', $this->_customerSession->getcustomerId());
        $model->save();
        $this->_cerditHelper->sendEmail($emailTempVar);
    }
}
