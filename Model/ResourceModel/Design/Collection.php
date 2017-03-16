<?php
namespace Artifi\Personalize\Model\ResourceModel\Design;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * collection initialization
     */
    protected function _construct()
    {
        $this->_init(\Artifi\Personalize\Model\Design::class, \Artifi\Personalize\Model\ResourceModel\Design::class);
    }

    /**
     * unserialize fields
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        parent::_afterLoad();
        /** @var \Artifi\Personalize\Model\Design $item */
        foreach ($this->_items as $item) {
            $this->getResource()->unserializeFields($item);
        }
        return $this;
    }
}
