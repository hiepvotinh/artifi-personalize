var config = {
    map: {
        '*': {
            // Register aliases for AMD modules
            'personalizationEditor': 'Artifi_Personalize/js/editor',
            'personalizationButton': 'Artifi_Personalize/js/button',

            // Override Knockout.js templates
            'Magento_Checkout/template/minicart/item/default.html' : 'Artifi_Personalize/template/minicart/item/default.html'
        }
    }
};
