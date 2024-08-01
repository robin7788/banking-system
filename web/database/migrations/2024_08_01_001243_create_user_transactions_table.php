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
        Schema::create('user_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_account_id')
                ->constrained()
                ->references('id')
                ->on('user_accounts')
                ->noActionOnUpdate();

            $table->foreignId('user_id')
                ->constrained()
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->boolean('is_credit')->default(0)->comment("This separates whether the amount is credited or debited");
            $table->double('amount', 10, 2)->default(0);
            $table->double('balance', 10, 2)->default(0);
            $table->text('note')->nullable();
            $table->string('from_currency')->nullable();
            $table->string('to_currency')->nullable();
            $table->double('charge', 10, 2)->default(0);

            $table->foreignId('from_to')
                ->nullable()
                ->references('id')
                ->on('users')
                ->noActionOnUpdate();

            $table->text('ref')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_transactions');
    }
};
