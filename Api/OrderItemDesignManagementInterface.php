<?php
/**
 * DesignManagementInterface.php
 *
 * @category Artifi
 * @package Artifi_Personalize
 * @author Alexexy Poletaev <alexey.poletaev@cyberhull.com>
 */

namespace Artifi\Personalize\Api;

use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Interface DesignManagementInterface
 *
 * @category Artifi
 * @package Artifi_Personalize
 * @api
 */
interface OrderItemDesignManagementInterface
{
    /**
     * get design entity by order item id
     *
     * @param int $itemId order item id
     * @return \Artifi\Personalize\Api\Data\DesignInterface|null
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function getDesign($itemId);

    /**
     * Lists design entities that match specified search criteria.
     *
     * @param SearchCriteria $searchCriteria The search criteria.
     * @return \Artifi\Personalize\Api\Data\DesignSearchResultInterface Design search result interface.
     */
    public function getDesignList(SearchCriteria $searchCriteria);
}
