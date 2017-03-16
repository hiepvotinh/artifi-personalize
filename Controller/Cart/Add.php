<?php
namespace Artifi\Personalize\Controller\Cart;

class Add extends \Magento\Checkout\Controller\Cart\Add
{
    /**
     * @var \Artifi\Personalize\Model\DesignFactory
     */
    protected $designFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Magento\Checkout\Model\Cart $cart
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Artifi\Personalize\Model\DesignFactory $designFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Artifi\Personalize\Model\DesignFactory $designFactory
    ) {
        parent::__construct($context, $scopeConfig, $checkoutSession, $storeManager, $formKeyValidator, $cart, $productRepository);
        $this->designFactory = $designFactory;
    }

    /**
     * Store personalization design as a custom product option
     *
     * @return \Magento\Catalog\Model\Product|false
     */
    protected function _initProduct()
    {
        $product = parent::_initProduct();

        $designData = $this->getRequest()->getParam('personalization');

        if ($product && $designData) {
            // Persist new personalization design
            /** @var \Artifi\Personalize\Model\Design $design */
            $design = $this->designFactory->create();
            $design->setData($designData);
            $design->save();

            // Store reference to the personalized design in a product option
            $product->addCustomOption('design_id', $design->getId());
        }

        return $product;
    }
}
