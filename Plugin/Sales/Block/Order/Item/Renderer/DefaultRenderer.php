<?php
/**
 * DefaultRenderer.php
 *
 * @category Artifi
 * @package Artifi_Personalize
 * @author Alexey Poletaev <alexey.poletaev@cyberhull.com>
 */

namespace Artifi\Personalize\Plugin\Sales\Block\Order\Item\Renderer;

/**
 * Class DefaultRenderer
 *
 * @category Artifi
 * @package Artifi_Personalize
 */
class DefaultRenderer
{
    /**
     * Override the block template
     *
     * @param \Magento\Sales\Block\Order\Item\Renderer\DefaultRenderer $subject
     * @return void
     */
    public function beforeToHtml(
        \Magento\Sales\Block\Order\Item\Renderer\DefaultRenderer $subject
    ) {
        if ($subject->getTemplate() == 'Magento_Sales::order/items/renderer/default.phtml'
            || ($subject->getTemplate() == 'order/items/renderer/default.phtml'
                && $subject->getModuleName() == 'Magento_Sales')
        ) {
            $subject->setTemplate('Artifi_Personalize::order/items/renderer/default.phtml');
        }
    }
}
