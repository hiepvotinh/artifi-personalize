<?php
namespace Artifi\Personalize\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Artifi\Personalize\Api\Data\DesignInterface;
use Artifi\Personalize\Api\Data\DesignViewInterface;
use Artifi\Personalize\Helper\Url;
use Artifi\Personalize\Model\Design\ViewFactory;

/**
 * @method array getDesignParams()
 * @method $this setDesignParams(array $params)
 * @method array getPreviewUrls()
 * @method $this setPreviewUrls(array $urls)
 * @method array getThumbnailUrls()
 * @method $this setThumbnailUrls(array $urls)
 */
class Design extends AbstractModel implements DesignInterface
{
    /**
     * @var Url
     */
    protected $urlHelper;

    /**
     * @var ViewFactory
     */
    protected $viewFactory;

    /**
     * Design constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param Url $urlHelper
     * @param ViewFactory $viewFactory
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Url $urlHelper,
        ViewFactory $viewFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->urlHelper = $urlHelper;
        $this->viewFactory = $viewFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(\Artifi\Personalize\Model\ResourceModel\Design::class);
    }

    /**
     * @return string
     */
    public function getMainPreviewUrl()
    {
        $urls = $this->getPreviewUrls();
        return reset($urls) ?: '';
    }

    /**
     * @return string
     */
    public function getMainThumbnailUrl()
    {
        $urls = $this->getThumbnailUrls();
        return reset($urls) ?: '';
    }

    /**
     * set item id
     *
     * @param $id
     * @throws InputException
     */
    public function setItemId($id)
    {
        if (!$id) {
            throw new InputException(__('Item ID required'));
        }

        $this->setData(DesignInterface::ITEM_ID, $id);
    }

    /**
     * retrieve order item id
     *
     * @return int
     */
    public function getItemId()
    {
        return $this->getData(DesignInterface::ITEM_ID);
    }

    /**
     * retrieve design id
     *
     * @return int
     */
    public function getDesignId()
    {
        return $this->getData(DesignInterface::DESIGN_ID);
    }

    /**
     * retrieve rendered pdf url
     *
     * @return string
     */
    public function getRenderedPdf()
    {
        $designId = $this->getDesignId();
        return $this->urlHelper->getRenderedPdfUrl($designId);
    }

    /**
     * get views for the design entity
     *
     * @return \Artifi\Personalize\Api\Data\DesignViewInterface[]
     */
    public function getViews()
    {
        $params = $this->getData(DesignInterface::DESIGN_PARAMS);
        $views = [];
        if (is_array($params) && !empty($params)) {
            foreach ($params as $name => $data) {
                $view = $this->viewFactory->create();
                $data[DesignViewInterface::NAME] = $name;
                $view->addData($data);
                $views[] = $view;
            }
        }

        return $views;
    }
}
