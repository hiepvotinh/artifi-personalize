<?php
namespace Artifi\Personalize\Helper;

use Magento\Framework\App\Helper\Context;

class Url extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var Config
     */
    protected $configHelper;

    /**
     * @param Context $context
     * @param Config $configHelper
     */
    public function __construct(
        Context $context,
        Config $configHelper
    ) {
        parent::__construct($context);
        $this->configHelper = $configHelper;
    }

    /**
     * generates url to retrieve rendered design pdf
     *
     * @param $designId
     * @return string
     */
    public function getRenderedPdfUrl($designId)
    {
        $params = [
            'customizedProductId' => $designId,
            'webApiClientKey' => $this->configHelper->getArtifiApiClientKey(),
            'websiteId' => $this->configHelper->getArtifiWebsiteId(),
        ];
        return $this->configHelper->getArtifiApiUrl('Designer/Asset/GeneratePdfByType', $params);
    }
}
