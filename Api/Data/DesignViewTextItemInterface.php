<?php
/**
 * DesignViewTextItemInterface.php
 *
 * @category Artifi
 * @package Artifi_Personalize
 * @author Alexexy Poletaev <alexey.poletaev@cyberhull.com>
 */

namespace Artifi\Personalize\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface DesignViewTextItemInterface
 *
 * @category Artifi
 * @package Artifi_Personalize
 * @api
 */
interface DesignViewTextItemInterface extends ExtensibleDataInterface
{
    /**
     * widget key
     *
     * @var string
     */
    const WIDGET_KEY = 'widgetKey';

    /**
     * text
     *
     * @var string
     */
    const TEXT = 'text';

    /**
     * font family
     *
     * @var string
     */
    const FONT_FAMILY = 'fontFamily';

    /**
     * color
     *
     * @var string
     */
    const COLOR = 'color';

    /**
     * stroke
     *
     * @var string
     */
    const STROKE = 'stroke';

    /**
     * @return string
     */
    public function getKey();

    /**
     * @return string
     */
    public function getText();

    /**
     * @return string
     */
    public function getFontFamily();

    /**
     * @return string
     */
    public function getColor();

    /**
     * @return string
     */
    public function getStroke();
}