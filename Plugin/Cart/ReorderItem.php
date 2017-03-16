<?php
/**
 * ReorderItem.php
 *
 * @category    Artifi
 * @package     Artifi_Personalize
 * @author      Alexander Nosachev <alexander.nosachev@cyberhull.com>
 */
namespace Artifi\Personalize\Plugin\Cart;

use Magento\Framework\HTTP\Adapter\Curl;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Session;
use Artifi\Personalize\Helper\Config;
use Artifi\Personalize\Model\DesignFactory;

class ReorderItem
{
    /**
     * @var Curl
     */
    protected $curlAdapter;
    
    /**
     * @var Session
     */
    protected $customerSession;
    
    /**
     * @var Config
     */
    protected $configHelper;
    
    /**
     * @var DesignFactory
     */
    protected $designFactory;
    
    /**
     * @var string
     */
    protected $designId;
    
    /**
     * @param Curl $curlAdapter
     * @param Session $customerSession
     * @param Config $configHelper
     * @param DesignFactory $designFactory
     */
    public function __construct(
        Curl $curlAdapter,
        Session $customerSession,
        Config $configHelper,
        DesignFactory $designFactory
    ) {
        $this->curlAdapter = $curlAdapter;
        $this->customerSession = $customerSession;
        $this->configHelper = $configHelper;
        $this->designFactory = $designFactory;
    }
    
    /**
     * Convert order item to quote item with modifying its design data
     *
     * @param \Magento\Checkout\Model\Cart $subject
     * @param \Closure $proceed
     * @param \Magento\Sales\Model\Order\Item $orderItem
     * @param true|null $qtyFlag if is null set product qty like in order
     * @return $this
     */
    public function aroundAddOrderItem(
        \Magento\Checkout\Model\Cart $subject,
        \Closure $proceed,
        $orderItem,
        $qtyFlag = null
    ) {
        $this->designId = $orderItem->getProductOptionByCode('design_id');
        try {
            // The method internally calls addProduct()
            return $proceed($orderItem, $qtyFlag);
        } finally {
            $this->designId = null;
        }
    }
    
    /**
     * Modify product before adding to shopping cart (quote)
     *
     * @param \Magento\Checkout\Model\Cart $subject
     * @param Product $product
     * @param \Magento\Framework\DataObject|int|array $requestInfo
     * @return $this
     */
    public function beforeAddProduct(
        \Magento\Checkout\Model\Cart $subject,
        $product,
        $requestInfo
    ) {
        if ($this->designId) {
            $newDesignData = $this->_requestNewDesign($this->designId);
            
            $buyRequestData = $this->_assembleBuyRequest($requestInfo->getData(), $newDesignData);
            $requestInfo->setData($buyRequestData);
            
            $newDesign = $this->_duplicateDesign($this->designId, $newDesignData);
            $product->addCustomOption('design_id', $newDesign->getId());
        }
        return [$product, $requestInfo];
    }
    
    /**
     * Retrieve new design instance created using previously ordered designId
     *
     * @param string|int $oldDesignId
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _requestNewDesign($oldDesignId)
    {
        $apiParams = array(
            'designId'      => $oldDesignId,
            'userId'        => $this->customerSession->getCustomerId(),
            'websiteId'     => $this->configHelper->getArtifiWebsiteId(),
            'webApiclientKey' => $this->configHelper->getArtifiApiClientKey(),
            //'isGuest'       => !$this->customerSession->isLoggedIn(),
        );

        $url = $this->configHelper->getArtifiApiUrl('Designer/Services/GetReorder', $apiParams);

        // disable headers in the output, Mage equiv of curl_setopt($ch, CURLOPT_HEADER, 0);
        $this->curlAdapter->setConfig(['header' => 0]);

        $this->curlAdapter->write(\Zend_Http_Client::GET, $url);
        $res = $this->curlAdapter->read();
        $this->curlAdapter->close();

        $res = json_decode($res);
        if (isset($res->Response) && $res->Response == 'Success' && isset($res->Data)) {
            $data = $res->Data;
            if (empty($data->Id)) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Error! Reordered design has no ID.')
                );
            }
            $newDesignData = [
                'design_id' => $data->Id,
                'preview_urls' => [],
                'thumbnail_urls' => [],
            ];
            if (isset($data->CustomizedDesignList)) {
                foreach ($data->CustomizedDesignList as $designElement) {
                    if (isset($designElement->ImagePath)) {
                        $newDesignData['preview_urls'][] = $designElement->ImagePath;
                    }
                    if (isset($designElement->ThumbnailImagePath)) {
                        $newDesignData['thumbnail_urls'][] = $designElement->ThumbnailImagePath;
                    }
                }
            }
            return $newDesignData;
        } else {
            $message = isset($res->Message) ? $res->Message : 'Unknown response.';
            throw new \Magento\Framework\Exception\LocalizedException(
                __($message)
            );
        }
    }
    
    /**
     * Incorporate new design data into the buy request data structure
     *
     * @param array $buyRequest
     * @param array $newDesignData
     * @return array
     */
    protected function _assembleBuyRequest($buyRequest, $newDesignData)
    {
        return array_replace_recursive($buyRequest, ['personalization' => $newDesignData]);
    }
    
    /**
     * Duplicate design record in db using new designId
     *
     * @param string|int $oldDesignId
     * @param array $newDesignData
     * @return \Artifi\Personalize\Model\Design
     */
    protected function _duplicateDesign($oldDesignId, $newDesignData)
    {
        /** @var \Artifi\Personalize\Model\Design $design */
        $design = $this->designFactory->create();
        $design->load($oldDesignId);
        $design->addData($newDesignData);
        $design->save();
        return $design;
    }
}
