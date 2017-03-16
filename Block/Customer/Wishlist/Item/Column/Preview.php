<?php
/**
 * Preview/Image Block For Wishlist item
 */
namespace Artifi\Personalize\Block\Customer\Wishlist\Item\Column;

class Preview extends \Magento\Wishlist\Block\Customer\Wishlist\Item\Column
{
    /**
     * @var \Artifi\Personalize\Model\DesignFactory
     */
    protected $_designFactory;
    
    /**
     * @var \Artifi\Personalize\Helper\Design
     */
    protected $_designHelper;
    
    /**
     * @var \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable
     */
    protected $_productTypeConfigurable;

    /**
     *
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @param \Artifi\Personalize\Helper\Design $designHelper
     * @param \Artifi\Personalize\Helper\Product $productHelper
     * @param array $data
     */
    public function __construct(
        \Artifi\Personalize\Helper\Design $designHelper,
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\App\Http\Context $appContext,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Artifi\Personalize\Model\DesignFactory $designFactory,
        array $data = []
    ) {
        $this->_designHelper = $designHelper;
        $this->_productFactory = $productFactory;
        $this->_designFactory = $designFactory;
        parent::__construct($context, $appContext, $data);
    }

    /**
     * @return \Artifi\Personalize\Model\Design|null
     */
    public function getDesign()
    {
        return $this->_designHelper->getWishlistItemDesignId($this->getItem());
    }
    
    /**
     * Function to ge selected configurable option for wislist item
     * @return String SKU of selected configurable option
     */
    public function getConfigurableOptionSku()
    {
        $sku = '';
        $infoBuyRequest = unserialize($this->getItem()->getOptionByCode('info_buyRequest')->getData('value'));
        $configurableProductId = $infoBuyRequest['selected_configurable_option'];
        if ($configurableProductId) {
            $product = $this->_productFactory->create()->load($configurableProductId);
            $sku = $product->getSku();
        }
        return $sku;
    }
    
    /**
     * Get First Thumbnail Url belonging to design id
     * @param type $designId
     * @return string
     */
    public function getFirstThumbnailUrl($designId)
    {
        $thumbUrl = '';
        $design = $this->_designFactory->create()->load($designId);
         $thumbUrls = $design->getData('thumbnail_urls');
        if (is_array($thumbUrls) && isset($thumbUrls[0])) {
            $thumbUrl = $thumbUrls[0];
        }
        return $thumbUrl;
    }
}
