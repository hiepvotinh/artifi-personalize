<?php
namespace Artifi\Personalize\Controller\Editor;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\LayoutFactory;

class Popup extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $layoutFactory;

    /**
     * @param Context $context
     * @param LayoutFactory $layoutFactory
     */
    public function __construct(
        Context $context,
        LayoutFactory $layoutFactory
    ) {
        parent::__construct($context);
        $this->layoutFactory = $layoutFactory;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $layout = $this->layoutFactory->create();

        /** @var \Artifi\Personalize\Block\Editor $block */
        $block = $layout->createBlock(\Artifi\Personalize\Block\Editor::class);
        $block->setTemplate('Artifi_Personalize::editor.phtml');

        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        $resultRaw->setContents($block->toHtml());
        return $resultRaw;
    }
}
