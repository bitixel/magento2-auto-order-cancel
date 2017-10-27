<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
 
namespace Bitixel\AutoCancelOrder\Cron;
 
class CancelOrder
{
    
    
    protected $_logger;
    protected $_orderCollectionFactory;
    protected $_stdTimezone;
     
    public function __construct(
    	\Psr\Log\LoggerInterface $logger,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Framework\Stdlib\DateTime\Timezone $stdTimezone
    ) {
        $this->_logger = $logger;
    	$this->_orderCollectionFactory = $orderCollectionFactory;
    	$this->_stdTimezone = $stdTimezone;
    }
 
    public function execute() {   
        $duration_in_sec = 86400 * 4; // 4 days
    	$currentTime = $this->_stdTimezone->date((time()-$duration_in_sec))->format('Y-m-d H:i:s');
    
	$orders = $this->_orderCollectionFactory->create()
		->addFieldToFilter('created_at', ['lteq' => $currentTime])
                ->addFieldToFilter('status', array('in' => ['processing', 'pending']));
                
        $orders->getSelect(); 
        $order_ids = [];   
        
        foreach($orders as $order){
        	$order_ids[] = $order->getId();
        	$order->addStatusHistoryComment('Order has been canceled automatically', \Magento\Sales\Model\Order::STATE_CANCELED)
            ->setIsVisibleOnFront(true);
        	$order->cancel();
        	$order->save();
        	//->setIsVisibleOnFront(false)
            //->setIsCustomerNotified(false);
        } 
        
        
        $this->_logger->info('cancelled orders '. implode(' , ', $order_ids));
        
        return $this;
    }
}
