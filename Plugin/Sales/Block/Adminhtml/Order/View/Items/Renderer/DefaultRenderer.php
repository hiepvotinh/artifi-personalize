<?php
/**
 * DefaultRenderer.php
 *
 * @category Artifi
 * @package Artifi_Personalize
 * @author Alexey Poletaev <alexey.poletaev@cyberhull.com>
 */

namespace Artifi\Personalize\Plugin\Sales\Block\Adminhtml\Order\View\Items\Renderer;

/**
 * Class DefaultRenderer
 *
 * @category Artifi
 * @package Artifi_Personalize
 */
class DefaultRenderer
{
    /**
     * personalization preview link is added here
     *
     * @param \Magento\Sales\Block\Adminhtml\Order\View\Items\Renderer\DefaultRenderer $subject
     * @param \Closure $proceed
     * @param \Magento\Framework\DataObject $item
     * @param $column
     * @param null $field
     * @return string
     */
    public function aroundGetColumnHtml(
        \Magento\Sales\Block\Adminhtml\Order\View\Items\Renderer\DefaultRenderer $subject,
        \Closure $proceed,
        \Magento\Framework\DataObject $item,
        $column,
        $field = null
    ) {
        $html = $proceed($item, $column, $field);
        if ($column == 'product' && $item->getProductOptionByCode('design_id')) {
            $html .= $subject->getLayout()->getBlock('personalization.preview')->setItem($item)->toHtml();
        }
        return $html;
    }
}