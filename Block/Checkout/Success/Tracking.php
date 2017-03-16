<?php
namespace Artifi\Personalize\Block\Checkout\Success;

class Tracking extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        array $data
    ) {
        parent::__construct($context, $data);
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @return string
     */
    public function getSessionId()
    {
        /** @var \Magento\Framework\Session\SessionManagerInterface $_session */
        $visitor = $this->_session->getVisitorData();
        return isset($visitor['session_id']) ? $visitor['session_id'] : '';
    }

    /**
     * @return array
     */
    public function getOrderedDesignIds()
    {
        $result = [];
        $order = $this->checkoutSession->getLastRealOrder();
        /** @var \Magento\Sales\Model\Order\Item $orderItem */
        foreach ($order->getItems() as $orderItem) {
            $designId = $orderItem->getProductOptionByCode('design_id');
            if ($designId) {
                $result[] = $designId;
            }
        }
        return $result;
    }
}
