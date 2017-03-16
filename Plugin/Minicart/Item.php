<?php
namespace Artifi\Personalize\Plugin\Minicart;

use Magento\Framework\UrlInterface;
use Artifi\Personalize\Helper\Design;
use Artifi\Personalize\Helper\Product;

class Item
{
    /**
     * @var Design
     */
    protected $designHelper;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var Product
     */
    protected $productHelper;

    /**
     * @var \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable
     */
    protected $_productTypeConfigurable;
    
    
    /**
     * @param Design $designHelper
     * @param Product $productHelper
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        Design $designHelper,
        Product $productHelper,
        UrlInterface $urlBuilder,
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $catalogProductTypeConfigurable
    ) {
        $this->designHelper = $designHelper;
        $this->productHelper = $productHelper;
        $this->urlBuilder = $urlBuilder;
        $this->_productTypeConfigurable = $catalogProductTypeConfigurable;
    }

    /**
     * @param \Magento\Checkout\CustomerData\ItemInterface $subject
     * @param \Closure $proceed
     * @param \Magento\Quote\Model\Quote\Item $quoteItem
     * @return array
     */
    public function aroundGetItemData(
        \Magento\Checkout\CustomerData\ItemInterface $subject,
        \Closure $proceed,
        \Magento\Quote\Model\Quote\Item $quoteItem
    ) {
        $result = $proceed($quoteItem);
        $design = $this->designHelper->getQuoteItemDesign($quoteItem);

        $result['is_product_personalized'] = (bool)$design;
        $result['item_id'] = $quoteItem->getId();
        if ($design) {
            $product = $this->productHelper->getVariationProduct($quoteItem) ?: $quoteItem->getProduct();
            $parentProductId = $this->getParentProductId($product->getId());
            $result['personalize_url'] = $this->urlBuilder->getUrl('personalization/editor/popup');
            $result['design_id'] = $design->getId();
            $result['product_sku'] = $product->getSku();
            
            if ($parentProductId != '0') :
                $result['product_id'] = $parentProductId;
            else :
                $result['product_id'] = $product->getId();
            endif;
            $thumbnailUrl = $design->getMainThumbnailUrl();
            if ($thumbnailUrl) {
                $result['product_image']['src'] = $thumbnailUrl;
                $result['product_image']['height'] = 'auto';
            }
        }
        // Fixed : Missing configure link for not personalized products
        
        $configureUrl = $this->urlBuilder->getUrl(
            'checkout/cart/configure',
            ['id' => $quoteItem->getId(),
            'product_id' => $quoteItem->getProduct()->getId()]
        );
        $result['configure_url'] = $configureUrl;	
        return $result;
    }
    
    /*
     * @param $productId -  Product Id
     * $return configurable parent sku
     */
    public function getParentProductId($productId) {
        $parent_id = 0;
        $parentByChild = $this->_productTypeConfigurable->getParentIdsByChild($productId);    
        if (isset($parentByChild[0])) {
            $parent_id = $parentByChild[0]; //here you will get parent product id
        }
        return $parent_id;
    }
}
