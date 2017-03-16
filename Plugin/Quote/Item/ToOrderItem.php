<?php
namespace Artifi\Personalize\Plugin\Quote\Item;

class ToOrderItem
{
    /**
     * @param \Magento\Quote\Model\Quote\Item\ToOrderItem $subject
     * @param \Closure $proceed
     * @param \Magento\Quote\Model\Quote\Item\AbstractItem $quoteItem
     * @param array $data
     * @return \Magento\Sales\Api\Data\OrderItemInterface
     */
    public function aroundConvert(
        \Magento\Quote\Model\Quote\Item\ToOrderItem $subject,
        \Closure $proceed,
        \Magento\Quote\Model\Quote\Item\AbstractItem $quoteItem,
        array $data
    ) {
        /** @var \Magento\Sales\Api\Data\OrderItemInterface $orderItem */
        $orderItem = $proceed($quoteItem, $data);
        $designIdOption = $quoteItem->getOptionByCode('design_id');
        if ($designIdOption) {
            $designId = $designIdOption->getValue();
            $options = (array)$orderItem->getProductOptions();
            $options['design_id'] = $designId;
            $orderItem->setProductOptions($options);
        }
        return $orderItem;
    }
}
