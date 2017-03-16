<?php
/**
 * DesignSearchResultInterface.php
 *
 * @category Artifi
 * @package Artifi_Personalize
 * @author Alexexy Poletaev <alexey.poletaev@cyberhull.com>
 */

namespace Artifi\Personalize\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface DesignSearchResultInterface
 *
 * @category Artifi
 * @package Artifi_Personalize
 */
interface DesignSearchResultInterface extends SearchResultsInterface
{
    /**
     * Get designs list.
     *
     * @api
     * @return \Artifi\Personalize\Api\Data\DesignInterface[]
     */
    public function getItems();

    /**
     * Set designs list.
     *
     * @api
     * @param \Artifi\Personalize\Api\Data\DesignInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}