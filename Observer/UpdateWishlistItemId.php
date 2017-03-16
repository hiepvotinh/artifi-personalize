<?php

/**
 * Observer to Update Wishlist Item Id to Design Table on Adding item to wishlist
 * and open the template in the editor.
 */

namespace Artifi\Personalize\Observer;

use Magento\Framework\Event\ObserverInterface;
use Artifi\Personalize\Helper\Design;

/**
 * Class UpdateWishlistItemId
 */
class UpdateWishlistItemId implements ObserverInterface {

    /**
     * @var Artifi\Personalize\Helper\Design
     */
    protected $_designHelper;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $_request;

    /**
     * @var \Magento\Quote\Model\Quote\ItemFactory
     */
    protected $_quoteItemFactory;
    
    /**
     * Constructor
     * @param Design $designHelper
     * @param \Magento\Quote\Model\Quote\ItemFactory $quoteItemFactory
     * @param \Magento\Framework\App\Request\Http $request
     */
    public function __construct(
        Design $designHelper,
        \Magento\Quote\Model\Quote\ItemFactory $quoteItemFactory,
        \Magento\Framework\App\Request\Http $request
    ) {
        $this->_designHelper = $designHelper;
        $this->_quoteItemFactory = $quoteItemFactory;
        $this->_request = $request;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return \Artifi\Personalize\Observer\UpdateWishlistItemId
     */
    public function execute(\Magento\Framework\Event\Observer $observer) {
        //Get Wishlist Item Id
        $items = $observer->getEvent()->getData('items');
        $item = $items[0];
        $wishlistItemId = $item->getId();

        // Get Design Id
        $quoteItemId = $this->_request->getParam('item');
        $quoteItem = $this->_quoteItemFactory->create();
        $quoteItem->load($quoteItemId);
        $design = $this->_designHelper->getQuoteItemDesign($quoteItem);
        if ($design) {
            $design->setWishlistItemId($wishlistItemId)->save();
        }
        return $this;
    }
}
