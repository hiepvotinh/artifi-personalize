<?php
/**
 * DesignRepositoryInterface.php
 *
 * @category Artifi
 * @package Artifi_Personalize
 * @author Alexexy Poletaev <alexey.poletaev@cyberhull.com>
 */

namespace Artifi\Personalize\Api;

use Magento\Framework\Api\SearchCriteria;
use Artifi\Personalize\Api\Data\DesignInterface;

/**
 * Design repository interface.
 *
 * @category Artifi
 * @package Artifi_Personalize
 * @api
 */
interface DesignRepositoryInterface
{
    /**
     * Loads a specified design item.
     *
     * @param int $id The design ID.
     * @return DesignInterface design interface.
     */
    public function get($id);

    /**
     * Lists design entities that match specified search criteria.
     *
     * @param SearchCriteria $searchCriteria The search criteria.
     * @return \Artifi\Personalize\Api\Data\DesignSearchResultInterface Design search result interface.
     */
    public function getList(SearchCriteria $searchCriteria);
}