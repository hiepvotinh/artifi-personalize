define([
    'jquery',
    'jquery/ui'
], function ($) {
    'use strict';

    $.widget('artifi.widget', {

        /**
         * Initialize callbacks to be executed upon the widget destruction
         */
        _create: function () {
            this._destructors = [];
        },

        /**
         * Execute all registered destructors
         */
        _destroy: function () {
            $.each(this._destructors, function (i, destructor) {
                destructor();
            });
            this._destructors = [];
        },

        /**
         * Subscribe to an element's event and register symmetrical destructor to remove the subscription
         */
        _subscribe: function (element, event, handler) {
            $(element).on(event, handler);
            this._destructors.push(function () {
                $(element).off(event, handler);
            });
        }

    });

    return $.artifi.widget;
});
