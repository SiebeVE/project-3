<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeFieldNullableOnBooks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
	        $table->text('image')->nullable()->change();
	        $table->text('description')->nullable()->change();
	        $table->text('author')->nullable()->change();
	        $table->integer( "pageCount" )->after( "condition" )->nullable()->change();
	        $table->string( "language" )->after( "pageCount" )->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
	        $table->string('image')->change();
	        $table->text('description')->change();
	        $table->text('author')->change();
	        $table->integer( "pageCount" )->after( "condition" )->change();
	        $table->string( "language" )->after( "pageCount" )->change();
        });
    }
}
