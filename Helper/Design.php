<?php
namespace Artifi\Personalize\Helper;

use Magento\Framework\Exception\NoSuchEntityException;

class Design extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Artifi\Personalize\Model\DesignFactory
     */
    protected $designFactory;

    /*
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $designCollection;

    /**
     * \Magento\Framework\App\ResourceConnection
     */
    protected $_resourceConnection;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Artifi\Personalize\Model\DesignFactory $designFactory
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Artifi\Personalize\Model\DesignFactory $designFactory,
        \Artifi\Personalize\Model\ResourceModel\Design\Collection $designCollection,
        \Magento\Framework\App\ResourceConnection $resourceConnection
    ) {
        parent::__construct($context);
        $this->designFactory = $designFactory;
        $this->designCollection = $designCollection;
        $this->_resourceConnection = $resourceConnection;
    }

    /**
     * @param \Magento\Quote\Model\Quote\Item $quoteItem
     * @return \Artifi\Personalize\Model\Design|null
     * @throws NoSuchEntityException
     */
    public function getQuoteItemDesign(\Magento\Quote\Model\Quote\Item $quoteItem)
    {
        $design = null;
        $designIdOption = $quoteItem->getOptionByCode('design_id');
        if ($designIdOption) {
            $designId = $designIdOption->getValue();

            /** @var \Artifi\Personalize\Model\Design $design */
            $design = $this->designFactory->create();
            $design->load($designId);
            if (!$design->getId()) {
                throw new NoSuchEntityException(__('No such design entity.'));
            }
            return $design;
        } else {
            /*
             * As options are not loaded in some cases,
             * get the design id by query
             */
            $quoteItemOptionTable = $this->_resourceConnection->getTableName('quote_item_option');
            $dbConnection = $this->_resourceConnection->getConnection();
            
            $select = $dbConnection->select()->from($quoteItemOptionTable)
                    ->where('item_id = ?', $quoteItem->getData('item_id'))
                    ->where('code = ?', 'design_id');
            $result = $dbConnection->fetchAll($select);
            if (isset($result[0]) && isset($result[0]['value'])) {
                $design = $this->designFactory->create();
                $design->load($result[0]['value']);
            }
        }
        return $design;
    }
    
    /**
     * Returns Artifi Design Id Associated with wishlist Item if any
     * @param \Magento\Quote\Model\Quote\Item $quoteItem
     * @return \Artifi\Personalize\Model\Design Id|null
     */
    public function getWishlistItemDesignId($wishlistItem)
    {
        if (!is_object($wishlistItem)) {
            $wishlistItemId = $wishlistItem;
        } else {
            $wishlistItemId = $wishlistItem->getData('wishlist_item_id');
        }

        $designId = null;
        $designTable = $this->_resourceConnection->getTableName('artifi_personalize_design');
        $dbConnection = $this->_resourceConnection->getConnection();

        $select = $dbConnection->select()->from($designTable)
                ->where('wishlist_item_id = ?', $wishlistItemId);

        $result = $dbConnection->fetchAll($select);
        if (isset($result[0]) && isset($result[0]['design_id'])) {
            $designId = $result[0]['design_id'];
        }
        return $designId;
    }

    /**
     * Get First Thumbnail Url belonging to design id
     * @param type $designId
     * @return string
     */
    public function getFirstThumbnailUrl($designId)
    {
        $thumbUrl = '';
        $design = $this->designFactory->create()->load($designId);
        $thumbUrls = $design->getData(thumbnail_urls);
        $thumbUrlsArray = unserialize($thumbUrls);
        
        if (is_array($thumbUrlsArray) && isset($thumbUrlsArray[0])) {
            $thumbUrl = $thumbUrlsArray[0];
        }
    }
}
