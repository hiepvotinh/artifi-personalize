<?php
/**
 * Item.php
 *
 * @category Artifi
 * @package Artifi_Personalize
 * @author Alexexy Poletaev <alexey.poletaev@cyberhull.com>
 */

namespace Artifi\Personalize\Model\Design\View\Text;

use Magento\Framework\DataObject;
use Artifi\Personalize\Api\Data\DesignViewTextItemInterface;

/**
 * Class Item
 *
 * @category Artifi
 * @package Artifi_Personalize
 */
class Item extends DataObject implements DesignViewTextItemInterface
{
    /**
     * @return string
     */
    public function getKey()
    {
        return $this->getData(DesignViewTextItemInterface::WIDGET_KEY);
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->getData(DesignViewTextItemInterface::TEXT);
    }

    /**
     * @return string
     */
    public function getFontFamily()
    {
        return $this->getData(DesignViewTextItemInterface::FONT_FAMILY);
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->getData(DesignViewTextItemInterface::COLOR);
    }

    /**
     * @return string
     */
    public function getStroke()
    {
        return $this->getData(DesignViewTextItemInterface::STROKE);
    }
}