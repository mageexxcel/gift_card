<?php
namespace Excellence\GiftCard\Helper\Product;
use \Magento\Framework\App\Helper\AbstractHelper;

class GetCurrentProductType extends AbstractHelper
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;
        
    public function __construct(       
        \Magento\Framework\Registry $registry
    ){        
        $this->_registry = $registry;
    }    
    
    public function getCurrentCategory(){        
        return $this->_registry->registry('current_category');
    }
    
    public function getCurrentProduct(){
        return $this->_registry->registry('current_product');
    }
}
?>