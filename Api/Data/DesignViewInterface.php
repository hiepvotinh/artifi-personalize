<?php
/**
 * ViewInterface.php
 *
 * @category Artifi
 * @package Artifi_Personalize
 * @author Alexexy Poletaev <alexey.poletaev@cyberhull.com>
 */

namespace Artifi\Personalize\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface ViewInterface
 *
 * @category Artifi
 * @package Artifi_Personalize
 * @api
 */
interface DesignViewInterface extends ExtensibleDataInterface
{
    /**
     * @var string
     */
    const NAME = 'name';

    /**
     * @var string
     */
    const VIEW_ID = 'viewId';

    /**
     * @var string
     */
    const TEXT = 'text';

    /**
     * @var string
     */
    const IMAGE = 'image';

    /**
     * view name (i.e 'front')
     *
     * @return string
     */
    public function getName();

    /**
     * view id
     *
     * @return string
     */
    public function getViewId();

    /**
     * array of text items for the design view
     *
     * @return \Artifi\Personalize\Api\Data\DesignViewTextItemInterface[]
     */
    public function getText();

    /**
     * array of images for the design view
     *
     * @return \Artifi\Personalize\Api\Data\DesignViewImageItemInterface[]
     */
    public function getImage();
}
