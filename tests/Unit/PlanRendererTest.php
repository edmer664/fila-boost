<?php

namespace FilaBoost\Tests\Unit;

use FilaBoost\Support\PlanRenderer;
use FilaBoost\Tests\TestCase;

class PlanRendererTest extends TestCase
{
    public function test_it_renders_filament_v5_modular_plan(): void
    {
        $renderer = new PlanRenderer;
        $result = $renderer->render([
            'feature_description' => 'Customer invoicing with line items and status tracking',
        ]);

        $this->assertEquals('success', $result['status']);
        $this->assertStringContainsString('plans/', $result['plan_file']);
        $this->assertEquals('5.x', $result['filament_version']);
        $this->assertNotEmpty($result['unresolved_decisions']);
        $this->assertNotEmpty($result['entities_detected']);

        $markdown = $result['markdown_content'];

        // Assert Filament v5 modular architecture
        $this->assertStringContainsString('Filament v5.x Modular', $markdown);
        $this->assertStringContainsString('Schemas\\', $markdown);
        $this->assertStringContainsString('Tables\\', $markdown);
        $this->assertStringContainsString('configure(Schema $schema): Schema', $markdown);
        $this->assertStringContainsString('configure(Table $table): Table', $markdown);

        // Assert tests section
        $this->assertStringContainsString('Pest PHP Test Specifications', $markdown);
    }
}
