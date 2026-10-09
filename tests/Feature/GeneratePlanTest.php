<?php

namespace FilaBoost\Tests\Feature;

use FilaBoost\Mcp\GeneratePlanTool;
use FilaBoost\Tests\TestCase;

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
}
