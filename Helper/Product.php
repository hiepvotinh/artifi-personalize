<?php
namespace Artifi\Personalize\Helper;

class Product extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Retrieve selected simple product of a configurable product
     *
     * @param \Magento\Quote\Model\Quote\Item $quoteItem
     * @return \Magento\Catalog\Model\Product|null
     */
    public function getVariationProduct(\Magento\Quote\Model\Quote\Item $quoteItem)
    {
        $simpleProductOption = $quoteItem->getOptionByCode('simple_product');
        if ($simpleProductOption && $simpleProductOption->getProduct()) {
            return $simpleProductOption->getProduct();
        }
        return null;
    }
    /**
     * Function to get if product can be
     * Personalized via artifi
     */
    public function canPersonalize($product)
    {
        //var_dump($product->getData());
        
        return (bool)$product->getData('personalization_allowed');
    }
    
    /**
     * Function to get if product can be
     * directly added to cart without personalization
     */
    public function canAddToCart($product)
    {
        return (!$product->getData('personalization_allowed') || !$product->getData('personalization_required'));
    }
}
