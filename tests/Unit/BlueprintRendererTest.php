<?php

namespace FilaBoost\FilamentBlueprint\Tests\Unit;

use FilaBoost\FilamentBlueprint\Support\BlueprintRenderer;
use FilaBoost\FilamentBlueprint\Tests\TestCase;

class BlueprintRendererTest extends TestCase
{
    public function test_it_renders_filament_v5_modular_blueprint(): void
    {
        $renderer = new BlueprintRenderer;
        $result = $renderer->render([
            'feature_description' => 'Customer invoicing with line items and status tracking',
        ]);

        $this->assertEquals('success', $result['status']);
        $this->assertStringContainsString('blueprints/', $result['blueprint_file']);
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
