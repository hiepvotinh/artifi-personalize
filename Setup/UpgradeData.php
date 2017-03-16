<?php
namespace Artifi\Personalize\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var EavSetupFactory
     */
    protected $eavSetupFactory;

    /**
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        if (version_compare($context->getVersion(), '3.0.0', '<')) {
            $groupName = 'Personalization';

            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'personalization_allowed',
                [
                    'group' => $groupName,
                    'type' => 'int',
                    'label' => 'Personalization Allowed',
                    'input' => 'select',
                    'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                    'sort_order' => 1,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'default' => '',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'used_in_product_listing' => 1,
                ]
            );

            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'personalization_required',
                [
                    'group' => $groupName,
                    'type' => 'int',
                    'label' => 'Personalization Required',
                    'input' => 'select',
                    'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                    'sort_order' => 2,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'default' => '',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'used_in_product_listing' => 1,
                ]
            );
        }

        if (version_compare($context->getVersion(), '3.1.0', '<')) {
            foreach (['personalization_allowed', 'personalization_required'] as $code) {
                $eavSetup->updateAttribute(\Magento\Catalog\Model\Product::ENTITY, $code, 'default_value', 0);
            }
        }
    }
}
