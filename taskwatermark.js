/**
 * TaskWatermark plugin script
 *
 * @licstart  The following is the entire license notice for the
 * JavaScript code in this file.
 *
 * Copyright (C) 2018 Philip Weir
 *
 * The JavaScript code in this page is free software: you can redistribute it
 * and/or modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation, either version 3 of
 * the License, or (at your option) any later version.
 *
 * @licend  The above is the entire license notice
 * for the JavaScript code in this file.
 */

rcube_webmail.prototype.taskwatermark_enable = function(is_spam) {
    var lock = this.set_busy(true, 'loading');
    this.http_post('plugin.taskwatermark.enable', {}, lock);
}

$(document).ready(function() {
    if (window.rcmail) {
        var afterlist = false;

        rcmail.addEventListener('afterlist', function() {
            afterlist = true;
        });

        rcmail.addEventListener('listupdate', function() {
            if (afterlist && rcmail.env.display_first && $('#' + rcmail.env.contentframe).is(':visible')) {
                rcmail.message_list.select_first();
                afterlist = false;
            }
        });
    }
});