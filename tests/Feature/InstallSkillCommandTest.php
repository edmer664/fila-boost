<?php

namespace FilaBoost\FilamentBlueprint\Tests\Feature;

use FilaBoost\FilamentBlueprint\Tests\TestCase;
use Illuminate\Support\Facades\File;

class InstallSkillCommandTest extends TestCase
{
    public function test_it_installs_skills_into_agents_skills_directory(): void
    {
        $targetDir = base_path('.agents/skills');
        if (File::exists($targetDir)) {
            File::deleteDirectory($targetDir);
        }

        $this->artisan('fila-boost:install')
            ->expectsOutputToContain('Installing Filament Blueprint agent skills')
            ->assertExitCode(0);

        $this->assertTrue(File::exists(base_path('.agents/skills/planning-filament/SKILL.md')));
        $this->assertTrue(File::exists(base_path('.agents/skills/reviewing-filament-plans/SKILL.md')));
    }
}
