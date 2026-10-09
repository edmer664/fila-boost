<?php

namespace FilaBoost\Tests\Feature;

use FilaBoost\Mcp\ReviewImplementationTool;
use FilaBoost\Tests\TestCase;
use Illuminate\Support\Facades\File;

class ExtendedReviewTest extends TestCase
{
    public function test_it_audits_missing_infolists_and_widgets(): void
    {
        $planDir = base_path('plans');
        if (! File::exists($planDir)) {
            File::makeDirectory($planDir, 0755, true);
        }

        $planPath = $planDir.'/extended-feature.md';
        $dummyPlan = <<<MD
# Feature Plan: Extended Feature
### Resource: `App\Filament\Resources\Clients\ClientResource`
#### Infolist Schema: `App\Filament\Resources\Clients\Infolists\ClientInfolist`
### Stats Overview Widget `App\Filament\Widgets\ClientStatsOverviewWidget`
### Model `App\Models\Client`
### Policy `App\Policies\ClientPolicy`
### Test `tests/Feature/Filament/ClientsTest.php`
## Multi-Tenancy Architecture
MD;
        File::put($planPath, $dummyPlan);

        $tool = new ReviewImplementationTool;
        $response = $tool->execute([
            'plan_file' => 'plans/extended-feature.md',
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

        File::delete($planPath);
    }
}
