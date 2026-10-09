<?php

namespace FilaBoost\Tests\Unit;

use FilaBoost\Mcp\ReviewImplementationTool;
use FilaBoost\Tests\TestCase;

class ReviewToolSchemaTest extends TestCase
{
    public function test_review_implementation_tool_schema_conforms_to_mcp(): void
    {
        $tool = new ReviewImplementationTool;
        $schema = $tool->schema();

        $this->assertEquals('review_filament_implementation', $schema['name']);
        $this->assertArrayHasKey('parameters', $schema);
        $this->assertEquals('object', $schema['parameters']['type']);
        $this->assertContains('plan_file', $schema['parameters']['required']);
        $this->assertTrue($schema['parameters']['properties']['run_pest_tests']['default']);
        $this->assertTrue($schema['parameters']['properties']['run_pint_check']['default']);
    }
}
