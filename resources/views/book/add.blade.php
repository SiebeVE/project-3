@extends('layouts.app')

@section('header_left')
	<a href="/">
		<i class="fa fa-chevron-left" aria-hidden="true"></i>
		Home
	</a>
@endsection

@section('content')
	<style>
		canvas.drawing, canvas.drawingBuffer
		{
			position: absolute;
			left: 0;
			top: 0;
			width: 100%;
		}
		
		video
		{
			width: 100%;
		}
		
		#interactive.viewport
		{
			position: relative;
		}
	</style>
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading">Add a book</div>
					
					<div class="panel-body">
						<div id="interactive" class="viewport">
							<video autoplay="true" preload="auto" src=""></video>
							<canvas class="drawingBuffer"></canvas>
							<br clear="all">
						</div>
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
	<script src="/js/quagga.js"></script>
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
		
		var api_key_book = "{{ env("API_KEY_BOOK") }}";
		var api_url_book = "{{ env("API_URL_BOOK") }}";
		
		var options = {
			callback: function ( currentSearch ) {
				if (!(currentSearch == "" && currentSearch == null )) {
					$.ajax({
						url: api_url_book,
						data: {
							q: currentSearch,
							key: api_key_book
						},
						success: function ( result ) {
							if (result.totalItems > 0) {
								var books = result.items;
								var $listOfResults = $("#search-results");
								$listOfResults.empty();
								for (var book in books) {
									var $newBook = $("<li>");
									var $link = $("<a>").attr("href", "/book/add/" + books [ book ].id);
									var $image = $("<img>");
									if (books[ book ].volumeInfo.hasOwnProperty("imageLinks")) {
										$image.attr("src", books[ book ].volumeInfo.imageLinks.smallThumbnail);
									}
									else {
										$image.attr("src", "https://www.hachettebookgroup.com/_b2c/static/site_theme/img/missingbook.png");
									}
									$newBook.append($link.append($image));
									$listOfResults.append($newBook);
								}
							}
							console.log(result);
						},
						dataType: "json"
					});
				}
			},
			wait: 250,
			highlight: true,
			allowSubmit: false,
			captureLength: 2
		};
		
		$(".input-field #search").typeWatch(options);
		
		$(function () {
			var App = {
				init: function () {
					Quagga.init(this.state, function ( err ) {
						if (err) {
							console.log(err);
							return;
						}
						Quagga.start();
					});
				},
				state: {
					inputStream: {
						type: "LiveStream",
						constraints: {
							width: {min: 640},
							height: {min: 480},
							aspectRatio: {min: 1, max: 100},
							facingMode: "environment" // or user
						}
					},
					locator: {
						patchSize: "medium",
						halfSample: true
					},
					numOfWorkers: 4,
					decoder: {
						readers: [
							{
								format: "ean_reader",
								config: {}
							} ]
					},
					locate: true
				},
				lastResult: null
			};
			
			App.init();
			
			Quagga.onDetected(function ( result ) {
				var code = result.codeResult.code;
				
				if (App.lastResult !== code) {
					App.lastResult = code;
					console.log(result);
					 var barcode = result.codeResult.code;
//					var barcode = "9780312325862";
					
					$.ajax({
						url: api_url_book,
						data: {
							q: barcode,
							key: api_key_book
						},
						success: function ( result ) {
							console.log(result);
							window.location = "{{ route('book.add') }}/"+result.items[0].id;
						},
						dataType: "json"
					});
				}
			});
		});
	</script>
@endsection