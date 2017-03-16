<?php
namespace Artifi\Personalize\Model\ResourceModel;

class Design extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var bool
     */
    protected $_isPkAutoIncrement = false;

    /**
     * @var array
     */
    protected $_serializableFields = [
        'design_params' => [[], []],
        'preview_urls' => [[], []],
        'thumbnail_urls' => [[], []],
    ];

    /**
     * @var array
     */
    protected $encryptedFields = [
        'design_params'
    ];

    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $encryptor;

    /**
     * Class constructor
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\Encryption\EncryptorInterface $encryptor
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor
    )
    {
        parent::__construct($context);
        $this->encryptor = $encryptor;
    }
    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init('artifi_personalize_design', 'design_id');
    }

    /**
     * Perform encryption before save
     *
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Framework\DataObject $object
     * @return $this
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        foreach ($this->encryptedFields as $field) {
            $fieldData = $this->encryptor->encrypt($object->getData($field));
            $object->setData($field, $fieldData);
        }

        return $this;
    }

    /**
     * Decrypt and unserialize \Magento\Framework\DataObject field in an object
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param string $field
     * @param mixed $defaultValue
     * @return void
     */
    protected function _unserializeField(\Magento\Framework\DataObject $object, $field, $defaultValue = null)
    {
        $value = $object->getData($field);
        if (in_array($field, $this->encryptedFields)) {
            $value = $this->encryptor->decrypt($value);
            $object->setData($field, $value);
        }
        parent::_unserializeField($object, $field, $defaultValue);
    }
}
