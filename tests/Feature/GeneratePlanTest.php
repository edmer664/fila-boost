<?php

namespace FilaBoost\Tests\Feature;

use FilaBoost\Mcp\GeneratePlanTool;
use FilaBoost\Tests\TestCase;
use Illuminate\Support\Facades\File;

class GeneratePlanTest extends TestCase
{
    public function test_it_generates_comprehensive_plan_with_unresolved_decisions(): void
    {
        $tool = new GeneratePlanTool;
        $response = $tool->execute([
            'feature_description' => 'Customer orders with status workflow and invoice payment handling',
        ]);

        $this->assertEquals('success', $response['status']);
        $this->assertNotEmpty($response['unresolved_decisions']);
        $this->assertStringContainsString('Unresolved Decisions', $response['markdown_content']);
        $this->assertStringContainsString('Resource', $response['markdown_content']);
        $this->assertStringContainsString('Customer', $response['markdown_content']);
    }

    public function test_it_writes_generated_plan_when_target_directory_is_newly_created(): void
    {
        $targetFile = 'plans/generated/newly-created/feature-plan.md';
        $targetPath = base_path($targetFile);

        File::delete($targetPath);
        File::deleteDirectory(dirname($targetPath));

        $tool = new GeneratePlanTool;
        $response = $tool->execute([
            'feature_description' => 'Customer onboarding workflow',
            'target_file' => $targetFile,
        ]);

        $this->assertFileExists($targetPath);
        $this->assertSame($response['markdown_content'], File::get($targetPath));

        File::delete($targetPath);
        File::deleteDirectory(dirname($targetPath));
    }

    public function test_it_writes_generated_plan_when_target_directory_already_exists(): void
    {
        $targetFile = 'plans/generated/existing-directory/feature-plan.md';
        $targetPath = base_path($targetFile);
        $targetDirectory = dirname($targetPath);

        File::ensureDirectoryExists($targetDirectory);
        File::delete($targetPath);

        $tool = new GeneratePlanTool;
        $response = $tool->execute([
            'feature_description' => 'Customer retention dashboard',
            'target_file' => $targetFile,
        ]);

        $this->assertFileExists($targetPath);
        $this->assertSame($response['markdown_content'], File::get($targetPath));

        File::delete($targetPath);
        File::deleteDirectory($targetDirectory);
    }
}
