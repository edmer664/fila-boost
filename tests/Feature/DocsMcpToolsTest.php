<?php

namespace FilaBoost\FilamentBlueprint\Tests\Feature;

use FilaBoost\FilamentBlueprint\Mcp\GetDocTool;
use FilaBoost\FilamentBlueprint\Mcp\SearchDocsTool;
use FilaBoost\FilamentBlueprint\Support\DocsRepository;
use FilaBoost\FilamentBlueprint\Tests\TestCase;

class DocsMcpToolsTest extends TestCase
{
    protected DocsRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new DocsRepository(__DIR__ . '/../../docs');
    }

    public function test_search_docs_tool_executes_and_returns_matches(): void
    {
        $tool = new SearchDocsTool($this->repo);
        $response = $tool->execute([
            'query' => 'infolist',
            'limit' => 3,
        ]);

        $this->assertEquals('success', $response['status']);
        $this->assertNotEmpty($response['results']);
        $this->assertLessThanOrEqual(3, count($response['results']));
        $this->assertStringContainsString('infolist', strtolower($response['results'][0]['title'] . ' ' . $response['results'][0]['slug']));
    }

    public function test_get_doc_tool_executes_and_returns_full_markdown(): void
    {
        $tool = new GetDocTool($this->repo);
        $response = $tool->execute([
            'topic_id' => '12-components/02-infolist',
        ]);

        $this->assertEquals('success', $response['status']);
        $this->assertNotEmpty($response['content']);
        $this->assertStringContainsString('# Infolist Component', $response['content']);
    }

    public function test_get_doc_tool_handles_missing_topics_gracefully(): void
    {
        $tool = new GetDocTool($this->repo);
        $response = $tool->execute([
            'topic_id' => 'non-existent-guide',
        ]);

        $this->assertEquals('error', $response['status']);
        $this->assertStringContainsString('not found', $response['message']);
    }
}
