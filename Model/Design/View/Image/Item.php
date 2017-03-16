<?php
/**
 * Item.php
 *
 * @category Artifi
 * @package Artifi_Personalize
 * @author Alexexy Poletaev <alexey.poletaev@cyberhull.com>
 */

namespace Artifi\Personalize\Model\Design\View\Image;

use Magento\Framework\DataObject;
use Artifi\Personalize\Api\Data\DesignViewImageItemInterface;

/**
 * Class Item
 *
 * @category Artifi
 * @package Artifi_Personalize
 */
class Item extends DataObject implements DesignViewImageItemInterface
{
    /**
     * @return string
     */
    public function getKey()
    {
        return $this->getData(DesignViewImageItemInterface::WIDGET_KEY);
    }

    /**
     * @return string
     */
    public function getSrc()
    {
        $src = $this->getData(DesignViewImageItemInterface::SRC);
        $src = $this->expandProtocolRelativeUrl($src);
        return $src;
    }

    /**
     * Expand protocol-relative URL to fully-qualified version
     *
     * @link https://en.wikipedia.org/wiki/Wikipedia:Protocol-relative_URL
     *
     * @param string $url
     * @param string $protocol
     * @return string
     */
    protected function expandProtocolRelativeUrl($url, $protocol = 'http')
    {
        return preg_replace('#^(?=//)#', $protocol . ':', $url);
    }
}
