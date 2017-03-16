<?php
/**
 * DesignInterface.php
 *
 * @category Artifi
 * @package Artifi_Personalize
 * @author Alexexy Poletaev <alexey.poletaev@cyberhull.com>
 */

namespace Artifi\Personalize\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface DesignInterface
 *
 * @category Artifi
 * @package Artifi_Personalize
 * @api
 */
interface DesignInterface extends ExtensibleDataInterface
{
    /**
     * order item id
     *
     * @var string
     */
    const ITEM_ID = 'item_id';

    /**
     * design id
     *
     * @var string
     */
    const DESIGN_ID = 'design_id';

    /**
     * design data
     *
     * @var string
     */
    const DESIGN_PARAMS = 'design_params';

    /**
     * returns order item id
     *
     * @return int | null
     */
    public function getItemId();

    /**
     * returns design id
     *
     * @return int
     */
    public function getDesignId();

    /**
     * returns rendered pdf url
     *
     * @return string
     */
    public function getRenderedPdf();

    /**
     * get views for the design entity
     *
     * @return \Artifi\Personalize\Api\Data\DesignViewInterface[] Array of views
     */
    public function getViews();
}
