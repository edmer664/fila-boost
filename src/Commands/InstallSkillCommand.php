<?php

namespace FilaBoost\FilamentBlueprint\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallSkillCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fila-boost:install {--force : Overwrite existing installed skills}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install and link Filament Blueprint agent skills into Laravel Boost and agent directories';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Installing Filament Blueprint agent skills for Laravel Boost...');

        $sourceDir = __DIR__.'/../../resources/skills';
        $destDir = base_path('.agents/skills');

        if (! File::exists($sourceDir)) {
            $this->error("Source skills directory not found at: {$sourceDir}");

            return 1;
        }

        if (! File::exists($destDir)) {
            File::makeDirectory($destDir, 0755, true);
        }

        $skills = ['planning-filament', 'reviewing-filament-plans'];

        foreach ($skills as $skill) {
            $skillSource = "{$sourceDir}/{$skill}";
            $skillDest = "{$destDir}/{$skill}";

            if (File::exists($skillDest) && ! $this->option('force')) {
                $this->warn("Skill '{$skill}' already exists in .agents/skills. Use --force to overwrite.");

                continue;
            }

            File::copyDirectory($skillSource, $skillDest);
            $this->line("<info>Registered skill:</info> {$skill} -> .agents/skills/{$skill}");
        }

        // Check for Boost or MCP compatibility
        if (! class_exists('Laravel\\Boost\\BoostServiceProvider') && ! File::exists(base_path('composer.json'))) {
            $this->comment('Tip: Install laravel/boost to enable automated agent context enhancements.');
        }

        $this->info('Filament Blueprint agent skills successfully installed!');

        return 0;
    }
}
