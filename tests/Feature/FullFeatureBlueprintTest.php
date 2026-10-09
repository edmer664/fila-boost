<?php

namespace FilaBoost\FilamentBlueprint\Tests\Feature;

use FilaBoost\FilamentBlueprint\Mcp\GenerateBlueprintTool;
use FilaBoost\FilamentBlueprint\Tests\TestCase;

class FullFeatureBlueprintTest extends TestCase
{
    public function test_it_generates_comprehensive_blueprint_with_infolists_widgets_tenancy_and_themes(): void
    {
        $tool = new GenerateBlueprintTool;
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
