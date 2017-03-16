<?php
/**
 * View.php
 *
 * @category Artifi
 * @package Artifi_Personalize
 * @author Alexexy Poletaev <alexey.poletaev@cyberhull.com>
 */

namespace Artifi\Personalize\Model\Design;

use Magento\Framework\DataObject;
use Artifi\Personalize\Api\Data\DesignViewInterface;
use Artifi\Personalize\Model\Design\View\Text\ItemFactory as TextItemFactory;
use Artifi\Personalize\Model\Design\View\Image\ItemFactory as ImageItemFactory;

/**
 * Class View
 *
 * @category Artifi
 * @package Artifi_Personalize
 */
class View extends DataObject implements DesignViewInterface
{
    /**
     * @var TextItemFactory
     */
    protected $textItemFactory;

    /**
     * @var ImageItemFactory
     */
    protected $imageItemFactory;

    /**
     * View constructor.
     *
     * @param TextItemFactory $textItemFactory
     * @param ImageItemFactory $imageItemFactory
     * @param array $data
     */
    public function __construct(
        TextItemFactory $textItemFactory,
        ImageItemFactory $imageItemFactory,
        array $data = []
    ) {
        $this->textItemFactory = $textItemFactory;
        $this->imageItemFactory = $imageItemFactory;
        parent::__construct($data);
    }

    /**
     * view name (i.e 'front')
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData(DesignViewInterface::NAME);
    }

    /**
     * view id
     *
     * @return string
     */
    public function getViewId()
    {
        return $this->getData(DesignViewInterface::VIEW_ID);
    }

    /**
     * array of text items for the design view
     *
     * @return \Artifi\Personalize\Api\Data\DesignViewTextItemInterface[]
     */
    public function getText()
    {
        return $this->getNodeData(DesignViewInterface::TEXT);
    }

    /**
     * array of images for the design view
     *
     * @return \Artifi\Personalize\Api\Data\DesignViewImageItemInterface[]
     */
    public function getImage()
    {
        return $this->getNodeData(DesignViewInterface::IMAGE);

    }

    /**
     * @param $type
     * @return array
     */
    protected function getNodeData($type)
    {
        $node = $this->getData($type);
        $items = [];
        if (is_array($node) && !empty($node)) {
            foreach ($node as $data) {
                switch ($type) {
                    case DesignViewInterface::TEXT:
                        $item = $this->textItemFactory->create();
                        break;
                    case DesignViewInterface::IMAGE:
                        $item = $this->imageItemFactory->create();
                        break;
                }

                $item->addData($data);
                $items[] = $item;
            }
        }

        return $items;
    }
}