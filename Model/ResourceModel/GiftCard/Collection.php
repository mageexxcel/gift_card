<?php
namespace Excellence\GiftCard\Model\ResourceModel\GiftCard;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Excellence\GiftCard\Model\GiftCard','Excellence\GiftCard\Model\ResourceModel\GiftCard');
    }
}
