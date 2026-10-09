<?php

namespace FilaBoost\Tests\Unit;

use FilaBoost\Mcp\GeneratePlanTool;
use FilaBoost\Tests\TestCase;

class McpToolSchemaTest extends TestCase
{
    public function test_generate_plan_tool_schema_conforms_to_mcp(): void
    {
        $tool = new GeneratePlanTool;
        $schema = $tool->schema();

        $this->assertEquals('generate_filament_plan', $schema['name']);
        $this->assertArrayHasKey('parameters', $schema);
        $this->assertEquals('object', $schema['parameters']['type']);
        $this->assertContains('feature_description', $schema['parameters']['required']);
        $this->assertEquals('5.x', $schema['parameters']['properties']['filament_version']['default']);
    }

    public function test_generate_plan_tool_executes_successfully(): void
    {
        $tool = new GeneratePlanTool;
        $response = $tool->execute([
            'feature_description' => 'Product catalog with category relationships and pricing',
        ]);

        $this->assertEquals('success', $response['status']);
        $this->assertNotEmpty($response['markdown_content']);
        $this->assertStringContainsString('Schemas\\', $response['markdown_content']);
    }
}
