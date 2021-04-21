<?php

/*
 * Excellence Giftcard Module
 * 
 * @category  Excellence
 * @package   Excellence_GiftCard
 * @copyright Copyright (c) 2019 Excellence. (http://xmagestore.com)
 */

namespace Excellence\GiftCard\Model\Product\Type;

class GiftCard extends \Magento\Catalog\Model\Product\Type\AbstractType{
    
    const TYPE_ID = 'excellence_giftcard';

    public function isVirtual($product){
        return true;
    }
    public function deleteTypeSpecificData(\Magento\Catalog\Model\Product $product){

    }

}
