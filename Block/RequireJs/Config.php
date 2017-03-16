<?php
namespace Artifi\Personalize\Block\RequireJs;

class Config extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Artifi\Personalize\Helper\Config
     */
    protected $configHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Artifi\Personalize\Helper\Config $configHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Artifi\Personalize\Helper\Config $configHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->customerSession = $customerSession;
        $this->configHelper = $configHelper;
    }

    /**
     * @return string
     */
    public function getArtifiSdkUrl()
    {
        // Note: RequireJs will append '.js' to the URL when resolving the module
        return $this->configHelper->getArtifiApiUrl('SCRIPT/sasIntegration/ArtifiIntegration', [], '');
    }

    /**
     * @return string
     */
    public function getArtifiJsonConfig()
    {
        $config = [
            'webApiclientKey' => $this->configHelper->getArtifiApiClientKey(),
            'websiteId' => $this->configHelper->getArtifiWebsiteId(),
            'userId' => $this->customerSession->isLoggedIn()
                ? $this->customerSession->getCustomerId()
                : $this->customerSession->getSessionId(),
            'isGuest' => !$this->customerSession->isLoggedIn(),
        ];
        return json_encode($config, JSON_FORCE_OBJECT);
    }
}
