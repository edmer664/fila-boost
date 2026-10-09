<?php

namespace FilaBoost\Tests\Feature;

use FilaBoost\Mcp\GeneratePlanTool;
use FilaBoost\Tests\TestCase;

class FullFeaturePlanTest extends TestCase
{
    public function test_it_generates_comprehensive_plan_with_infolists_widgets_tenancy_and_themes(): void
    {
        $tool = new GeneratePlanTool;
        $response = $tool->execute([
            'feature_description' => 'Multi-tenant client portal with client infolist view, revenue chart widget, custom theme styling, and tenant isolation',
        ]);

        $this->assertEquals('success', $response['status']);
        $this->assertTrue($response['multi_tenancy']);
        $this->assertNotEmpty($response['infolists_planned']);
        $this->assertNotEmpty($response['widgets_planned']);

        $markdown = $response['markdown_content'];

        // Verify Infolist
        $this->assertStringContainsString('Infolist Schema', $markdown);
        $this->assertStringContainsString('TextEntry::make', $markdown);

        // Verify Widgets
        $this->assertStringContainsString('StatsOverviewWidget', $markdown);

        // Verify Tenancy
        $this->assertStringContainsString('Multi-Tenancy Architecture', $markdown);
        $this->assertStringContainsString('team_id', $markdown);
        $this->assertStringContainsString('->tenant(Team::class)', $markdown);

        // Verify Themes
        $this->assertStringContainsString('Theme & Brand Customization', $markdown);
        $this->assertStringContainsString('->colors([', $markdown);
    }
}
