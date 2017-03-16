<?php
namespace Artifi\Personalize\Plugin\Cart\Item;

class Renderer
{
    /**
     * @var \Artifi\Personalize\Helper\Design
     */
    protected $designHelper;

    /**
     * @param \Artifi\Personalize\Helper\Design $designHelper
     */
    public function __construct(
        \Artifi\Personalize\Helper\Design $designHelper
    ) {
        $this->designHelper = $designHelper;
    }

    /**
     * @param \Magento\Checkout\Block\Cart\Item\Renderer $subject
     * @return array
     */
    public function afterGetImage(
        \Magento\Checkout\Block\Cart\Item\Renderer $subject,
        \Magento\Catalog\Block\Product\Image $result
    ) {
        /** @var \Magento\Quote\Model\Quote\Item $quoteItem */
        $quoteItem = $subject->getItem();
        $design = $this->designHelper->getQuoteItemDesign($quoteItem);
        if ($design) {
            $thumbnailUrl = $design->getMainThumbnailUrl();
            if ($thumbnailUrl) {
                $result->setImageUrl($thumbnailUrl);
            }
        }
        return $result;
    }
}
