<?php
namespace Artifi\Personalize\Block;

class Editor extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Magento\Checkout\Helper\Cart
     */
    protected $cartHelper;

    
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Checkout\Helper\Cart $cartHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Checkout\Helper\Cart $cartHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->productRepository = $productRepository;
        $this->cartHelper = $cartHelper;
    }

    /**
     * @param int $productId
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct($productId)
    {
        return $this->productRepository->getById($productId, false, $this->_storeManager->getStore()->getId());
    }

    /**
     * @return int|null
     */
    public function getProductId()
    {
        return $this->getRequest()->getParam('product_id');
    }

    /**
     * @return int|null
     */
    protected function getVariationProductId()
    {
        return $this->getRequest()->getParam('selected_configurable_option');
    }

    /**
     * @return int|null
     */
    protected function getDesignId()
    {
        return $this->getRequest()->getParam('design_id');
    }

    /**
     * @param array $overriddenConfig
     * @return string
     */
    public function getEditorJsonConfig(array $overriddenConfig = [])
    {
        $sku = $this->getRequest()->getParam('sku');
        
        //exit;
        if (!$sku) {
            $parentProduct = $this->getProduct($this->getProductId());
            $productId = $this->getVariationProductId() ?: $this->getProductId();
            $product = $this->getProduct($productId);
            $buyRequest = new \Magento\Framework\DataObject($this->getRequest()->getParams());
            $product->getTypeInstance()->processConfiguration($buyRequest, $product);
            $sku = $product->getSku();
            $productCode = $parentProduct->getSku();
        } else {
            $parentProduct = $this->getProduct($this->getProductId());
            $productCode = $parentProduct->getSku();
        }
       
        if( $productCode === $sku ) {
            $sku = '';
        }
        
        $config = [
            'designId' => $this->getDesignId(),
            'productCode' => $productCode,
            'sku' => $sku,
        ];
        
        
        $config = array_replace_recursive($config, $overriddenConfig);
        return json_encode($config, JSON_FORCE_OBJECT);
    }

    /**
     * @return string
     */
    public function getCartUrl()
    {
        return $this->cartHelper->getCartUrl();
    }

    /**
     * @return string
     */
    public function getAddToCartUrl()
    {
        return $this->getUrl('personalization/cart/add');
    }

    /**
     * @return string
     */
    public function getUpdateCartUrl()
    {
        $id = $this->getRequest()->getParam('id');
        return $this->getUrl('personalization/cart/update/id/'.$id);
    }

    /**
     * @return string
     */
    public function getAddToCartJsonConfig()
    {
        return json_encode($this->getRequest()->getPost(), JSON_FORCE_OBJECT);
    }
    
    /**
     * @return Array - Get configurable product children data [id and super attributes]
     */
    public function getProductSkuMapping()
    {
        $product = $this->getProduct($this->getProductId());
        
        /*
         * Return Blank arry if it's not configurable product
         */
        if ( $product->getData('type_id') !== 'configurable' ) {
            return array();
        }
        
        $children = $product->getTypeInstance()->getUsedProducts($product);
        $childrenArray = array();
        
        $configurableAttributes = $product->getTypeInstance(true)->getConfigurableAttributes($product); 
        $superAttribs = array();
        
        // Create Super Attributes Array
        foreach($configurableAttributes as $attrib) {
             $superAttribs[] = array(
                 'id' => $attrib->getData('attribute_id'),
                 'code' => $attrib->getProductAttribute()->getAttributeCode()
                );
        }
        
        // Create id and superattribute data array
        foreach ($children as $child){
            
            // configurable subproducts array by sku as key with id data
            $childrenArray[$child->getData('sku')]['id'] = $child->getData('entity_id'); 
                        
            foreach($superAttribs as $superArrtib) {
                $attribId = $superArrtib['id'];
                $attribCode = $superArrtib['code'];
            
                // configurable subproducts array by sku as key with super attributes as array
                $childrenArray[$child->getData('sku')]['super_attribute'][$attribId] = $child->getData($attribCode);
            }
        }
        return $childrenArray;
    }
    
    /**
     * @return Integer - Get Cart Item Id
     */
    public function getCartItemId() {
        return $this->getRequest()->getParam('id');
    }
}
