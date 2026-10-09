<?php

namespace FilaBoost\Tests\Feature;

use FilaBoost\Tests\TestCase;
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
            ->expectsOutputToContain('Installing Fila-boost agent skills')
            ->assertExitCode(0);

        $this->assertTrue(File::exists(base_path('.agents/skills/planning-filament/SKILL.md')));
        $this->assertTrue(File::exists(base_path('.agents/skills/reviewing-filament-plans/SKILL.md')));
    }
}
