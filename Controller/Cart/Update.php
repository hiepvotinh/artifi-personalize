<?php
/**
 * Artifi Update Cart Controller
 */
namespace Artifi\Personalize\Controller\Cart;

use Magento\Catalog\Controller\Product\View\ViewInterface;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Catalog\Api\ProductRepositoryInterface;

class Update extends \Magento\Checkout\Controller\Cart\UpdateItemOptions
{
    /**
     * @var \Magento\Framework\App\Action\Context
     */
    public $designFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Artifi\Personalize\Model\DesignFactory $designFactory
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        CustomerCart $cart,
        \Artifi\Personalize\Model\DesignFactory $designFactory,
        ProductRepositoryInterface $productRepository
    ) {
        $this->designFactory = $designFactory;
        $this->productRepository = $productRepository;
        parent::__construct($context, $scopeConfig, $checkoutSession, $storeManager, $formKeyValidator, $cart);
    }

    /**
     * Update existing personalization design
     */
    public function execute()
    {
        parent::execute();
    }
}
