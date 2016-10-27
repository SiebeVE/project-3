<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultValueToBook extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table( 'books', function ( Blueprint $table ) {
			$table->string( 'image' )->nullable()->change();
			$table->text( 'description' )->nullable()->change();
			$table->text( 'author' )->nullable()->change();
			$table->integer( 'status' )->default( 0 )->change();
			$table->integer( "pageCount" )->nullable()->change();
			$table->string( "language" )->nullable()->change();
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table( 'books', function ( Blueprint $table ) {
			$table->string( 'image' )->change();
			$table->text( 'description' )->change();
			$table->text( 'author' )->change();
			$table->integer( 'status' )->change();
			$table->integer( "pageCount" )->change();
			$table->string( "language" )->change();
			$table->string( "type" )->change();
		} );
	}
}
