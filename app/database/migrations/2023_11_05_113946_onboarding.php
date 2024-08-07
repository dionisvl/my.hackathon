<?php

use App\Models\Task;
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

        Schema::create('tests', static function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description')->nullable();
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
            $table->tinyInteger('is_right')->default(0);
            $table->timestamps();
        });
        Schema::create('user_tests', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('test_id');
            $table->decimal('result')->nullable();# UserTest::PASS_THRESHOLD = 65
            $table->timestamp('completed_at')->nullable()->default(null);
            $table->timestamps();
            $table->comment('Результаты пользователей по прохождению тестов');
        });

        Schema::create('user_materials', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('material_id');
            $table->timestamp('viewed_at')->nullable();
            $table->timestamps();
            $table->comment('Результаты пользователей по изучению материалов');
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
        Schema::create('course_roles', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('role_id');
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
            $table->comment('');
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

        Schema::create('tasks', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id')->index();
            $table->unsignedBigInteger('assignee_id')->nullable()->index();
            $table->string('status')->default(Task::STATUS_NOT_STARTED)->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('result')->nullable();
            $table->timestamp('deadline')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('email_logs', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('email');
            $table->string('type');
            $table->timestamps();
        });

        Schema::create('settings', static function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('value')->nullable();
            $table->string('description')->default('');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('messages', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->text('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        # tests
        Schema::dropIfExists('tests');
        Schema::dropIfExists('test_questions');
        Schema::dropIfExists('test_question_answers');
        Schema::dropIfExists('user_tests');

        # courses
        Schema::dropIfExists('courses');
        Schema::dropIfExists('course_roles');
        Schema::dropIfExists('course_materials');
        Schema::dropIfExists('course_tests');
        Schema::dropIfExists('course_users');

        Schema::dropIfExists('materials');
        Schema::dropIfExists('user_materials');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('notifications');

        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('support_responses');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('onboarding_plans');
        Schema::dropIfExists('plan_materials');
        Schema::dropIfExists('plan_tests');

        Schema::dropIfExists('tasks');

        Schema::dropIfExists('email_logs');

        Schema::dropIfExists('settings');

        Schema::dropIfExists('messages');
    }
};
