<?php
namespace Excellence\GiftCard\Controller\Cron;

use \Psr\Log\LoggerInterface;

class SendScheduledMail extends \Magento\Framework\App\Action\Action
{
  /**
   * @var \Magento\Framework\View\Result\PageFactory
   */
  protected $_pageFactory;
  /**
   * @var \Psr\Log\LoggerInterface
   */
  protected $_logger;
  /**
   * @var \Excellence\GiftCard\Helper\Data
   */
  protected $_dataHelper;

  public function __construct(
    \Magento\Framework\App\Action\Context $context,
    \Magento\Framework\View\Result\PageFactory $pageFactory,
    LoggerInterface $logger,
    \Excellence\GiftCard\Helper\Data $dataHelper
  ){
    $this->_pageFactory = $pageFactory;
    $this->_logger = $logger;
    $this->_dataHelper = $dataHelper;
    return parent::__construct($context);
  }

  public function execute(){
    $this->_logger->info('GiftCard Cron Triggered !');
    $this->_dataHelper->sendEmailViaCronJob();
    return $this->_pageFactory->create();
  }
}