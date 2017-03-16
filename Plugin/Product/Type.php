<?php
namespace Artifi\Personalize\Plugin\Product;

class Type
{
    /**
     * @param \Magento\Catalog\Model\Product\Type\AbstractType $subject
     * @param \Magento\Framework\DataObject $buyRequest
     * @param \Magento\Catalog\Model\Product $product
     * @param string|null $processMode
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforePrepareForCartAdvanced(
        \Magento\Catalog\Model\Product\Type\AbstractType $subject,
        \Magento\Framework\DataObject $buyRequest,
        \Magento\Catalog\Model\Product $product,
        $processMode = null
    ) {
        $this->assertPersonalizationPresence($product);
        return [$buyRequest, $product, $processMode];
    }

    /**
     * @param \Magento\Catalog\Model\Product\Type\AbstractType $subject
     * @param \Closure $proceed
     * @param \Magento\Catalog\Model\Product $product
     * @return \Magento\Catalog\Model\Product\Type\AbstractType
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function aroundCheckProductBuyState(
        \Magento\Catalog\Model\Product\Type\AbstractType $subject,
        \Closure $proceed,
        \Magento\Catalog\Model\Product $product
    ) {
        $result = $proceed($product);
        $this->assertPersonalizationPresence($product);
        return $result;
    }

    /**
     * Assert that the product satisfies the personalization requirements
     *
     * @param \Magento\Catalog\Model\Product $product
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function assertPersonalizationPresence(\Magento\Catalog\Model\Product $product)
    {
        $canPersonalize = (bool)$product->getData('personalization_allowed');
        $mustPersonalize = $canPersonalize && (bool)$product->getData('personalization_required');
        $designId = $product->getCustomOption('design_id');
        /**
         *  Commented this code as it's preventing sometimes
         *  from updating desing id to quote item
        if ($mustPersonalize && !$designId) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('The product requires personalization.')
            );
        }
         */
    }
}
