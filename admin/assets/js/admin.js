( function( $ ) {

	/**
	 * AJAX Request Queue
	 *
	 * - add()
	 * - remove()
	 * - run()
	 * - stop()
	 *
	 * @since 1.2.0.8
	 */
	var GutenbergPackAjaxQueue = (function() {

		var requests = []

		return {

			/**
			 * Add AJAX request
			 *
			 * @since 1.2.0.8
			 */
			add:  function(opt) {
			    requests.push(opt)
			},

			/**
			 * Remove AJAX request
			 *
			 * @since 1.2.0.8
			 */
			remove:  function(opt) {
			    if( jQuery.inArray(opt, requests) > -1 )
			        requests.splice($.inArray(opt, requests), 1)
			},

			/**
			 * Run / Process AJAX request
			 *
			 * @since 1.2.0.8
			 */
			run: function() {
			    var self = this,
			        oriSuc

			    if( requests.length ) {
			        oriSuc = requests[0].complete

			        requests[0].complete = function() {
			             if( typeof(oriSuc) === "function" ) oriSuc()
			             requests.shift()
			             self.run.apply(self, [])
			        }

			        jQuery.ajax(requests[0])

			    } else {

			      self.tid = setTimeout(function() {
			         self.run.apply(self, [])
			      }, 1000)
			    }
			},

			/**
			 * Stop AJAX request
			 *
			 * @since 1.2.0.8
			 */
			stop:  function() {

			    requests = []
			    clearTimeout(this.tid)
			}
		}

	}())

	GutenbergPackAdmin = {

		init: function() {
			/**
			 * Run / Process AJAX request
			 */
			GutenbergPackAjaxQueue.run()

			$( document ).delegate( ".gutenberg-pack-activate-widget", "click", GutenbergPackAdmin._activate_widget )
			$( document ).delegate( ".gutenberg-pack-deactivate-widget", "click", GutenbergPackAdmin._deactivate_widget )

			$( document ).delegate( ".gutenberg-pack-activate-all", "click", GutenbergPackAdmin._bulk_activate_widgets )
			$( document ).delegate( ".gutenberg-pack-deactivate-all", "click", GutenbergPackAdmin._bulk_deactivate_widgets )
		},

		/**
		 * Activate All Widgets.
		 */
		_bulk_activate_widgets: function( e ) {
			var button = $( this )

			var data = {
				action: "gutenberg_pack_bulk_activate_widgets",
				nonce: gutenberg_pack_obj.ajax_nonce,
			}

			if ( button.hasClass( "updating-message" ) ) {
				return
			}

			$( button ).addClass("updating-message")

			GutenbergPackAjaxQueue.add({
				url: ajaxurl,
				type: "POST",
				data: data,
				success: function(data){

					console.log( data )

					// Bulk add or remove classes to all modules.
					$(".gutenberg-pack-widget-list").children( "li" ).addClass( "activate" ).removeClass( "deactivate" )
					$(".gutenberg-pack-widget-list").children( "li" ).find(".gutenberg-pack-activate-widget")
						.addClass("gutenberg-pack-deactivate-widget")
						.text(gutenberg_pack_obj.deactivate)
						.removeClass("gutenberg-pack-activate-widget")
					$( button ).removeClass("updating-message")
				}
			})
			e.preventDefault()
		},

		/**
		 * Deactivate All Widgets.
		 */
		_bulk_deactivate_widgets: function( e ) {
			var button = $( this )

			var data = {
				action: "gutenberg_pack_bulk_deactivate_widgets",
				nonce: gutenberg_pack_obj.ajax_nonce,
			}

			if ( button.hasClass( "updating-message" ) ) {
				return
			}
			$( button ).addClass("updating-message")

			GutenbergPackAjaxQueue.add({
				url: ajaxurl,
				type: "POST",
				data: data,
				success: function(data){

					console.log( data )
					// Bulk add or remove classes to all modules.
					$(".gutenberg-pack-widget-list").children( "li" ).addClass( "deactivate" ).removeClass( "activate" )
					$(".gutenberg-pack-widget-list").children( "li" ).find(".gutenberg-pack-deactivate-widget")
						.addClass("gutenberg-pack-activate-widget")
						.text(gutenberg_pack_obj.activate)
						.removeClass("gutenberg-pack-deactivate-widget")
					$( button ).removeClass("updating-message")
				}
			})
			e.preventDefault()
		},

		/**
		 * Activate Module.
		 */
		_activate_widget: function( e ) {
			var button = $( this ),
				id     = button.parents("li").attr("id")

			var data = {
				block_id : id,
				action: "gutenberg_pack_activate_widget",
				nonce: gutenberg_pack_obj.ajax_nonce,
			}

			if ( button.hasClass( "updating-message" ) ) {
				return
			}

			$( button ).addClass("updating-message")

			GutenbergPackAjaxQueue.add({
				url: ajaxurl,
				type: "POST",
				data: data,
				success: function(data){

					// Add active class.
					$( "#" + id ).addClass("activate").removeClass( "deactivate" )
					// Change button classes & text.
					$( "#" + id ).find(".gutenberg-pack-activate-widget")
						.addClass("gutenberg-pack-deactivate-widget")
						.text(gutenberg_pack_obj.deactivate)
						.removeClass("gutenberg-pack-activate-widget")
						.removeClass("updating-message")
				}
			})

			e.preventDefault()
		},

		/**
		 * Deactivate Module.
		 */
		_deactivate_widget: function( e ) {
			var button = $( this ),
				id     = button.parents("li").attr("id")
			var data = {
				block_id: id,
				action: "gutenberg_pack_deactivate_widget",
				nonce: gutenberg_pack_obj.ajax_nonce,
			}

			if ( button.hasClass( "updating-message" ) ) {
				return
			}

			$( button ).addClass("updating-message")

			GutenbergPackAjaxQueue.add({
				url: ajaxurl,
				type: "POST",
				data: data,
				success: function(data){

					// Remove active class.
					$( "#" + id ).addClass( "deactivate" ).removeClass("activate")

					// Change button classes & text.
					$( "#" + id ).find(".gutenberg-pack-deactivate-widget")
						.addClass("gutenberg-pack-activate-widget")
						.text(gutenberg_pack_obj.activate)
						.removeClass("gutenberg-pack-deactivate-widget")
						.removeClass("updating-message")
				}
			})
			e.preventDefault()
		},

	}

	$( document ).ready(function() {
		GutenbergPackAdmin.init()
	})


} )( jQuery )
