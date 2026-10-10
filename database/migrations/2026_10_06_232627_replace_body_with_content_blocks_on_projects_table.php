<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Case studies move from a single markdown body to ordered content blocks
 * (markdown, image, video, custom HTML). Existing bodies become a markdown block.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->json('content')->nullable()->after('summary');
        });

        DB::table('projects')->whereNotNull('body')->orderBy('id')->each(function (object $project): void {
            DB::table('projects')->where('id', $project->id)->update([
                'content' => json_encode([['type' => 'markdown', 'data' => ['body' => $project->body]]]),
            ]);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('body');
        });
    }

    /**
     * Lossy: only markdown blocks can be folded back into a body; media and HTML blocks are discarded.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->longText('body')->nullable()->after('summary');
        });

        DB::table('projects')->whereNotNull('content')->orderBy('id')->each(function (object $project): void {
            $markdown = collect(json_decode($project->content, true) ?? [])
                ->where('type', 'markdown')
                ->pluck('data.body')
                ->implode("\n\n");

            DB::table('projects')->where('id', $project->id)->update(['body' => $markdown ?: null]);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('content');
        });
    }
};
