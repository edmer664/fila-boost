<?php

namespace FilaBoost\FilamentBlueprint\Tests\Unit;

use FilaBoost\FilamentBlueprint\Support\DocsRepository;
use FilaBoost\FilamentBlueprint\Tests\TestCase;

class DocsRepositoryTest extends TestCase
{
    public function test_it_indexes_and_searches_bundled_docs(): void
    {
        $repo = new DocsRepository(__DIR__ . '/../../docs');
        $results = $repo->search('infolist');

        $this->assertNotEmpty($results);
        $first = $results[0];
        $this->assertArrayHasKey('slug', $first);
        $this->assertArrayHasKey('title', $first);
        $this->assertArrayHasKey('snippet', $first);
        $this->assertStringContainsString('infolist', strtolower($first['title'] . ' ' . $first['slug']));
    }

    public function test_it_retrieves_full_document_by_slug(): void
    {
        $repo = new DocsRepository(__DIR__ . '/../../docs');
        $doc = $repo->get('12-components/02-infolist');

        $this->assertNotNull($doc);
        $this->assertEquals('12-components/02-infolist', $doc['slug']);
        $this->assertNotEmpty($doc['content']);
        $this->assertStringContainsString('# Infolist Component', $doc['content']);
    }

    public function test_it_retrieves_document_fuzzy_by_basename(): void
    {
        $repo = new DocsRepository(__DIR__ . '/../../docs');
        $doc = $repo->get('03-colors');

        $this->assertNotNull($doc);
        $this->assertStringContainsString('08-styling/03-colors', $doc['slug']);
    }
}
