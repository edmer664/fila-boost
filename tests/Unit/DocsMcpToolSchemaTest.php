<?php

namespace FilaBoost\FilamentBlueprint\Tests\Unit;

use FilaBoost\FilamentBlueprint\Mcp\GetDocTool;
use FilaBoost\FilamentBlueprint\Mcp\SearchDocsTool;
use FilaBoost\FilamentBlueprint\Tests\TestCase;

class DocsMcpToolSchemaTest extends TestCase
{
    public function test_search_docs_tool_schema_conforms_to_mcp(): void
    {
        $tool = new SearchDocsTool();
        $schema = $tool->schema();

        $this->assertEquals('search_filament_docs', $schema['name']);
        $this->assertArrayHasKey('parameters', $schema);
        $this->assertEquals('object', $schema['parameters']['type']);
        $this->assertContains('query', $schema['parameters']['required']);
        $this->assertEquals(5, $schema['parameters']['properties']['limit']['default']);
    }

    public function test_get_doc_tool_schema_conforms_to_mcp(): void
    {
        $tool = new GetDocTool();
        $schema = $tool->schema();

        $this->assertEquals('get_filament_doc', $schema['name']);
        $this->assertArrayHasKey('parameters', $schema);
        $this->assertEquals('object', $schema['parameters']['type']);
        $this->assertContains('topic_id', $schema['parameters']['required']);
    }
}
