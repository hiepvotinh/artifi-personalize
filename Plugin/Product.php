<?php
namespace Artifi\Personalize\Plugin;

class Product
{
    /**
     * Add custom option information to product
     * Remove personalization information from buyReuqest
     *
     * @param   string $code    Option code
     * @param   mixed  $value   Value of the option
     * @param   int|Product    $product Product ID
     * @return array
     */
    public function beforeAddCustomOption(
        \Magento\Catalog\Model\Product $subject,
        $code,
        $value,
        $product = null
    ) {
        if ($code == 'info_buyRequest') {
            $buyRequest = unserialize($value);
            if (isset($buyRequest['personalization'])) {
                $buyRequest['personalization'] = [
                    'design_id' => $buyRequest['personalization']['design_id']
                ];
            }
            $value = serialize($buyRequest);
        }
        return [$code, $value, $product];
    }
}
