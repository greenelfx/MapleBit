<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ConstructAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('accounts')) {
            Schema::create('accounts', function ($table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
            });
        }

        Schema::table('accounts', function (Blueprint $table) {
            if (! Schema::hasColumn('accounts', 'id')) {
                $table->id();
            }
            if (! Schema::hasColumn('accounts', 'name')) {
                $table->string('name')->nullable();
            }
            if (! Schema::hasColumn('accounts', 'email')) {
                $table->string('email')->unique()->nullable();
            }
            if (! Schema::hasColumn('accounts', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable();
            }
            if (! Schema::hasColumn('accounts', 'password')) {
                $table->string('password')->nullable();
            }
            if (! Schema::hasColumn('accounts', 'site_password')) {
                $table->string('site_password')->nullable();
            }
            if (! Schema::hasColumn('accounts', 'loggedin')) {
                $table->tinyInteger('loggedin')->default(0);
            }
            if (! Schema::hasColumn('accounts', 'remember_token')) {
                $table->rememberToken();
            }
            if (! Schema::hasColumn('accounts', 'created_at') && ! Schema::hasColumn('accounts', 'updated_at')) {
                $table->timestamps();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->dropColumn('username');
            $table->dropColumn('email');
            $table->dropColumn('email_verified_at');
            $table->dropColumn('password');
            $table->dropColumn('remember_token');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}
