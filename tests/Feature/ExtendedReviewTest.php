<?php

namespace FilaBoost\FilamentBlueprint\Tests\Feature;

use FilaBoost\FilamentBlueprint\Mcp\ReviewImplementationTool;
use FilaBoost\FilamentBlueprint\Tests\TestCase;
use Illuminate\Support\Facades\File;

class ExtendedReviewTest extends TestCase
{
    public function test_it_audits_missing_infolists_and_widgets(): void
    {
        $blueprintDir = base_path('blueprints');
        if (!File::exists($blueprintDir)) {
            File::makeDirectory($blueprintDir, 0755, true);
        }

        $blueprintPath = $blueprintDir . '/extended-feature.md';
        $dummyBlueprint = <<<MD
# Blueprint: Extended Feature
### Resource: `App\Filament\Resources\Clients\ClientResource`
#### Infolist Schema: `App\Filament\Resources\Clients\Infolists\ClientInfolist`
### Stats Overview Widget `App\Filament\Widgets\ClientStatsOverviewWidget`
### Model `App\Models\Client`
### Policy `App\Policies\ClientPolicy`
### Test `tests/Feature/Filament/ClientsTest.php`
## Multi-Tenancy Architecture
MD;
        File::put($blueprintPath, $dummyBlueprint);

        $tool = new ReviewImplementationTool();
        $response = $tool->execute([
            'blueprint_file' => 'blueprints/extended-feature.md',
            'run_pest_tests' => false,
            'run_pint_check' => false,
        ]);

        $this->assertEquals('failed_verification', $response['status']);
        $this->assertFalse($response['checks']['infolist_schemas']);
        $this->assertFalse($response['checks']['widgets']);
        $this->assertFalse($response['checks']['multi_tenancy']);

        $discrepanciesText = implode(' ', $response['discrepancies']);
        $this->assertStringContainsString('Infolist', $discrepanciesText);
        $this->assertStringContainsString('Widget', $discrepanciesText);
        $this->assertStringContainsString('Tenant Model', $discrepanciesText);

        File::delete($blueprintPath);
    }
}
