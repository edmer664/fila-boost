<?php

namespace FilaBoost\Mcp;

use FilaBoost\Support\PlanRenderer;
use Illuminate\Support\Facades\File;

class GeneratePlanTool
{
    protected PlanRenderer $renderer;

    public function __construct(?PlanRenderer $renderer = null)
    {
        $this->renderer = $renderer ?? new PlanRenderer;
    }

    /**
     * Get the MCP Tool JSON Schema definition.
     */
    public function schema(): array
    {
        return [
            'name' => 'generate_filament_plan',
            'description' => 'Generates a structured, self-contained Filament v5.x architecture and implementation plan (Markdown) for an administrative feature, including models, migrations, Filament v5 modular resources (CustomerResource, Schemas/CustomerForm, Tables/CustomersTable), policies, and Pest tests.',
            'parameters' => [
                'type' => 'object',
                'properties' => [
                    'feature_description' => [
                        'type' => 'string',
                        'description' => 'Detailed natural language description of the Filament administrative feature to plan (e.g., "Customer invoicing with line items, tax calculation, and payment status tracking").',
                    ],
                    'target_file' => [
                        'type' => 'string',
                        'description' => 'Relative path where the generated plan Markdown should be saved (default: "plans/{slug}.md").',
                    ],
                    'filament_version' => [
                        'type' => 'string',
                        'description' => 'Target Filament version. Strict Filament v5.x enforced.',
                        'enum' => ['5.x'],
                        'default' => '5.x',
                    ],
                    'multi_tenancy' => [
                        'type' => 'boolean',
                        'description' => 'Whether the feature requires multi-tenancy isolation and tenant ownership scoping.',
                        'default' => false,
                    ],
                ],
                'required' => ['feature_description'],
            ],
        ];
    }

    /**
     * Execute the tool with given arguments.
     */
    public function execute(array $arguments): array
    {
        $result = $this->renderer->render($arguments);

        // Optionally write plan to target file if in application environment
        $targetFile = $result['plan_file'] ?? $result['blueprint_file'];
        if (function_exists('base_path') && ! empty($targetFile)) {
            $dest = base_path($targetFile);
            $dir = dirname($dest);
            if (! File::isDirectory($dir) && @mkdir($dir, 0755, true)) {
                @file_put_contents($dest, $result['markdown_content']);
            }
        }

        return $result;
    }
}
