<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToBook extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table( 'books', function ( Blueprint $table ) {
			$table->integer( "pageCount" )->after( "condition" );
			$table->string( "language" )->after( "pageCount" );
			$table->string( "type" )->after( "language" );
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table( 'books', function ( Blueprint $table ) {
			$table->dropColumn( [
				"pageCount",
				"language",
				"type"
			] );
		} );
	}
}
