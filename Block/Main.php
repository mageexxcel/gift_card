<?php
namespace Excellence\GiftCard\Block;
  
class Main extends \Magento\Framework\View\Element\Template
{
    const EQ_CURRENCY = 'storecredit/points_currency/currency';

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context
    ){
        parent::__construct($context);
    }
    protected function _prepareLayout(){
        parent::_prepareLayout();
        return $this;
    }
}
