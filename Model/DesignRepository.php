<?php
/**
 * PersonalizationRepository.php
 *
 * @category Artifi
 * @package Artifi_Personalize
 * @author Alexexy Poletaev <alexey.poletaev@cyberhull.com>
 */

namespace Artifi\Personalize\Model;

use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Artifi\Personalize\Api\Data\DesignInterface;
use Artifi\Personalize\Api\DesignRepositoryInterface;
use Artifi\Personalize\Api\Data\DesignSearchResultInterfaceFactory as SearchResultFactory;
use Artifi\Personalize\Model\ResourceModel\Design\Collection as DesignCollection;
use Artifi\Personalize\Model\ResourceModel\Design\CollectionFactory as DesignCollectionFactory;

/**
 * Class DesignRepository
 *
 * @category Artifi
 * @package Artifi_Personalize
 */
class DesignRepository implements DesignRepositoryInterface
{
    /**
     * @var DesignFactory
     */
    protected $designFactory;

    /**
     * @var DesignCollectionFactory
     */
    protected $designCollectionFactory;

    /**
     * @var SearchResultFactory
     */
    protected $searchResultFactory;

    /**
     * @var array
     */
    protected $registry = [];

    /**
     * DesignRepository constructor.
     *
     * @param DesignFactory $designFactory
     * @param DesignCollectionFactory $designCollectionFactory
     * @param SearchResultFactory $searchResultFactory
     */
    public function __construct(
        DesignFactory $designFactory,
        DesignCollectionFactory $designCollectionFactory,
        SearchResultFactory $searchResultFactory
    ) {
        $this->designFactory = $designFactory;
        $this->designCollectionFactory = $designCollectionFactory;
        $this->searchResultFactory = $searchResultFactory;
    }

    /**
     * Loads a specified design item.
     *
     * @param int $id The design ID.
     * @return DesignInterface design interface.
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function get($id)
    {
        if (!$id) {
            throw new InputException(__('ID required'));
        }
        if (!isset($this->registry[$id])) {
            /** @var \Artifi\Personalize\Model\Design $design */
            $design = $this->designFactory->create();
            $design->load($id);
            if (!$design->getId()) {
                throw new NoSuchEntityException(__('Requested entity doesn\'t exist'));
            }
            $this->registry[$id] = $design;
        }

        return $this->registry[$id];
    }

    /**
     * Lists design entities that match specified search criteria.
     *
     * usage example:
     * $filter = $this->filterBuilder
     *     ->setField('design_id')
     *     ->setConditionType('in')
     *     ->setValue('55906, 55907')
     *     ->create();
     * $this->searchCriteriaBuilder->addFilters([$filter]);
     * $searchCriteria = $this->searchCriteriaBuilder->create();
     *
     * @param SearchCriteria $searchCriteria The search criteria.
     * @return \Artifi\Personalize\Api\Data\DesignSearchResultInterface Design search result interface.
     */
    public function getList(SearchCriteria $searchCriteria)
    {
        /** @var DesignCollection $collection */
        $collection = $this->designCollectionFactory->create();
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }

        /** @var \Artifi\Personalize\Api\Data\DesignSearchResultInterface $searchResult */
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult;
    }
}
