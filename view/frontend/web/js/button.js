define([
    'jquery',
    'jquery/ui',
    'Artifi_Personalize/js/widget',
    'Magento_Ui/js/modal/modal',
    'mage/apply/main'
], function ($, $ui, abstractWidget, modal, mage) {
    'use strict';

    $.widget('artifi.personalizationButton', abstractWidget, {

        options: {
            editorUrl: '',
            editorParams: {},
            modalOptions: {
                autoOpen: true,
                responsive: true,
                buttons: []
            }
        },

        _create: function() {
            abstractWidget.prototype._create.call(this);

            var addToCartForm = $(this.element).closest('form');

            this._subscribe(this.element, 'click', function (e) {
                var formData = {};
                if (addToCartForm.length) {
                    // Validate form data entry
                    if (!addToCartForm.valid()) {
                        return false;
                    }
                    formData = addToCartForm.serialize();
                }
                this._renderEditorHtml(formData, this._displayPopup.bind(this));
                return false;
            }.bind(this));
        },

        _renderEditorHtml: function (formData, callback) {
            $.ajax({
                url: this.options.editorUrl + '?' + $.param(this.options.editorParams),
                type: 'post',
                data: formData,
                context: $('body'),
                success: callback,
                showLoader: true
            });
        },

        _displayPopup: function (html) {
            var popup = $('<div />');
            popup.append(html);

            var modalOptions = this.options.modalOptions;
            modalOptions.closed = function () {
                // Destroy DOM elements and associated jQuery widgets all the way down the DOM tree
                popup.remove();
            };
            popup.modal(modalOptions);

            // Apply newly added '<script type="text/x-magento-init">' and '<div data-mage-init="">'
            mage.apply();
        }
    });

    return $.artifi.personalizationButton;
});
