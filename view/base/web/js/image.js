/**
 * Wishlist Preview Image Display
 * @param {type} $
 * @param {type} $ui
 * @param {type} abstractWidget
 * @returns {$telerik.$.artifi.wishlistImage|$.artifi.wishlistImage}
 */
define([
    'jquery',
    'jquery/ui',
    'Artifi_Personalize/js/widget'
], function ($, $ui, abstractWidget) {
    'use strict';
    $.widget('artifi.wishlistImage', abstractWidget, {
        _create: function () {
            abstractWidget.prototype._create.call(this);
            this.element.find('span').find('span').find('img').attr('src', this.options.thumbImage);
        }
    });
    return $.artifi.wishlistImage;
});