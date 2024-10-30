/**
 * Handle: wpCJAdmin
 * Version: 0.0.1
 * Deps: jquery
 * Enqueue: true
 */

var wpCJAdmin = function () {}

wpCJAdmin.prototype = {
    options           : {},
    generateShortCode : function() {
        var attrs = '';
	var keywords = document.getElementById('wpCJ_keywords').value;
	var itemcount = document.getElementById('wpCJ_itemcount').value;

        attrs += (keywords != '') ? ' keywords="' + keywords + '" ' : '';
        attrs += (itemcount != '') ? ' records_per_page="' + itemcount + '" ' : '';

	return '[cj_show_items' + attrs + ']'
    },
    sendToEditor      : function(f) {
        send_to_editor(this.generateShortCode());
        return false;
    }
}

var this_wpCJAdmin = new wpCJAdmin();
