define([
    'artifi',
    'artifiConfig',
    'jquery',
    'jquery/ui',
    'Artifi_Personalize/js/widget'
], function (Artifi, ArtifiConfig, $, $ui, abstractWidget) {
    'use strict';

    $.widget('artifi.personalizationPreviewButton', abstractWidget, {

        options: {
            previewOptions: {},
            modalOptions: {
                autoOpen: true,
                responsive: true,
                buttons: []
            }
        },

        _create: function() {
            abstractWidget.prototype._create.call(this);

            $.extend(this.options.previewOptions, ArtifiConfig);

            this._subscribe(this.element, 'click', function (e) {
                var previewUrl = Artifi.getPreviewUrl(this.options.previewOptions);

                var popup = $('<div class="artifi-container" />');
                popup.html('<iframe src="' + previewUrl + '" frameborder="0" width="100%" height="100%"></iframe>');

                var modalOptions = this.options.modalOptions;
                modalOptions.closed = function () {
                    popup.remove();
                };
                popup.modal(modalOptions);

                return false;
            }.bind(this));
        }
    });

    return $.artifi.personalizationPreviewButton;
});
