@extends('layouts.app')

@section('content')
	<style>
		.icon-barcode
		{
			background-size: contain;
			background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB3aWR0aD0iMzIiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAzMiAzMiI+PHBhdGggZD0iTTAgNGg0djIwaC00ek02IDRoMnYyMGgtMnpNMTAgNGgydjIwaC0yek0xNiA0aDJ2MjBoLTJ6TTI0IDRoMnYyMGgtMnpNMzAgNGgydjIwaC0yek0yMCA0aDF2MjBoLTF6TTE0IDRoMXYyMGgtMXpNMjcgNGgxdjIwaC0xek0wIDI2aDJ2MmgtMnpNNiAyNmgydjJoLTJ6TTEwIDI2aDJ2MmgtMnpNMjAgMjZoMnYyaC0yek0zMCAyNmgydjJoLTJ6TTI0IDI2aDR2MmgtNHpNMTQgMjZoNHYyaC00eiI+PC9wYXRoPjwvc3ZnPg==) no-repeat center center;
		}
		
		.input-field button
		{
			flex: 0 0 auto;
			height: 28px;
			font-size: 20px;
			width: 40px;
		}
	</style>
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading">Dashboard</div>
					
					<div class="panel-body">
						<form>
							<div class="input-field">
								<label for="isbn_input">EAN:</label>
								<input id="isbn_input" class="isbn" type="text"/>
								<button type="button" class="icon-barcode button scan"></button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="/js/quagga.min.js"></script>
	<script>
		//		var Quagga = window.Quagga;
		var App = {
			_scanner: null,
			init: function () {
				this.attachListeners();
			},
			activateScanner: function () {
				var scanner = this.configureScanner('.overlay__content'),
						onDetected = function ( result ) {
							document.querySelector('input.isbn').value = result.codeResult.code;
							stop();
						}.bind(this),
						stop = function () {
							scanner.stop();  // should also clear all event-listeners?
							scanner.removeEventListener('detected', onDetected);
							this.hideOverlay();
							this.attachListeners();
						}.bind(this);
				
				this.showOverlay(stop);
				scanner.addEventListener('detected', onDetected).start();
			},
			attachListeners: function () {
				var self = this,
						button = document.querySelector('.input-field input + button.scan');
				
				button.addEventListener("click", function onClick( e ) {
					e.preventDefault();
					button.removeEventListener("click", onClick);
					self.activateScanner();
				});
			},
			showOverlay: function ( cancelCb ) {
				if (!this._overlay) {
					var content = document.createElement('div'),
							closeButton = document.createElement('div');
					
					closeButton.appendChild(document.createTextNode('X'));
					content.className = 'overlay__content';
					closeButton.className = 'overlay__close';
					this._overlay = document.createElement('div');
					this._overlay.className = 'overlay';
					this._overlay.appendChild(content);
					content.appendChild(closeButton);
					closeButton.addEventListener('click', function closeClick() {
						closeButton.removeEventListener('click', closeClick);
						cancelCb();
					});
					document.body.appendChild(this._overlay);
				}
				else {
					var closeButton = document.querySelector('.overlay__close');
					closeButton.addEventListener('click', function closeClick() {
						closeButton.removeEventListener('click', closeClick);
						cancelCb();
					});
				}
				this._overlay.style.display = "block";
			},
			hideOverlay: function () {
				if (this._overlay) {
					this._overlay.style.display = "none";
				}
			},
			configureScanner: function ( selector ) {
				if (!this._scanner) {
					this._scanner = Quagga
							.decoder({readers: [ 'ean_reader' ]})
							.locator({patchSize: 'medium'})
							.fromSource({
								target: selector,
								constraints: {
									width: 800,
									height: 600,
									facingMode: "environment"
								}
							});
				}
				return this._scanner;
			}
		};
		App.init();
	</script>
@endsection
