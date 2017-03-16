<?php
namespace Artifi\Personalize\Block\Cart\Item\Renderer\Actions;

/**
 * @method \Magento\Quote\Model\Quote\Item getItem()
 */
class Generic extends \Magento\Checkout\Block\Cart\Item\Renderer\Actions\Generic
{
    /**
     * @var \Artifi\Personalize\Helper\Design
     */
    protected $designHelper;

    /**
     * @var \Artifi\Personalize\Helper\Product
     */
    protected $productHelper;
    
    /**
     * @var \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable
     */
    protected $_productTypeConfigurable;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Artifi\Personalize\Helper\Design $designHelper
     * @param \Artifi\Personalize\Helper\Product $productHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Artifi\Personalize\Helper\Design $designHelper,
        \Artifi\Personalize\Helper\Product $productHelper,
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $catalogProductTypeConfigurable,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->designHelper = $designHelper;
        $this->productHelper = $productHelper;
        $this->_productTypeConfigurable = $catalogProductTypeConfigurable;
    }

    /**
     * @return \Artifi\Personalize\Model\Design|null
     */
    public function getDesign()
    {
        return $this->designHelper->getQuoteItemDesign($this->getItem());
    }

    /**
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    public function getProduct()
    {
        return $this->productHelper->getVariationProduct($this->getItem())
            ?: $this->getItem()->getProduct();
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
    /**
     * Get item configure url
     *
     * @return string
     */
    public function getConfigureUrl()
    {
        return $this->getUrl(
            'checkout/cart/configure',
            [
                'id' => $this->getItem()->getId(),
                'product_id' => $this->getItem()->getProduct()->getId()
            ]
        );
    }
}
