define([
    'artifi',
    'artifiConfig',
    'jquery',
    'jquery/ui',
    'Artifi_Personalize/js/widget'
], function (Artifi, ArtifiConfig, $, $ui, abstractWidget) {
    'use strict';

    $.widget('artifi.personalizationEditor', abstractWidget, {

        options: {
            editorOptions: {},
            cartUrl: '',
            updateCartUrl: '',
            addToCartUrl: '',
            addToCartData: {}
        },

        _create: function () {
            abstractWidget.prototype._create.call(this);

            $.extend(this.options.editorOptions, ArtifiConfig);

            // Subscribe to the events occurring in the editor's iframe
            this._subscribe(window, 'message onmessage', this.handleEditorMsg.bind(this));

            Artifi.Initialize(this.options.editorOptions);
        },

        handleEditorMsg: function (event) {
            // Ignore irrelevant messages
            var origin = event.origin || event.originalEvent.origin;
            if (!Artifi.isValidArtifiDomain(origin)) {
                return;
            }

            // Parse the message format
            var msgJson = event.originalEvent.data;
            var msg = JSON.parse(msgJson);

            // Dispatch the message to the appropriate handler
            if (msg.action == 'add-to-cart') {
                var isExistingDesign = Boolean(this.options.editorOptions.designId);
                if (isExistingDesign) {
                    this.handleUpdateCartMsg(msg.data);
                } else {
                    this.handleAddToCartMsg(msg.data);
                }
            }
        },

        handleUpdateCartMsg: function (data) {
            $('.artifi-loader').show();
            var formData = this.assembleCartFormData(data);
            formData.product = this.options.product;
            var itemId = this.options.id;
            if(typeof formData.qty === "undefined") {
                formData.qty = '';
            }
            /**
             * Setting qty parameter may not be required
             * Hence may be set as blank
             * Changes to Fix JS error formData.cart is not defined
             */ 
            if(typeof formData.cart !== "undefined") {
                formData.qty = formData.cart[itemId]['qty'] ; //cart[150][qty]
            }
            this.doAjaxPost(this.options.updateCartUrl, formData, this.redirectToCart.bind(this));
        },

        handleAddToCartMsg: function (data) {
            $('.artifi-loader').show();
            var formData = this.assembleCartFormData(data);

            // Prevent success messages from showing up
            formData.return_url = this.options.cartUrl;

            this.doAjaxPost(this.options.addToCartUrl, formData, this.redirectToCart.bind(this));
        },

        assembleCartFormData: function (data) {
            var formData = this.options.addToCartData;
            if( this.options.porductSkuMapping.hasOwnProperty(data.sku) ) {
                var propName = data.sku;
                formData.selected_configurable_option = this.options.porductSkuMapping[propName]['id'];
                for( var key in this.options.porductSkuMapping[propName]['super_attribute']) {
                    //alert(key);
                    //alert(this.options.porductSkuMapping[propName]['super_attribute'][key]);
                    if(typeof formData.super_attribute === "undefined") {
                        formData.super_attribute = {};
                    }
                    formData.super_attribute[key] = this.options.porductSkuMapping[propName]['super_attribute'][key];
                }
            }
            // Add the personalization design data
            formData.personalization = {
                design_id: data.custmizeProductId,
                design_params: data.customizedData,
                preview_urls: data.savedDesignStandardImages,
                thumbnail_urls: data.savedDesigns
            };

            return formData;
        },

        doAjaxPost: function (url, formData, successCallback) {
            $.ajax({
                url: url,
                data: formData,
                type: 'post',
                dataType: 'json',
                success: successCallback
            });
        },

        redirectToCart: function () {
            window.location = this.options.cartUrl;
        }
    });

    return $.artifi.personalizationEditor;
});
