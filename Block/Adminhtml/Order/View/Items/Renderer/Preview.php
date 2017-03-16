<?php
/**
 * Preview.php
 *
 * @category Artifi
 * @package Artifi_Personalize
 * @author Alexey Poletaev <alexey.poletaev@cyberhull.com>
 */

namespace Artifi\Personalize\Block\Adminhtml\Order\View\Items\Renderer;

use Magento\Framework\View\Element\Template;
use Magento\Sales\Model\Order\Item;

/**
 * Class Preview
 *
 * @category Artifi
 * @package Artifi_Personalize
 */
class Preview extends Template
{
    /**
     * @var \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable
     */
    protected $_productTypeConfigurable;
    
    
   protected $_productloader;  
    
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_productloader = $_productloader;        
    }
    /**
     * @var Item
     */
    protected $item;

    /**
     * set order item
     *
     * @param Item $item
     * @return $this
     */
    public function setItem(Item $item)
    {
        $this->item = $item;
        return $this;
    }

    /**
     * get order item
     *
     * @return Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * returns item design id
     *
     * @return string|null
     */
    public function getDesignId()
    {
        $item = $this->getItem();
        return ($item) ? $item->getProductOptionByCode('design_id') : null;
    }
    
    /**
     * Get Product Sku By it's id
     * @param type $id
     * @return String sku
     */
    public function getProductSku($id)
    {
        $product = $this->_productloader->create()->load($id);
        return $sku = $product->getSku();
    }
}
