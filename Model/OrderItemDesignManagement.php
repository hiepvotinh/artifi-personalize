<?php
/**
 * OrderItemDesignManagement.php
 *
 * @category Artifi
 * @package Artifi_Personalize
 * @author Alexexy Poletaev <alexey.poletaev@cyberhull.com>
 */

namespace Artifi\Personalize\Model;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Model\Order\ItemRepository;
use Artifi\Personalize\Api\Data\DesignInterface;
use Artifi\Personalize\Api\OrderItemDesignManagementInterface;
use Artifi\Personalize\Api\DesignRepositoryInterface;
use Artifi\Personalize\Api\Data\DesignSearchResultInterfaceFactory as SearchResultFactory;

/**
 * Class OrderItemDesignManagement
 *
 * @category Artifi
 * @package Artifi_Personalize
 */
class OrderItemDesignManagement implements OrderItemDesignManagementInterface
{
    /**
     * @var \Artifi\Personalize\Model\DesignRepository
     */
    protected $designRepository;

    /**
     * @var ItemRepository
     */
    protected $itemRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var FilterBuilder
     */
    protected $filterBuilder;

    /**
     * @var SearchResultFactory
     */
    protected $searchResultFactory;

    /**
     * OrderItemDesignManagement constructor.
     *
     * @param DesignRepositoryInterface $designRepository
     * @param ItemRepository $itemRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     * @param SearchResultFactory $searchResultFactory
     */
    public function __construct(
        DesignRepositoryInterface $designRepository,
        ItemRepository $itemRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        SearchResultFactory $searchResultFactory
    ) {
        $this->designRepository = $designRepository;
        $this->itemRepository = $itemRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->searchResultFactory = $searchResultFactory;
    }

    /**
     * get design entity by order item id
     *
     * @param int $itemId order item id
     * @return \Artifi\Personalize\Api\Data\DesignInterface|null
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function getDesign($itemId)
    {
        /** @var \Magento\Sales\Model\Order\Item $item */
        $item = $this->itemRepository->get($itemId);
        /** @var int|null $designId */
        $designId = $item->getProductOptionByCode('design_id');
        if (!$designId) {
            return null;
        }
        /** @var \Artifi\Personalize\Model\Design $design */
        $design = $this->designRepository->get($designId);
        $design->setItemId($itemId);
        return $design;
    }

    /**
     * Searches design entities by order item ids
     *
     * usage example:
     *
     * curl -g
     *      -H "Accept: application/xml"
     *      -H "Authorization: Bearer <token>"
     *      -X GET "http://domain/index.php/rest/V1/orders/items/personalization?
     *          searchCriteria[filterGroups][0][filters][0][field]=item_id&
     *          searchCriteria[filterGroups][0][filters][0][value]=<item_id1>,<item_id2>&
     *          searchCriteria[filterGroups][0][filters][0][condition_type]=in"
     *
     * @param SearchCriteria $searchCriteria The search criteria.
     * @return \Artifi\Personalize\Api\Data\DesignSearchResultInterface Design search result interface.
     */
    public function getDesignList(SearchCriteria $searchCriteria)
    {
        // Find matching order items
        $orderItems = $this->itemRepository->getList($searchCriteria);

        // Collect IDs of design entities and their association with order items
        $designIds = [];
        $designToItemMap = [];
        /** @var \Magento\Sales\Model\Order\Item $item */
        foreach ($orderItems as $item) {
            /** @var int|null $designId */
            $designId = $item->getProductOptionByCode('design_id');
            if ($designId) {
                $designIds[] = $designId;
                $designToItemMap[$designId] = $item->getId();
            }
        }

        if ($designIds) {
            // Fetch design entities for order items
            $designSearchCriteria = $this->createSearchCriteria('design_id', 'in', implode(',', $designIds));
            $searchResult = $this->designRepository->getList($designSearchCriteria);
        } else {
            // None of matching order items has a design associated with it
            $searchResult = $this->createEmptySearchResults();
        }

        // Associate design entities with respective order items
        /** @var \Artifi\Personalize\Model\Design $design */
        foreach ($searchResult->getItems() as $design) {
            if (isset($designToItemMap[$design->getId()])) {
                $design->setItemId($designToItemMap[$design->getId()]);
            }
        }

        $searchResult->setSearchCriteria($searchCriteria);

        return $searchResult;
    }

    /**
     * @param string $fieldName
     * @param string $conditionType
     * @param mixed $value
     * @return SearchCriteria
     */
    protected function createSearchCriteria($fieldName, $conditionType, $value)
    {
        $filter = $this->filterBuilder
            ->setField($fieldName)
            ->setConditionType($conditionType)
            ->setValue($value)
            ->create();

        $this->searchCriteriaBuilder->addFilters([$filter]);
        return $this->searchCriteriaBuilder->create();
    }

    /**
     * @return \Artifi\Personalize\Api\Data\DesignSearchResultInterface
     */
    protected function createEmptySearchResults()
    {
        /** @var \Artifi\Personalize\Api\Data\DesignSearchResultInterface $searchResult */
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setItems([]);
        $searchResult->setTotalCount(0);
        return $searchResult;
    }
}
