<?php
namespace Excellence\GiftCard\Observer;
use Magento\Framework\Event\ObserverInterface;
 
class CreatingGiftCard implements ObserverInterface
{
    /**
     * @var \Excellence\GiftCard\Helper\Data
     */
    protected $_helper;
    /**
     * @var \Excellence\GiftCard\Model\GiftCardFactory
     */
    protected $_giftCard;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $_productRepo;
    
    public function __construct(
        \Excellence\GiftCard\Helper\Data $dataHelper,
        \Excellence\GiftCard\Model\GiftCardFactory $giftCard,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\ProductRepository  $productRepository,
        \Excellence\GiftCard\Helper\Data $giftCardHelper
    ){
        $this->_giftCard =$giftCard;
        $this->_helper = $dataHelper;
        $this->_scopeConfig = $scopeConfig;
        $this->_productRepo = $productRepository;
    }
    /**
     * @return product type
     */
    public function getProductTypeByProductId($productId){
        return $this->_productRepo->getById($productId)->getTypeId();
    }

    public function execute(\Magento\Framework\Event\Observer $observer){
        $order = $observer->getEvent()->getOrder();
        $orderId = $observer->getEvent()->getOrder()->getId();
        $isEnable = $this->_scopeConfig->getValue($this->_helper::MODULE_STATUS, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($isEnable){
            foreach($order->getAllItems() as $item){
                $productId = $item->getProductId();
                $productType = $this->getProductTypeByProductId($productId);
                $productOptions = [];
                if($productType == $this->_helper::PRODUCT_TYPE){
                    $options = $item->getProductOptions();        
                    if (isset($options['options']) && !empty($options['options'])) {        
                        foreach ($options['options'] as $option) {
                            if($option['option_type'] == 'drop_down'){
                                continue;
                            }
                            array_push($productOptions,$option['option_value']);
                        }
                    }
                    $this->generateDynamicCoupon($productOptions,$orderId,$item->getPrice());
                }
            }
        }
    }
    /**
     * stores entries on order place if product belongs to gift card category
     */
    public function generateDynamicCoupon($productOptions,$orderId,$couponValue){
        try{
            $orderCustomOptionCollection = $productOptions;
            $couponCode = $this->_helper->generateCouponCode(12);
            $time = $this->_helper->getFormattedTime();
            $date = $this->_helper->getFormattedDate();
            //checking if optional message set or not
            if(count($orderCustomOptionCollection) == 6){
                $data = array("sender_name","sender_email","receiver_name","receiver_email","optional_message","date");
                $first_chunck = array_combine($data,$orderCustomOptionCollection);
            }else{
                $data = array("sender_name","sender_email","receiver_name","receiver_email","date");
                $first_chunck = array_combine($data,$orderCustomOptionCollection);
            }
            $second_chunck = array(
                "time"  =>  $time,
                "coupon_code"   => $couponCode,
                "card_value"    => $couponValue,
                "order_id"  =>  $orderId
            );
            $final_array = array_merge($first_chunck,$second_chunck);
            $giftModel = $this->_giftCard->create();
            $giftModel->setData($final_array);
            $giftModel->save();
        }catch(\Exception $e){
            
        }
    }
}