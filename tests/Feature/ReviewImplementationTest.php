<?php

namespace FilaBoost\Tests\Feature;

use FilaBoost\Mcp\ReviewImplementationTool;
use FilaBoost\Tests\TestCase;
use Illuminate\Support\Facades\File;

class ReviewImplementationTest extends TestCase
{
    public function test_it_audits_missing_files_against_plan(): void
    {
        $planDir = base_path('plans');
        if (! File::exists($planDir)) {
            File::makeDirectory($planDir, 0755, true);
        }

        $planPath = $planDir.'/test-feature.md';
        $dummyPlan = <<<MD
# Feature Plan: Test Feature
### Resource: `App\Filament\Resources\Invoices\InvoiceResource`
### Model `App\Models\Invoice`
### Policy `App\Policies\InvoicePolicy`
### Test `tests/Feature/Filament/InvoicesTest.php`
MD;
        File::put($planPath, $dummyPlan);

        $tool = new ReviewImplementationTool;
        $response = $tool->execute([
            'plan_file' => 'plans/test-feature.md',
            'run_pest_tests' => false,
            'run_pint_check' => false,
        ]);

        $this->assertEquals('failed_verification', $response['status']);
        $this->assertNotEmpty($response['discrepancies']);
        $this->assertFalse($response['checks']['filament_v5_modular_structure']);
        $this->assertFalse($response['checks']['models_and_migrations']);
        $this->assertFalse($response['checks']['authorization_policies']);

        // Clean up
        File::delete($planPath);
    }
}
