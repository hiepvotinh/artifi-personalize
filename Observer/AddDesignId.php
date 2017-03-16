<?php

/**
 * Opserver to add design on update of quote item
 */

namespace Artifi\Personalize\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class AddDesignId
 */
class AddDesignId implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    public $request;

    /**
     * Constructor
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Quote\Model\Quote\Item\OptionFactory $optionFactory
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Quote\Model\Quote\Item\OptionFactory $optionFactory
    ) {
        $this->optionFactory = $optionFactory;
        $this->request = $request;
    }

    /**
     * Function to update Quote item with design id on update item for
     * configurable product
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer) {
        $personalization = $this->request->getParam('personalization');
        if (isset($personalization) && isset($personalization['design_id'])) {
            $product = $observer->getEvent()->getData('product');
            $quoteItem = $observer->getEvent()->getData('quote_item');

            $optData = [
                'product_id' => $product->getId(),
                'code' => 'design_id',
                'value' => $personalization['design_id']
            ];
            $quoteItem->addOption($optData);
        }
        return $this;
    }

}
