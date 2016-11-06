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
		}
		
		#interactive.viewport
		{
			position: relative;
		}
	</style>
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2" id="change-column" style="transition: all 0.3s ease;">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">
							<i class="fa fa-plus"></i>
							Add a book
						</h3>
					</div>
					
					<div id="interactive" class="viewport">
						<video autoplay="true" preload="auto" src=""></video>
						<canvas class="drawingBuffer" width="640" height="480"></canvas>
					</div>
					
					<div class="panel-body">
						<form class="form-inline">
							<div class="form-group">
								<label for="search">Or search:</label>
								<input id="search" class="search form-control" type="search"/>
								<button type="button" class="btn btn-primary search">Search</button>
							</div>
						</form>
					</div>
				</div>
				
				<div class="results-search">
					<!-- card list -->
					<div class="flex-card-list" id="search-results">
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
							console.log(result);
							
							var books = result.items;
							var $listOfResults = $("#search-results");
							$listOfResults.empty();
							for (var book in books) {
								var $bookAuthor = $('<h3/>').html(books[ book ].volumeInfo.title);
								var $bookTitle;
								if(books[book].volumeInfo.hasOwnProperty('authors'))
								{
									$bookTitle = $('<h4/>').html(formatArray(books[ book ].volumeInfo.authors));
								}
								else {
									$bookTitle = $('<h4/>').html("");
								}
								var $flexCardContent = $('<div/>').addClass('flex-card-content').append($bookAuthor).append($bookTitle);
								var $image = $("<img>");
								if (books[ book ].volumeInfo.hasOwnProperty("imageLinks")) {
									$image.attr("src", books[ book ].volumeInfo.imageLinks.smallThumbnail);
								}
								else {
									$image.attr("src", "/imgs/nocover1.png");
								}
								var $flexCardImage = $("<div/>").addClass('flex-card-image').append($image);
								var $flexCard = $("<div/>").addClass('flex-card').append($flexCardImage).append($flexCardContent);
								var $newBook = $("<a/>").addClass('flex-card-listitem').attr("href", "/book/add/" + books [ book ].id).append($flexCard);
								
								$listOfResults.append($newBook);
							}
							
							var $bookAuthorNew = $('<h3/>').html("Book not found?");
							var $bookTitleNew = $('<h4/>').html("Add a new one!");
							var $flexCardContentNew = $('<div/>').addClass('flex-card-content').append($bookAuthorNew).append($bookTitleNew);
							var $imageNew = $("<img>");
							$imageNew.attr("src", "/imgs/addcover1.png");
							var $flexCardImageNew = $("<div/>").addClass('flex-card-image').append($imageNew);
							var $flexCardNew = $("<div/>").addClass('flex-card').append($flexCardImageNew).append($flexCardContentNew);
							var $newBookNew = $("<a/>").addClass('flex-card-listitem').attr("href", "/book/add/new").append($flexCardNew);
							$listOfResults.append($newBookNew);
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
		
		$("#search").typeWatch(options);
		
		$("#search").focusin(function () {
			$('#interactive').slideUp(function () {
				$('#change-column').removeClass();
			});
			
			
		});
		$("#search").focusout(function () {
			if (this.value.length == 0) {
				$('#change-column').addClass('col-md-8 col-md-offset-2');
				setTimeout(function () {
					$('#interactive').slideDown();
				}, 300);
			}
		});
		
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
							window.location = "{{ route('book.add') }}/" + result.items[ 0 ].id;
						},
						dataType: "json"
					});
				}
			});
		});
		
		function formatArray( arr ) {
			var outStr = "";
			if (arr.length === 1) {
				outStr = arr[ 0 ];
			}
			else if (arr.length === 2) {
				//joins all with "and" but no commas
				//example: "bob and sam"
				outStr = arr.join(' and ');
			}
			else if (arr.length > 2) {
				//joins all with commas, but last one gets ", and" (oxford comma!)
				//example: "bob, joe, and sam"
				outStr = arr.slice(0, -1).join(', ') + ', and ' + arr.slice(-1);
			}
			return outStr;
		}
	</script>
@endsection