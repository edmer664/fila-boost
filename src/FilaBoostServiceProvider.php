<?php

namespace FilaBoost\FilamentBlueprint;

use FilaBoost\FilamentBlueprint\Commands\InstallSkillCommand;
use FilaBoost\FilamentBlueprint\Mcp\GenerateBlueprintTool;
use FilaBoost\FilamentBlueprint\Mcp\ReviewImplementationTool;
use Illuminate\Support\ServiceProvider;

class FilaBoostServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(GenerateBlueprintTool::class, function () {
            return new GenerateBlueprintTool;
        });

        $this->app->singleton(ReviewImplementationTool::class, function () {
            return new ReviewImplementationTool;
        });
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallSkillCommand::class,
            ]);

            $this->publishes([
                __DIR__.'/../resources/skills' => base_path('.agents/skills'),
            ], 'fila-boost-skills');
        }
    }
}
