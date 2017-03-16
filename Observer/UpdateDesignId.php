<?php

/**
 * Opserver to add design id on adding wishlist item to cart
 */

namespace Artifi\Personalize\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class AddDesignId
 */
class UpdateDesignid implements ObserverInterface
{

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * Constructor Function
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->_objectManager = $objectManager;
    }

    /**
     * Function to update Quote item with design id on move 
     * item from wishlist to cart
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer) {
        $request = $this->_objectManager->get('\Magento\Framework\App\Request\Http');
        $optionFactory = $this->_objectManager->create('\Magento\Quote\Model\Quote\Item\OptionFactory');
        $designHelper = $this->_objectManager->create('\Artifi\Personalize\Helper\Design');

        $moduleName = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        
        if ($controller === "index" && $moduleName === "wishlist" && $action === "cart") {
            $wishlistItemId = $request->getParam('item');
            $designId = $designHelper->getWishlistItemDesignId($wishlistItemId);
            
            if ($designId) {
                $product = $observer->getEvent()->getData('product');
                $quoteItem = $observer->getEvent()->getData('quote_item');

                $optData = [
                    'product_id' => $product->getId(),
                    'code' => 'design_id',
                    'value' => $designId
                ];
                $quoteItem->addOption($optData);
            }
            return $this;
        }
    }
}
