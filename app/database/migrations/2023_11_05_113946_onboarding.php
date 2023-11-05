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
        Schema::create('roles', static function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('users', static function (Blueprint $table) {
            // Add the role_id column and foreign key constraint
            $table->unsignedBigInteger('role_id')->after('id'); // Assuming you want to place it after the 'id' column
            $table->foreign('role_id')->references('id')->on('roles');
        });

        Schema::create('materials',static function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('tests',static function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('questions',static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_id');
            $table->text('question');
            $table->timestamps();

            $table->foreign('test_id')->references('id')->on('tests');
        });

        Schema::create('user_materials',static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('material_id');
            $table->timestamp('viewed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('material_id')->references('id')->on('materials');
        });

        Schema::create('user_tests',static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('test_id');
            $table->timestamp('completed_at')->nullable()->default(null);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('test_id')->references('id')->on('tests');
        });

        Schema::create('notifications',static function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->timestamps();
        });

        Schema::create('user_notifications',static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('notification_id');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('notification_id')->references('id')->on('notifications');
        });

        Schema::create('courses',static function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('course_materials',static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('material_id');
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('material_id')->references('id')->on('materials');
        });

        Schema::create('course_tests',static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('test_id');
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('test_id')->references('id')->on('tests');
        });

        Schema::create('support_tickets',static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('subject');
            $table->text('message');
            $table->enum('status', ['open', 'in_progress', 'closed'])->default('open');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('support_responses',static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('responder_id');
            $table->text('response');
            $table->timestamps();

            $table->foreign('ticket_id')->references('id')->on('support_tickets');
            $table->foreign('responder_id')->references('id')->on('users');
        });

        Schema::create('reports',static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('report_type');
            $table->text('report_data'); // This will hold serialized or JSON data depending on the type of reports
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('onboarding_plans',static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->string('title');
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles');
        });

        Schema::create('plan_materials',static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('material_id');
            $table->timestamps();

            $table->foreign('plan_id')->references('id')->on('onboarding_plans');
            $table->foreign('material_id')->references('id')->on('materials');
        });

        Schema::create('plan_tests',static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('test_id');
            $table->timestamps();

            $table->foreign('plan_id')->references('id')->on('onboarding_plans');
            $table->foreign('test_id')->references('id')->on('tests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
