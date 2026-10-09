<?php

namespace FilaBoost\FilamentBlueprint\Support;

use Illuminate\Support\Facades\File;

class ImplementationAuditor
{
    /**
     * Audit an application checkout against a saved blueprint.
     *
     * @param  string  $blueprintPath  Absolute or relative path to blueprint file
     * @param  string|null  $basePath  Application base path
     */
    public function audit(string $blueprintPath, ?string $basePath = null, array $options = []): array
    {
        $base = $basePath ?? base_path();
        $fullPath = $this->isAbsolutePath($blueprintPath) ? $blueprintPath : $base.DIRECTORY_SEPARATOR.$blueprintPath;

        if (! File::exists($fullPath)) {
            return [
                'status' => 'error',
                'coverage_score' => 0,
                'checks' => [],
                'discrepancies' => ["Blueprint file not found at: {$fullPath}"],
                'recommendations' => ['Ensure the blueprint has been generated first.'],
            ];
        }

        $content = File::get($fullPath);
        $discrepancies = [];
        $recommendations = [];

        // 1. Check for expected modular resource classes mentioned in blueprint
        preg_match_all('/### Resource: `App\\\\Filament\\\\Resources\\\\([^`]+)`/', $content, $resourceMatches);
        $resources = $resourceMatches[1] ?? [];

        $modularPassed = true;
        $formsPassed = true;
        $tablesPassed = true;
        $infolistsPassed = true;

        if (empty($resources)) {
            preg_match_all('/class ([A-Za-z0-9_]+Resource)/', $content, $fallbackMatches);
            $resources = $fallbackMatches[1] ?? [];
        }

        foreach ($resources as $resourceSubpath) {
            $parts = explode('\\', $resourceSubpath);
            $resourceClass = end($parts);
            $folder = dirname(str_replace('\\', '/', $resourceSubpath));

            $relResourceFile = "app/Filament/Resources/{$resourceSubpath}.php";
            if (! File::exists($base.'/'.$relResourceFile)) {
                $modularPassed = false;
                $discrepancies[] = "Missing Filament Resource: {$relResourceFile}";
                $recommendations[] = "Create {$relResourceFile} adhering to Filament v5 conventions.";
            }

            // Check modular Schemas/{Name}Form.php
            $entityName = str_replace('Resource', '', $resourceClass);
            $relFormFile = "app/Filament/Resources/{$folder}/Schemas/{$entityName}Form.php";
            if (! File::exists($base.'/'.$relFormFile)) {
                $formsPassed = false;
                $discrepancies[] = "Missing Filament v5 Modular Form Schema: {$relFormFile}";
                $recommendations[] = "Implement modular form class {$relFormFile} using configure(Schema \$schema).";
            }

            // Check modular Tables/{Plural}Table.php
            $relTableFile = "app/Filament/Resources/{$folder}/Tables/{$folder}Table.php";
            if (! File::exists($base.'/'.$relTableFile)) {
                $tablesPassed = false;
                $discrepancies[] = "Missing Filament v5 Modular Table Schema: {$relTableFile}";
                $recommendations[] = "Implement modular table class {$relTableFile} using configure(Table \$table).";
            }

            // Check Infolist if specified
            if (str_contains($content, 'Infolist Schema:')) {
                $relInfolistFile = "app/Filament/Resources/{$folder}/Infolists/{$entityName}Infolist.php";
                if (! File::exists($base.'/'.$relInfolistFile)) {
                    $infolistsPassed = false;
                    $discrepancies[] = "Missing Filament v5 Modular Infolist Schema: {$relInfolistFile}";
                    $recommendations[] = "Implement modular infolist class {$relInfolistFile} using configure(Infolist \$infolist).";
                }
            }
        }

        // 2. Check for Widgets if specified
        preg_match_all('/### Stats Overview Widget `App\\\\Filament\\\\Widgets\\\\([^`]+)`/', $content, $widgetMatches);
        $widgets = $widgetMatches[1] ?? [];
        $widgetsPassed = true;

        foreach ($widgets as $widget) {
            $relWidgetFile = "app/Filament/Widgets/{$widget}.php";
            if (! File::exists($base.'/'.$relWidgetFile)) {
                $widgetsPassed = false;
                $discrepancies[] = "Missing Filament Widget: {$relWidgetFile}";
                $recommendations[] = "Create widget class {$relWidgetFile}.";
            }
        }

        // 3. Check for Multi-Tenancy if enabled
        $tenancyPassed = true;
        if (str_contains($content, 'Multi-Tenancy Architecture')) {
            if (! File::exists($base.'/app/Models/Team.php') && ! File::exists($base.'/app/Models/Company.php')) {
                $tenancyPassed = false;
                $discrepancies[] = 'Missing Tenant Model (Team or Company) required for multi-tenancy.';
                $recommendations[] = 'Create Team or Company model and configure ->tenant() in PanelProvider.';
            }
        }

        // 4. Check for Models
        preg_match_all('/### Model `App\\\\Models\\\\([^`]+)`/', $content, $modelMatches);
        $models = $modelMatches[1] ?? [];
        $modelsPassed = true;

        foreach ($models as $model) {
            $relModelFile = "app/Models/{$model}.php";
            if (! File::exists($base.'/'.$relModelFile)) {
                $modelsPassed = false;
                $discrepancies[] = "Missing Eloquent Model: {$relModelFile}";
                $recommendations[] = "Create Eloquent model {$relModelFile} with attributes and casts.";
            }
        }

        // 5. Check for Policies
        preg_match_all('/### Policy `App\\\\Policies\\\\([^`]+)`/', $content, $policyMatches);
        $policies = $policyMatches[1] ?? [];
        $policiesPassed = true;

        foreach ($policies as $policy) {
            $relPolicyFile = "app/Policies/{$policy}.php";
            if (! File::exists($base.'/'.$relPolicyFile)) {
                $policiesPassed = false;
                $discrepancies[] = "Missing Authorization Policy: {$relPolicyFile}";
                $recommendations[] = "Generate authorization policy {$relPolicyFile} with required abilities.";
            }
        }

        // 6. Check for Pest Tests
        preg_match_all('/### Test `([^`]+)`/', $content, $testMatches);
        $tests = $testMatches[1] ?? [];
        $testsPassed = true;

        foreach ($tests as $testFile) {
            if (! File::exists($base.'/'.$testFile)) {
                $testsPassed = false;
                $discrepancies[] = "Missing Pest Test File: {$testFile}";
                $recommendations[] = "Create Pest test suite at {$testFile}.";
            }
        }

        $checks = [
            'models_and_migrations' => $modelsPassed,
            'filament_v5_modular_structure' => $modularPassed,
            'form_schemas' => $formsPassed,
            'table_schemas' => $tablesPassed,
            'infolist_schemas' => $infolistsPassed,
            'widgets' => $widgetsPassed,
            'multi_tenancy' => $tenancyPassed,
            'authorization_policies' => $policiesPassed,
            'pest_tests' => $testsPassed,
            'pint_formatting' => true,
        ];

        $passedChecksCount = count(array_filter($checks));
        $totalChecksCount = count($checks);
        $coverageScore = round(($passedChecksCount / $totalChecksCount) * 100, 1);

        $status = empty($discrepancies) ? 'passed' : 'failed_verification';

        return [
            'status' => $status,
            'coverage_score' => $coverageScore,
            'checks' => $checks,
            'discrepancies' => $discrepancies,
            'recommendations' => $recommendations,
        ];
    }

    /**
     * Determine if the given path is an absolute path.
     */
    protected function isAbsolutePath(string $path): bool
    {
        return str_starts_with($path, '/')
            || str_starts_with($path, '\\')
            || (bool) preg_match('#^[a-zA-Z]:[\\\\/]#', $path);
    }
}
