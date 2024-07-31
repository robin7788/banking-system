<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'first_name');
            $table->bigInteger('account_number')->unique()->nullable()->after('id');
            $table->string('last_name')->nullable()->after('first_name');
            $table->date('dob')->nullable()->after('last_name');
            $table->string('address_1')->nullable()->after('dob');
            $table->string('address_2')->nullable()->after('address_1');
            $table->string('town')->nullable()->after('address_2');
            $table->string('country')->nullable()->after('town');
            $table->string('post_code')->nullable()->after('country');
            $table->string('two_factor_code')->nullable();
            $table->dateTime('two_factor_expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'account_number', 'last_name', 'dob', 'address_1', 'address_2', 'town', 
                'country', 'post_code', 'two_factor_code', 'two_factor_expires_at'
            ]);
            $table->renameColumn('first_name', 'name');
        });
    }
};
