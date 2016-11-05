<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDatabaseForeignThatBooks extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up () {
		Schema::table('books', function (Blueprint $table) {
			$table->dropForeign(['owner_id']);
			$table->dropColumn(["condition", "type", "status", "owner_id"]);
		});

		Schema::create('book_user', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('book_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->string("type");
			$table->text('condition');
			$table->integer('status')->default(0);

			$table->foreign('book_id')
			      ->references('id')
			      ->on('books');
			$table->foreign('user_id')
			      ->references('id')
			      ->on('users');

			$table->timestamps();
		});

		Schema::table('book_transactions', function (Blueprint $table) {
			$table->dropForeign(['book_id']);
			$table->foreign('book_id')->references('id')->on('book_user');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down () {
		Schema::table('book_transactions', function (Blueprint $table) {
			$table->dropForeign(['book_id']);
			$table->foreign('book_id')->references('id')->on('books');
		});

		Schema::drop('book_user');

		Schema::table('books', function (Blueprint $table) {
			$table->string("type")->after("language");
			$table->text('condition')->after('author');
			$table->integer('status')->default(0)->after("type");
			$table->integer('owner_id')->nullable()->unsigned()->after("status");
			$table->foreign('owner_id')->references('id')->on('users');
		});
	}
}
