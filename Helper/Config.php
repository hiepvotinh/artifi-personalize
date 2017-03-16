<?php
/**
 * Config.php
 *
 * @category    Artifi
 * @package     Artifi_Personalize
 * @editor      Alexander Nosachev <alexander.nosachev@cyberhull.com>
 */
namespace Artifi\Personalize\Helper;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Build URL to Artifi API endpoint
     *
     * @param string $path URL path
     * @param array $params Query parameters
     * @param string $protocol Protocol name or empty for protocol-relative URL
     * @return string
     */
    public function getArtifiApiUrl($path = '', array $params = [], $protocol = 'http')
    {
        $domain = $this->scopeConfig->getValue(
            'personalization/artifi/api_domain',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $scheme = $protocol ? $protocol . ':' : '';
        $url = "$scheme//$domain/$path";
        if ($params) {
            $query = http_build_query($params);
            $url .= '?' . $query;
        }
        return $url;
    }
    
    /**
     * @return string
     */
    public function getArtifiApiClientKey()
    {
        return $this->scopeConfig->getValue(
            'personalization/artifi/api_client_key',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getArtifiWebsiteId()
    {
        return $this->scopeConfig->getValue(
            'personalization/artifi/website_id',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
