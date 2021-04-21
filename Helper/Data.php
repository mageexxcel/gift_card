<?php
namespace Excellence\GiftCard\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;
use \Psr\Log\LoggerInterface;
use \Magento\Framework\Mail\Template\TransportBuilder;
use \Magento\Framework\Translate\Inline\StateInterface;


class Data extends AbstractHelper
{
    /**
     * @var \Magento\Framework\Math\Random
     */
    protected $_math;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $_time;
    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $_order;
    /**
     * @var \Excellence\GiftCard\Model\GiftCardFactory
     */
    protected $_giftCard;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;
    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $_inlineTranslation;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $_timeZone;

    const TEMPLATE_ID = "excellence_giftcard_template";
    const PRODUCT_TYPE = "excellence_giftcard";
    const MODULE_STATUS = "excellence_giftcard/excellence_giftcard_setting/enable_control";
    
    public function __construct(
        \Magento\Framework\Math\Random $mathRandom,
        \Magento\Sales\Model\Order $orderRepository,
        \Excellence\GiftCard\Model\GiftCardFactory $giftCard,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $time,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        LoggerInterface $logger,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        \Magento\Directory\Model\Currency $currency,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timeZone
    ){
        $this->_order = $orderRepository;
        $this->_math = $mathRandom;
        $this->_date = $date;
        $this->_storeManager = $storeManager;
        $this->_time = $time;
        $this->_giftCard = $giftCard;
        $this->_logger = $logger;
        $this->_transportBuilder = $transportBuilder;
        $this->_inlineTranslation = $inlineTranslation;
        $this->_currency = $currency;
        $this->_timeZone = $timeZone;
    }
    /**
     * @return order object
     */
    public function loadOrderByid($id){
        return $this->_order->load($id);
    }
    /**
     * @return short hand formatted date like 17/09/2019
     */
    public function getFormattedDate(){
        return $this->_time->formatDate($date = null,$format = \IntlDateFormatter::SHORT,$showTime = false);
    }
    /**
     * Get currency symbol for current locale and currency code
     *
     * @return string
     */ 
    public function getCurrentCurrencySymbol()
    {
        return $this->_currency->getCurrencySymbol();
    }
    /**
     * @return formatted time like 20:30:12
     */
    public function getFormattedTime(){
        $time = (new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);
        $time = date('h:i A', strtotime($time));
        return $time;
    }
    /**
     * @param string length of coupon and any specified character
     * @return string alphanumeric coupon code of specified length 
     */
    public function generateCouponCode($length,  $chars = null){
        return $this->_math->getRandomString($length, $chars);
    }
    /**
     * @return void this method send email to all peding created order
     */
    public function sendEmailViaCronJob()
    {
        try {
            $couponCollection = $this->_giftCard->create()
                                        ->getCollection()
                                        ->addFieldToFilter("is_mail_sent",["eq"=>0]);
            $templateId = self::TEMPLATE_ID;
            $storeName = $this->_storeManager->getStore()->getName();
            foreach($couponCollection as $couponDetails){
                $senderEmail = $couponDetails->getsenderEmail();
                $toMail = $couponDetails->getreceiverEmail();
                $senderName = $couponDetails->getsenderName();
                $receiverName = $couponDetails->getreceiverName();
                $couponCode = $couponDetails->getcouponCode();
                $cardValue = $couponDetails->getcardValue();
                $sendDate = $couponDetails->getDate();
                $optionalMessage = $couponDetails->getoptionalMessage();
                $sendDate = $this->_timeZone->date(new \DateTime($sendDate))->format('Y-m-d');
                $today = $this->_timeZone->date(($this->getFormattedDate()))->format('Y-m-d');
                
                //validating date before sending mail
                if($today == $sendDate){
                    //valida date found! sending mail now.
                    $from = array('email' => (string) $senderEmail, 'name' => (string) $senderName);
                    $this->_inlineTranslation->suspend();
                    if(empty($optionalMessage)){
                        $transport = $this->_transportBuilder
                        ->setTemplateIdentifier($templateId)
                        ->setTemplateOptions(
                            [
                                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                                'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                            ]
                        )
                        ->setTemplateVars([
                            'subject' => 'Welcome',
                            'customerEmail' => $toMail,
                            'customerName' => $receiverName,
                            'couponCode' => $couponCode,
                            'couponValue' => $this->getCurrentCurrencySymbol().' '.number_format((float) $cardValue, 2, '.', ''),
                            'senderName' => $senderName,
                            'senderEmail' => $senderEmail,
                        ])
                        ->setFrom($from)
                        ->addTo($toMail, $receiverName)
                        ->getTransport();
                    }else{
                        $transport = $this->_transportBuilder
                        ->setTemplateIdentifier($templateId)
                        ->setTemplateOptions(
                            [
                                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                                'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                            ]
                        )
                        ->setTemplateVars([
                            'subject' => 'Welcome',
                            'customerEmail' => $toMail,
                            'customerName' => $receiverName,
                            'couponCode' => $couponCode,
                            'couponValue' => $this->getCurrentCurrencySymbol().' '.number_format((float) $cardValue, 2, '.', ''),
                            'senderName' => $senderName,
                            'senderEmail' => $senderEmail,
                            'optionalMessage' => $optionalMessage,
                        ])
                        ->setFrom($from)
                        ->addTo($toMail, $receiverName)
                        ->getTransport();                        
                    }
                    $transport->sendMessage();
                    $this->_inlineTranslation->resume();

                    //updating email_send_status
                    $couponDetails->setisMailSent(1);
                    $couponDetails->save();
                }
                $this->_logger->info('All Due GiftCard Sent Via Cron Dated Till Today :-- Excellence-GiftCard');
            }
        } catch (Exception $ex) {
            $this->_logger->info('Problem While Sending Mail Via Cron !');
            echo $ex->getMessage();
        }
    }
    // Get BaseUrl
    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }
}
?>