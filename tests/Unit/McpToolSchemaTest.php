<?php

namespace FilaBoost\FilamentBlueprint\Tests\Unit;

use FilaBoost\FilamentBlueprint\Mcp\GenerateBlueprintTool;
use FilaBoost\FilamentBlueprint\Tests\TestCase;

class McpToolSchemaTest extends TestCase
{
    public function test_generate_blueprint_tool_schema_conforms_to_mcp(): void
    {
        $tool = new GenerateBlueprintTool;
        $schema = $tool->schema();

        $this->assertEquals('generate_filament_blueprint', $schema['name']);
        $this->assertArrayHasKey('parameters', $schema);
        $this->assertEquals('object', $schema['parameters']['type']);
        $this->assertContains('feature_description', $schema['parameters']['required']);
        $this->assertEquals('5.x', $schema['parameters']['properties']['filament_version']['default']);
    }

    public function test_generate_blueprint_tool_executes_successfully(): void
    {
        $tool = new GenerateBlueprintTool;
        $response = $tool->execute([
            'feature_description' => 'Product catalog with category relationships and pricing',
        ]);

        $this->assertEquals('success', $response['status']);
        $this->assertNotEmpty($response['markdown_content']);
        $this->assertStringContainsString('Schemas\\', $response['markdown_content']);
    }
}
