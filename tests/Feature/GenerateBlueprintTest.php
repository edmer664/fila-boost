<?php

namespace FilaBoost\FilamentBlueprint\Tests\Feature;

use FilaBoost\FilamentBlueprint\Mcp\GenerateBlueprintTool;
use FilaBoost\FilamentBlueprint\Tests\TestCase;

class GenerateBlueprintTest extends TestCase
{
    public function test_it_generates_comprehensive_blueprint_with_unresolved_decisions(): void
    {
        $tool = new GenerateBlueprintTool;
        $response = $tool->execute([
            'feature_description' => 'Customer orders with status workflow and invoice payment handling',
        ]);

        $this->assertEquals('success', $response['status']);
        $this->assertNotEmpty($response['unresolved_decisions']);
        $this->assertStringContainsString('Unresolved Decisions', $response['markdown_content']);
        $this->assertStringContainsString('Resource', $response['markdown_content']);
        $this->assertStringContainsString('Customer', $response['markdown_content']);
    }
}
