<?php

namespace FilaBoost\FilamentBlueprint\Tests\Feature;

use FilaBoost\FilamentBlueprint\Mcp\ReviewImplementationTool;
use FilaBoost\FilamentBlueprint\Tests\TestCase;
use Illuminate\Support\Facades\File;

class ReviewImplementationTest extends TestCase
{
    public function test_it_audits_missing_files_against_blueprint(): void
    {
        $blueprintDir = base_path('blueprints');
        if (!File::exists($blueprintDir)) {
            File::makeDirectory($blueprintDir, 0755, true);
        }

        $blueprintPath = $blueprintDir . '/test-feature.md';
        $dummyBlueprint = <<<MD
# Blueprint: Test Feature
### Resource: `App\Filament\Resources\Invoices\InvoiceResource`
### Model `App\Models\Invoice`
### Policy `App\Policies\InvoicePolicy`
### Test `tests/Feature/Filament/InvoicesTest.php`
MD;
        File::put($blueprintPath, $dummyBlueprint);

        $tool = new ReviewImplementationTool();
        $response = $tool->execute([
            'blueprint_file' => 'blueprints/test-feature.md',
            'run_pest_tests' => false,
            'run_pint_check' => false,
        ]);

        $this->assertEquals('failed_verification', $response['status']);
        $this->assertNotEmpty($response['discrepancies']);
        $this->assertFalse($response['checks']['filament_v5_modular_structure']);
        $this->assertFalse($response['checks']['models_and_migrations']);
        $this->assertFalse($response['checks']['authorization_policies']);

        // Clean up
        File::delete($blueprintPath);
    }
}
