<?php

namespace FilaBoost\FilamentBlueprint\Mcp;

use FilaBoost\FilamentBlueprint\Support\DocsRepository;

class GetDocTool
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
            'name' => 'get_filament_doc',
            'description' => 'Retrieves the complete markdown content of a bundled official Filament documentation guide by topic slug or file path.',
            'parameters' => [
                'type' => 'object',
                'properties' => [
                    'topic_id' => [
                        'type' => 'string',
                        'description' => 'The documentation topic slug or relative path (e.g., "12-components/02-infolist", "08-styling/03-colors", "infolist", or "09-advanced/05-modular-architecture").',
                    ],
                ],
                'required' => ['topic_id'],
            ],
        ];
    }

    /**
     * Execute the tool with given arguments.
     */
    public function execute(array $arguments): array
    {
        $topicId = $arguments['topic_id'] ?? '';
        $doc = $this->docs->get($topicId);

        if (!$doc) {
            return [
                'status' => 'error',
                'topic_id' => $topicId,
                'message' => "Documentation topic '{$topicId}' not found.",
            ];
        }

        return [
            'status' => 'success',
            'slug' => $doc['slug'],
            'title' => $doc['title'],
            'category' => $doc['category'],
            'relative_path' => $doc['relative_path'],
            'content' => $doc['content'],
        ];
    }
}
