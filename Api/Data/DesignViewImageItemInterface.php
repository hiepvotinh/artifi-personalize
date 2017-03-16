<?php
/**
 * DesignViewImageItemInterface.php
 *
 * @category Artifi
 * @package Artifi_Personalize
 * @author Alexexy Poletaev <alexey.poletaev@cyberhull.com>
 */

namespace Artifi\Personalize\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface DesignViewImageItemInterface
 *
 * @category Artifi
 * @package Artifi_Personalize
 * @api
 */
interface DesignViewImageItemInterface extends ExtensibleDataInterface
{
    /**
     * @var string
     */
    const WIDGET_KEY = 'widgetKey';

    /**
     * @var string
     */
    const SRC = 'src';

    /**
     * @return string
     */
    public function getKey();

    /**
     * @return string
     */
    public function getSrc();
}