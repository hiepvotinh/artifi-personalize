define([
    'module'
], function (module) {
    'use strict';

    // Expose the module configuration to dependent modules, effectively sharing it between them
    return module.config();
});
