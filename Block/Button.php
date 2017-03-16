<?php
namespace Artifi\Personalize\Block;

class Button extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $product;

    /**
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        if (!$this->product) {
            /** @var \Magento\Catalog\Block\Product\View $parentBlock */
            $parentBlock = $this->getParentBlock();
            $this->product = $parentBlock->getProduct();
        }
        return $this->product;
    }

    /**
     * @return bool
     */
    public function canPersonalize()
    {
        $product = $this->getProduct();
        //var_dump($product->getData());
        return (bool)$product->getData('personalization_allowed');
    }

    /**
     * @return bool
     */
    public function canAddToCart()
    {
        $product = $this->getProduct();
        return (!$product->getData('personalization_allowed') || !$product->getData('personalization_required'));
    }
}
