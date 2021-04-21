<?php
namespace Excellence\GiftCard\Model;

class GiftCard extends \Magento\Framework\Model\AbstractModel 
{
    const CACHE_TAG = 'giftcard_main_id';

    protected function _construct(){
        $this->_init('Excellence\GiftCard\Model\ResourceModel\GiftCard');
    }
    
    
    public function getIdentities(){
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
   
}
