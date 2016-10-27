@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading">Find a book</div>
					
					<div class="panel-body">
						<form>
							<div class="input-field">
								<label for="search">Search:</label>
								<input id="search" class="search" type="search"/>
								<button type="button" class="search">Search</button>
							</div>
						</form>
						<div class="results-search">
							<ul id="search-results"></ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="/js/jquery.min.js"></script>
	<script>
		!function ( root, factory ) {
			if (typeof define === 'function' && define.amd) {
				define([ 'jquery' ], factory);
			}
			else if (typeof exports === 'object') {
				factory(require('jquery'));
			}
			else {
				factory(root.jQuery);
			}
		}(this, function ( $ ) {
			'use strict';
			$.fn.typeWatch = function ( o ) {
				// The default input types that are supported
				var _supportedInputTypes =
						[ 'TEXT', 'TEXTAREA', 'PASSWORD', 'TEL', 'SEARCH', 'URL', 'EMAIL', 'DATETIME', 'DATE', 'MONTH', 'WEEK', 'TIME', 'DATETIME-LOCAL', 'NUMBER', 'RANGE', 'DIV' ];
				
				// Options
				var options = $.extend({
					wait: 750,
					callback: function () {
					},
					highlight: true,
					captureLength: 2,
					allowSubmit: false,
					inputTypes: _supportedInputTypes
				}, o);
				
				function checkElement( timer, override ) {
					var value = timer.type === 'DIV'
							? jQuery(timer.el).html()
							: jQuery(timer.el).val();
					
					// If has capture length and has changed value
					// Or override and has capture length or allowSubmit option is true
					// Or capture length is zero and changed value
					if ((value.length >= options.captureLength && value != timer.text)
							|| (override && (value.length >= options.captureLength || options.allowSubmit))
							|| (value.length == 0 && timer.text)) {
						timer.text = value;
						timer.cb.call(timer.el, value);
					}
				}
				
				function watchElement( elem ) {
					var elementType = (elem.type || elem.nodeName).toUpperCase();
					if (jQuery.inArray(elementType, options.inputTypes) >= 0) {
						
						// Allocate timer element
						var timer = {
							timer: null,
							text: (elementType === 'DIV') ? jQuery(elem).html() : jQuery(elem).val(),
							cb: options.callback,
							el: elem,
							type: elementType,
							wait: options.wait
						};
						
						// Set focus action (highlight)
						if (options.highlight && elementType !== 'DIV') {
							jQuery(elem).focus(function () {
								this.select();
							});
						}
						
						// Key watcher / clear and reset the timer
						var startWatch = function ( evt ) {
							var timerWait = timer.wait;
							var overrideBool = false;
							// If enter key is pressed and not a TEXTAREA or DIV
							if (typeof evt.keyCode != 'undefined' && evt.keyCode == 13
									&& elementType !== 'TEXTAREA' && elementType !== 'DIV') {
								console.log('OVERRIDE');
								timerWait = 1;
								overrideBool = true;
							}
							
							var timerCallbackFx = function () {
								checkElement(timer, overrideBool)
							};
							
							// Clear timer
							clearTimeout(timer.timer);
							timer.timer = setTimeout(timerCallbackFx, timerWait);
						};
						
						jQuery(elem).on('keydown paste cut input', startWatch);
					}
				}
				
				// Watch each element
				return this.each(function () {
					watchElement(this);
				});
			};
		});
		
		$(".input-field #search").typeWatch(options);
	</script>
@endsection