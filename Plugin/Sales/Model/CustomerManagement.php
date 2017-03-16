<?php
/**
 * CustomerManagement.php
 *
 * @category    Artifi
 * @package     Artifi_Personalize
 * @author      Alexander Nosachev <alexander.nosachev@cyberhull.com>
 */
namespace Artifi\Personalize\Plugin\Sales\Model;

use Magento\Framework\HTTP\Adapter\Curl;
use Artifi\Personalize\Helper\Config;
use Magento\Framework\Session\SessionManagerInterface;

class CustomerManagement
{
    /**
     * @var Curl
     */
    protected $curlAdapter;
    
    /**
     * @var Config
     */
    protected $configHelper;
    
    /**
     * @var SessionManagerInterface
     */
    protected $sessionManager;
    
    /**
     * @param Curl $curlAdapter
     * @param Config $configHelper
     * @param SessionManagerInterface $sessionManager
     */
    public function __construct(
        Curl $curlAdapter,
        Config $configHelper,
        SessionManagerInterface $sessionManager
    ) {
        $this->curlAdapter = $curlAdapter;
        $this->configHelper = $configHelper;
        $this->sessionManager = $sessionManager;
    }
    
    /**
     * Use newly created account to update user id on Artifi's end
     *
     * @param \Magento\Sales\Model\Order\CustomerManagement $subject
     * @param \Magento\Customer\Api\Data\CustomerInterface $account
     * @return \Magento\Customer\Api\Data\CustomerInterface
     */
    public function afterCreate(
        \Magento\Sales\Model\Order\CustomerManagement $subject,
        \Magento\Customer\Api\Data\CustomerInterface $account
    ) {
        if (!is_null($account) && $account->getId()) {
            $visitor = $this->sessionManager->getVisitorData();
            $oldUserId = isset($visitor['session_id']) ? $visitor['session_id'] : '';
            
            $this->_updateUserId($oldUserId, $account->getId());
        }
        
        return $account;
    }
    
    /**
     * API call to update user id
     *
     * @param string|int $oldUserId
     * @param string|int $newUserId
     * @return bool
     */
    protected function _updateUserId($oldUserId, $newUserId)
    {
        $apiParams = array(
            'webApiclientKey' => $this->configHelper->getArtifiApiClientKey(),
            'websiteId'     => $this->configHelper->getArtifiWebsiteId(),
            'oldUserId'     => $oldUserId,
            'newUserId'     => $newUserId,
            'isGuest'       => 0,
        );

        $url = $this->configHelper->getArtifiApiUrl('Designer/Services/UpdateUserId', $apiParams);

        // disable headers in the output, Mage equiv of curl_setopt($ch, CURLOPT_HEADER, 0);
        $this->curlAdapter->setConfig(['header' => 0]);

        $this->curlAdapter->write(\Zend_Http_Client::GET, $url);
        $res = $this->curlAdapter->read();
        $this->curlAdapter->close();

        $res = json_decode($res);
        if (isset($res->Response) && $res->Response == 'Success') {
            return true;
        } else if (isset($res->Response) && $res->Response == 'Error') {
            $message = isset($res->Message) ? $res->Message : 'Unknown response.';
            //throw new \Magento\Framework\Exception\LocalizedException(__($message));
            return false;
        }
        return false;
    }
    
}
