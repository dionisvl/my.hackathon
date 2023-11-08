<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('materials', static function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->text('files')->nullable();
            $table->timestamps();
        });

        // Todo: перепродумать функционал тестирования
        Schema::create('tests', static function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('test_questions', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_id');
            $table->text('question');
            $table->timestamps();
        });

        Schema::create('test_question_answers', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_question_id');
            $table->text('answer');
            $table->tinyInteger('is_right');
            $table->timestamps();
        });

        Schema::create('user_materials', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('material_id');
            $table->timestamp('viewed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('user_tests', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('test_id');
            $table->timestamp('completed_at')->nullable()->default(null);
            $table->timestamps();
        });

        // @see: https://laravel.com/docs/10.x/notifications
        Schema::create('notifications', static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('courses', static function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description')->default('');
            $table->timestamp('start_at')->default(now()->addWeek()->toDateString());
            $table->timestamp('deadline_at')->nullable();
            $table->timestamp('end_at')->default(now()->addYear()->toDateString());
            $table->timestamps();
        });

        Schema::create('course_materials', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('material_id');
            $table->timestamps();
        });

        Schema::create('course_tests', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('test_id');
            $table->timestamps();
        });

        Schema::create('course_users', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });

        Schema::create('support_tickets', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('subject');
            $table->text('message');
            $table->enum('status', ['open', 'in_progress', 'closed'])->default('open');
            $table->timestamps();
        });

        Schema::create('support_responses', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('responder_id');
            $table->text('response');
            $table->timestamps();
        });

        Schema::create('reports', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('title');
            $table->string('report_type')->nullable();
            $table->text('report_data'); // This will hold serialized or JSON data depending on the type of reports
            $table->timestamps();
        });

        Schema::create('onboarding_plans', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id')->nullable();// можно указать на какую профессию рассчитан данный план
            $table->string('title');
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('plan_materials', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('material_id');
            $table->timestamps();
        });

        Schema::create('plan_tests', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('test_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
        Schema::dropIfExists('tests');
        Schema::dropIfExists('test_questions');
        Schema::dropIfExists('test_question_answers');
        Schema::dropIfExists('user_materials');
        Schema::dropIfExists('user_tests');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('course_materials');
        Schema::dropIfExists('course_tests');
        Schema::dropIfExists('course_users');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('support_responses');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('onboarding_plans');
        Schema::dropIfExists('plan_materials');
        Schema::dropIfExists('plan_tests');
        Schema::dropIfExists('settings');
    }
};
