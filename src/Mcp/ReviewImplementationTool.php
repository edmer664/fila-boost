<?php

namespace FilaBoost\Mcp;

use FilaBoost\Support\ImplementationAuditor;

class ReviewImplementationTool
{
    protected ImplementationAuditor $auditor;

    public function __construct(?ImplementationAuditor $auditor = null)
    {
        $this->auditor = $auditor ?? new ImplementationAuditor;
    }

    /**
     * Get the MCP Tool JSON Schema definition.
     */
    public function schema(): array
    {
        return [
            'name' => 'review_filament_implementation',
            'description' => 'Reviews the current application repository checkout against a previously generated Filament architecture plan, checking adherence to Filament v5.x modular structure (Schemas/ and Tables/ classes), validation rules, authorization policies, and test suites.',
            'parameters' => [
                'type' => 'object',
                'properties' => [
                    'plan_file' => [
                        'type' => 'string',
                        'description' => 'Relative path to the architecture plan markdown file (e.g., "plans/invoicing.md").',
                    ],
                    'blueprint_file' => [
                        'type' => 'string',
                        'description' => 'Legacy alias for plan_file (e.g., "blueprints/invoicing.md").',
                    ],
                    'run_pest_tests' => [
                        'type' => 'boolean',
                        'description' => 'Whether to execute Pest tests to verify implementation.',
                        'default' => true,
                    ],
                    'run_pint_check' => [
                        'type' => 'boolean',
                        'description' => 'Whether to verify code style using Laravel Pint.',
                        'default' => true,
                    ],
                ],
                'required' => ['plan_file'],
            ],
        ];
    }

    /**
     * Execute the review tool with given arguments.
     */
    public function execute(array $arguments): array
    {
        $planFile = $arguments['plan_file'] ?? $arguments['blueprint_file'] ?? '';

        return $this->auditor->audit($planFile, null, $arguments);
    }
}
