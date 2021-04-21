<?php
namespace Excellence\GiftCard\Observer\Adminhtml;
use Magento\Framework\Event\ObserverInterface;
 
class GiftCardOptions implements ObserverInterface
{
    /**
     * @var \Magento\Catalog\Model\Product\Option
     */
    protected $_productOptions;
    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $_productRepository;
    /**
     * @var \Excellence\GiftCard\Helper\Data
     */
    protected $_giftCardHelper;
 
    public function __construct(
        \Magento\Catalog\Model\Product\Option $productOptions,
        \Magento\Catalog\Model\Product $productRepository,
        \Excellence\GiftCard\Helper\Data $giftCardHelper
    ){
        $this->_productOptions = $productOptions;
        $this->_productRepository = $productRepository;
        $this->_giftCardHelper = $giftCardHelper;
    }
 
    public function execute(\Magento\Framework\Event\Observer $observer){
        $product = $observer->getProduct();
        if($product->getTypeId() == $this-> _giftCardHelper::PRODUCT_TYPE){
            $optionsStatus = $this->isOptionsExists($product);
            if(!$optionsStatus){
                $productId = $observer->getProduct()->getId();
                $productFromRepo = $this->_productRepository->load($productId);
                $values = [
                            [
                                'record_id'=>0,                                        
                                'title'=>'Gift Card',
                                'price'=>10,
                                'price_type'=>"fixed",
                                'sort_order'=>1,
                                'is_delete'=>0
                            ],
                            [
                                'record_id'=>1,                    
                                'title'=>'Gift Card',
                                'price'=>20,
                                'price_type'=>"fixed",
                                'sort_order'=>1,
                                'is_delete'=>0
                            ],
                            [
                                'record_id'=>2,                    
                                'title'=>'Gift Card',
                                'price'=>30,
                                'price_type'=>"fixed",
                                'sort_order'=>1,
                                'is_delete'=>0
                            ]
                ];

                $options = [
                            [
                                "sort_order"    => 0,
                                "title"         => "Gift Card Value",
                                "price_type"    => "fixed",
                                "type"          => "drop_down",
                                "is_require"    => 1,
                                "values"        => $values
                            ],[
                                "sort_order"    => 1,
                                "title"         => "Sender Name",
                                "price_type"    => "fixed",
                                "price"         => "",
                                "type"          => "field",
                                "is_require"    => 1
                            ],[
                                "sort_order"    => 2,
                                "title"         => "Sender Email",
                                "price_type"    => "fixed",
                                "price"         => "",
                                "type"          => "field",
                                "is_require"    => 1
                            ],[
                                "sort_order"    => 3,
                                "title"         => "Receiver Name",
                                "price_type"    => "fixed",
                                "price"         => "",
                                "type"          => "field",
                                "is_require"    => 1
                            ],[
                                "sort_order"    => 4,
                                "title"         => "Receiver Email",
                                "price_type"    => "fixed",
                                "price"         => "",
                                "type"          => "field",
                                "is_require"    => 1
                            ],[
                                "sort_order"    => 5,
                                "title"         => "Message",
                                "price_type"    => "fixed",
                                "price"         => "",
                                "type"          => "area",
                                "is_require"    => 0
                            ],[
                                "sort_order"    => 6,
                                "title"         => "Scheduled Date",
                                "price_type"    => "fixed",
                                "price"         => "",
                                "type"          => "date",
                                "is_require"    => 1
                            ]
                ];

                $productFromRepo->setHasOptions(1);
                $productFromRepo->setCanSaveCustomOptions(true);
                foreach ($options as $arrayOption) {
                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); //instance of Object manager
                    $option = $objectManager->create('\Magento\Catalog\Model\Product\Option')
                    ->setProductId($productId)
                    ->setStoreId($product->getStoreId())
                    ->addData($arrayOption);
                    $option->save();
                    $productFromRepo->addOption($option);
                }
                $productFromRepo->save();
            }
        }        
    }
    /**
     * @return boolean
     */
    public function isOptionsExists($product){
        foreach ($product->getOptions() as $value) {
            return true;
    }
        return false;
    }
}