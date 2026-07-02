<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->date('date');
            $table->string('time', 5); // HH:mm
            $table->string('location');
            $table->string('host')->nullable();
            $table->string('topic')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa']);
            $table->timestamps();
            $table->unique(['meeting_id', 'user_id']);
        });

        Schema::create('finance_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->enum('type', ['masuk', 'keluar']);
            $table->string('category');
            $table->unsignedBigInteger('amount'); // rupiah, tanpa desimal
            $table->string('note')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // pencatat
            $table->timestamps();
        });

        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('body');
            $table->boolean('pinned')->default(false);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // pembuat
            $table->timestamps();
        });

        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('title');
            $table->string('speaker')->nullable();
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('tilawah_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->unsignedSmallInteger('pages');
            $table->string('surah')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tilawah_entries');
        Schema::dropIfExists('notes');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('finance_entries');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('meetings');
    }
};
