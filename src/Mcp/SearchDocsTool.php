<?php

namespace FilaBoost\FilamentBlueprint\Mcp;

use FilaBoost\FilamentBlueprint\Support\DocsRepository;

class SearchDocsTool
{
    protected DocsRepository $docs;

    public function __construct(?DocsRepository $docs = null)
    {
        $this->docs = $docs ?? new DocsRepository();
    }

    /**
     * Get the MCP Tool JSON Schema definition.
     */
    public function schema(): array
    {
        return [
            'name' => 'search_filament_docs',
            'description' => 'Searches the bundled official Filament v5 documentation repository for guides, components, styling, widgets, infolists, tenancy, and testing patterns. Returns matched topics with summaries and relative paths.',
            'parameters' => [
                'type' => 'object',
                'properties' => [
                    'query' => [
                        'type' => 'string',
                        'description' => 'Search keyword or phrase (e.g., "infolist", "stats widget", "multi-tenancy", "custom theme colors", "render hooks").',
                    ],
                    'category' => [
                        'type' => 'string',
                        'description' => 'Optional category filter (e.g., "components", "styling", "resources", "advanced", "testing", "plugins").',
                    ],
                    'limit' => [
                        'type' => 'integer',
                        'description' => 'Maximum number of results to return (default: 5, max: 20).',
                        'default' => 5,
                    ],
                ],
                'required' => ['query'],
            ],
        ];
    }

    /**
     * Execute the tool with given arguments.
     */
    public function execute(array $arguments): array
    {
        $query = $arguments['query'] ?? '';
        $category = $arguments['category'] ?? null;
        $limit = (int) ($arguments['limit'] ?? 5);

        $results = $this->docs->search($query, $category, $limit);

        return [
            'status' => empty($results) ? 'not_found' : 'success',
            'query' => $query,
            'total_matches' => count($results),
            'results' => array_map(function ($item) {
                return [
                    'slug' => $item['slug'],
                    'title' => $item['title'],
                    'category' => $item['category'],
                    'relative_path' => $item['relative_path'],
                    'snippet' => $item['snippet'],
                ];
            }, $results),
        ];
    }
}
