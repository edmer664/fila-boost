<?php

namespace FilaBoost\FilamentBlueprint\Support;

use Illuminate\Support\Str;

class BlueprintRenderer
{
    /**
     * Render a self-contained Filament v5.x Markdown Blueprint document.
     *
     * @param array $payload
     * @return array
     */
    public function render(array $payload): array
    {
        $rawDescription = $payload['feature_description'] ?? 'Filament Feature';
        $title = $payload['title'] ?? $this->extractTitle($rawDescription);
        $slug = $payload['slug'] ?? Str::kebab($title);
        $version = $payload['filament_version'] ?? '5.x';
        $targetFile = $payload['target_file'] ?? "blueprints/{$slug}.md";

        $lowerDesc = strtolower($rawDescription);
        $multiTenancy = (bool) ($payload['multi_tenancy'] ?? (str_contains($lowerDesc, 'tenant') || str_contains($lowerDesc, 'tenancy')));
        $includeInfolist = str_contains($lowerDesc, 'infolist') || str_contains($lowerDesc, 'view') || str_contains($lowerDesc, 'detail');
        $includeWidgets = str_contains($lowerDesc, 'widget') || str_contains($lowerDesc, 'chart') || str_contains($lowerDesc, 'stats') || str_contains($lowerDesc, 'dashboard');
        $includeTheme = str_contains($lowerDesc, 'theme') || str_contains($lowerDesc, 'color') || str_contains($lowerDesc, 'styling') || str_contains($lowerDesc, 'brand');

        $unresolvedDecisions = $this->detectUnresolvedDecisions($rawDescription);
        $entities = $this->extractEntities($rawDescription);

        $markdown = $this->generateMarkdown([
            'title' => $title,
            'slug' => $slug,
            'description' => $rawDescription,
            'version' => $version,
            'multi_tenancy' => $multiTenancy,
            'include_infolist' => $includeInfolist,
            'include_widgets' => $includeWidgets,
            'include_theme' => $includeTheme,
            'unresolved_decisions' => $unresolvedDecisions,
            'entities' => $entities,
        ]);

        return [
            'status' => 'success',
            'blueprint_file' => $targetFile,
            'title' => $title,
            'filament_version' => $version,
            'multi_tenancy' => $multiTenancy,
            'unresolved_decisions' => $unresolvedDecisions,
            'entities_detected' => array_keys($entities),
            'resources_planned' => array_map(function ($name) {
                $plural = Str::plural($name);
                return "App\\Filament\\Resources\\{$plural}\\{$name}Resource";
            }, array_keys($entities)),
            'infolists_planned' => $includeInfolist ? array_map(function ($name) {
                $plural = Str::plural($name);
                return "App\\Filament\\Resources\\{$plural}\\Infolists\\{$name}Infolist";
            }, array_keys($entities)) : [],
            'widgets_planned' => $includeWidgets ? [
                "App\\Filament\\Widgets\\{$title}StatsOverviewWidget",
                "App\\Filament\\Widgets\\{$title}ChartWidget",
            ] : [],
            'markdown_content' => $markdown,
        ];
    }

    /**
     * Extract title from description.
     */
    protected function extractTitle(string $description): string
    {
        $firstLine = trim(explode("\n", $description)[0]);
        $clean = preg_replace('/^(Build|Create|Implement|Plan)\s+/i', '', $firstLine);
        return Str::headline(Str::limit($clean, 50, ''));
    }

    /**
     * Detect business ambiguities that should be flagged at the top of the plan.
     */
    protected function detectUnresolvedDecisions(string $description): array
    {
        $decisions = [];
        $lower = strtolower($description);

        if (str_contains($lower, 'status') || str_contains($lower, 'workflow')) {
            $decisions[] = 'Status transitions and approval workflow roles require final business sign-off.';
        }

        if (str_contains($lower, 'payment') || str_contains($lower, 'invoice') || str_contains($lower, 'price') || str_contains($lower, 'revenue')) {
            $decisions[] = 'Currency handling and precision rules (decimal vs integer cents) should be confirmed.';
        }

        if (str_contains($lower, 'tenant') || str_contains($lower, 'tenancy')) {
            $decisions[] = 'Multi-tenancy registration: Confirm whether users can belong to multiple tenants or a single tenant.';
        }

        if (str_contains($lower, 'delete') || str_contains($lower, 'archive')) {
            $decisions[] = 'Retention policy: Soft deletes versus immediate hard deletion needs confirmation.';
        }

        if (empty($decisions)) {
            $decisions[] = 'Default permissions: Confirm whether all administrative users possess full access by default.';
        }

        return $decisions;
    }

    /**
     * Infer entities from description.
     */
    protected function extractEntities(string $description): array
    {
        $entities = [];
        $words = preg_split('/[\s,\.\-]+/', $description);
        $stopWords = ['a', 'an', 'the', 'with', 'and', 'or', 'for', 'in', 'on', 'at', 'to', 'from', 'build', 'create', 'plan', 'management', 'system', 'portal', 'custom', 'view', 'chart', 'widget', 'theme', 'color', 'styling', 'tenant', 'tenancy'];

        foreach ($words as $word) {
            $singular = Str::singular(Str::studly($word));
            if (strlen($singular) >= 3 && !in_array(strtolower($word), $stopWords)) {
                if (!isset($entities[$singular]) && count($entities) < 3) {
                    $entities[$singular] = [
                        'name' => $singular,
                        'table' => Str::snake(Str::plural($singular)),
                    ];
                }
            }
        }

        if (empty($entities)) {
            $entities['Item'] = ['name' => 'Item', 'table' => 'items'];
        }

        return $entities;
    }

    /**
     * Build full self-contained Markdown text adhering strictly to Filament v5.x modular structure.
     */
    protected function generateMarkdown(array $data): string
    {
        $title = $data['title'];
        $version = $data['version'];
        $unresolved = $data['unresolved_decisions'];
        $entities = $data['entities'];
        $multiTenancy = $data['multi_tenancy'];
        $includeInfolist = $data['include_infolist'];
        $includeWidgets = $data['include_widgets'];
        $includeTheme = $data['include_theme'];

        $out = [];
        $out[] = "# Blueprint: {$title} (Filament v{$version})";
        $out[] = "";
        $out[] = "> **Generated by Fila-boost** | **Architecture: Filament v5.x Modular** | **Multi-tenancy: " . ($multiTenancy ? 'Enabled' : 'Disabled') . "**";
        $out[] = "";

        if (!empty($unresolved)) {
            $out[] = "## ⚠️ Unresolved Decisions";
            $out[] = "";
            foreach ($unresolved as $item) {
                $out[] = "- [ ] {$item}";
            }
            $out[] = "";
        }

        // Multi-Tenancy section
        if ($multiTenancy) {
            $out[] = "## Multi-Tenancy Architecture";
            $out[] = "";
            $out[] = "- **Tenant Entity**: `App\\Models\\Team` (or Company)";
            $out[] = "- **Ownership Trait / Interface**: Eloquent models use `BelongsToTeam` / `team_id` foreign key.";
            $out[] = "- **Panel Registration** in `AdminPanelProvider`:";
            $out[] = "```php";
            $out[] = "->tenant(Team::class)";
            $out[] = "```";
            $out[] = "- **Query Scoping**: Global scope filters automatically by current tenant context.";
            $out[] = "";
        }

        // Theme and Styling section
        if ($includeTheme) {
            $out[] = "## Theme & Brand Customization";
            $out[] = "";
            $out[] = "- **Panel Configuration** in `AdminPanelProvider`:";
            $out[] = "```php";
            $out[] = "use Filament\\Support\\Colors\\Color;";
            $out[] = "";
            $out[] = "->colors([";
            $out[] = "    'primary' => Color::Amber,";
            $out[] = "    'gray' => Color::Slate,";
            $out[] = "])";
            $out[] = "->font('Inter')";
            $out[] = "```";
            $out[] = "- **Render Hooks**: Available via `Filament\\View\\PanelsRenderHook`.";
            $out[] = "";
        }

        $out[] = "## 1. Domain & Data Model";
        $out[] = "";
        foreach ($entities as $name => $meta) {
            $table = $meta['table'];
            $out[] = "### Model `App\\Models\\{$name}`";
            $out[] = "- **Table**: `{$table}`";
            $out[] = "- **Fillable Attributes**: `name`, `status`, `notes`" . ($multiTenancy ? ", `team_id`" : "");
            $out[] = "- **Casts**: `['status' => 'string']`";
            $out[] = "";
            $out[] = "```php";
            $out[] = "namespace App\\Models;";
            $out[] = "";
            $out[] = "use Illuminate\\Database\\Eloquent\\Model;";
            $out[] = "use Illuminate\\Database\\Eloquent\\Factories\\HasFactory;";
            $out[] = "";
            $out[] = "class {$name} extends Model";
            $out[] = "{";
            $out[] = "    use HasFactory;";
            $out[] = "";
            $out[] = "    protected \$fillable = ['name', 'status', 'notes'" . ($multiTenancy ? ", 'team_id'" : "") . "];";
            if ($multiTenancy) {
                $out[] = "";
                $out[] = "    public function team()";
                $out[] = "    {";
                $out[] = "        return \$this->belongsTo(Team::class);";
                $out[] = "    }";
            }
            $out[] = "}";
            $out[] = "```";
            $out[] = "";
        }

        $out[] = "## 2. Filament v5.x Modular Resource Architecture";
        $out[] = "";

        foreach ($entities as $name => $meta) {
            $plural = Str::plural($name);
            $resourceClass = "App\\Filament\\Resources\\{$plural}\\{$name}Resource";
            $formClass = "App\\Filament\\Resources\\{$plural}\\Schemas\\{$name}Form";
            $tableClass = "App\\Filament\\Resources\\{$plural}\\Tables\\{$plural}Table";
            $infolistClass = "App\\Filament\\Resources\\{$plural}\\Infolists\\{$name}Infolist";

            $out[] = "### Resource: `{$resourceClass}`";
            $out[] = "- **Directory Namespace**: `App\\Filament\\Resources\\{$plural}`";
            $out[] = "- **Model**: `App\\Models\\{$name}`";
            $out[] = "- **Record Title Attribute**: `'name'`";
            $out[] = "- **Navigation Group**: `'Management'`";
            $out[] = "- **Navigation Icon**: `'heroicon-o-rectangle-stack'`";
            $out[] = "";
            $out[] = "```php";
            $out[] = "namespace App\\Filament\\Resources\\{$plural};";
            $out[] = "";
            $out[] = "use App\\Filament\\Resources\\{$plural}\\Pages;";
            $out[] = "use App\\Filament\\Resources\\{$plural}\\Schemas\\{$name}Form;";
            $out[] = "use App\\Filament\\Resources\\{$plural}\\Tables\\{$plural}Table;";
            if ($includeInfolist) {
                $out[] = "use App\\Filament\\Resources\\{$plural}\\Infolists\\{$name}Infolist;";
            }
            $out[] = "use App\\Models\\{$name};";
            $out[] = "use Filament\\Resources\\Resource;";
            $out[] = "use Filament\\Schemas\\Schema;";
            $out[] = "use Filament\\Tables\\Table;";
            if ($includeInfolist) {
                $out[] = "use Filament\\Infolists\\Infolist;";
            }
            $out[] = "";
            $out[] = "class {$name}Resource extends Resource";
            $out[] = "{";
            $out[] = "    protected static ?string \$model = {$name}::class;";
            $out[] = "    protected static ?string \$recordTitleAttribute = 'name';";
            $out[] = "    protected static ?string \$navigationIcon = 'heroicon-o-rectangle-stack';";
            $out[] = "";
            $out[] = "    public static function form(Schema \$schema): Schema";
            $out[] = "    {";
            $out[] = "        return {$name}Form::configure(\$schema);";
            $out[] = "    }";
            $out[] = "";
            $out[] = "    public static function table(Table \$table): Table";
            $out[] = "    {";
            $out[] = "        return {$plural}Table::configure(\$table);";
            $out[] = "    }";
            if ($includeInfolist) {
                $out[] = "";
                $out[] = "    public static function infolist(Infolist \$infolist): Infolist";
                $out[] = "    {";
                $out[] = "        return {$name}Infolist::configure(\$infolist);";
                $out[] = "    }";
            }
            $out[] = "";
            $out[] = "    public static function getPages(): array";
            $out[] = "    {";
            $out[] = "        return [";
            $out[] = "            'index' => Pages\\List{$plural}::route('/'),";
            $out[] = "            'create' => Pages\\Create{$name}::route('/create'),";
            $out[] = "            'edit' => Pages\\Edit{$name}::route('/{record}/edit'),";
            if ($includeInfolist) {
                $out[] = "            'view' => Pages\\View{$name}::route('/{record}'),";
            }
            $out[] = "        ];";
            $out[] = "    }";
            $out[] = "}";
            $out[] = "```";
            $out[] = "";

            $out[] = "#### Form Schema: `{$formClass}`";
            $out[] = "```php";
            $out[] = "namespace App\\Filament\\Resources\\{$plural}\\Schemas;";
            $out[] = "";
            $out[] = "use Filament\\Forms\\Components\\TextInput;";
            $out[] = "use Filament\\Forms\\Components\\Textarea;";
            $out[] = "use Filament\\Schemas\\Components\\Section;";
            $out[] = "use Filament\\Schemas\\Schema;";
            $out[] = "";
            $out[] = "class {$name}Form";
            $out[] = "{";
            $out[] = "    public static function configure(Schema \$schema): Schema";
            $out[] = "    {";
            $out[] = "        return \$schema->components([";
            $out[] = "            Section::make('General Information')->schema([";
            $out[] = "                TextInput::make('name')";
            $out[] = "                    ->required()";
            $out[] = "                    ->maxLength(255),";
            $out[] = "                TextInput::make('status')";
            $out[] = "                    ->default('active')";
            $out[] = "                    ->required(),";
            $out[] = "                Textarea::make('notes')";
            $out[] = "                    ->nullable()";
            $out[] = "                    ->columnSpanFull(),";
            $out[] = "            ]),";
            $out[] = "        ]);";
            $out[] = "    }";
            $out[] = "}";
            $out[] = "```";
            $out[] = "";

            $out[] = "#### Table Schema: `{$tableClass}`";
            $out[] = "```php";
            $out[] = "namespace App\\Filament\\Resources\\{$plural}\\Tables;";
            $out[] = "";
            $out[] = "use Filament\\Actions\\DeleteAction;";
            $out[] = "use Filament\\Actions\\EditAction;";
            $out[] = "use Filament\\Actions\\BulkActionGroup;";
            $out[] = "use Filament\\Actions\\DeleteBulkAction;";
            $out[] = "use Filament\\Tables\\Columns\\TextColumn;";
            $out[] = "use Filament\\Tables\\Table;";
            $out[] = "";
            $out[] = "class {$plural}Table";
            $out[] = "{";
            $out[] = "    public static function configure(Table \$table): Table";
            $out[] = "    {";
            $out[] = "        return \$table";
            $out[] = "            ->columns([";
            $out[] = "                TextColumn::make('name')->searchable()->sortable(),";
            $out[] = "                TextColumn::make('status')->badge(),";
            $out[] = "                TextColumn::make('created_at')->dateTime()->sortable(),";
            $out[] = "            ])";
            $out[] = "            ->recordActions([";
            $out[] = "                EditAction::make(),";
            $out[] = "                DeleteAction::make(),";
            $out[] = "            ])";
            $out[] = "            ->toolbarActions([";
            $out[] = "                BulkActionGroup::make([";
            $out[] = "                    DeleteBulkAction::make(),";
            $out[] = "                ]),";
            $out[] = "            ]);";
            $out[] = "    }";
            $out[] = "}";
            $out[] = "```";
            $out[] = "";

            // Modular Infolist Schema
            if ($includeInfolist) {
                $out[] = "#### Infolist Schema: `{$infolistClass}`";
                $out[] = "```php";
                $out[] = "namespace App\\Filament\\Resources\\{$plural}\\Infolists;";
                $out[] = "";
                $out[] = "use Filament\\Infolists\\Components\\Section;";
                $out[] = "use Filament\\Infolists\\Components\\TextEntry;";
                $out[] = "use Filament\\Infolists\\Infolist;";
                $out[] = "";
                $out[] = "class {$name}Infolist";
                $out[] = "{";
                $out[] = "    public static function configure(Infolist \$infolist): Infolist";
                $out[] = "    {";
                $out[] = "        return \$infolist->components([";
                $out[] = "            Section::make('Record Details')->schema([";
                $out[] = "                TextEntry::make('name'),";
                $out[] = "                TextEntry::make('status')->badge(),";
                $out[] = "                TextEntry::make('created_at')->dateTime(),";
                $out[] = "            ]),";
                $out[] = "        ]);";
                $out[] = "    }";
                $out[] = "}";
                $out[] = "```";
                $out[] = "";
            }
        }

        // Widgets section
        if ($includeWidgets) {
            $out[] = "## 3. Filament Widgets";
            $out[] = "";
            $out[] = "### Stats Overview Widget `App\\Filament\\Widgets\\{$title}StatsOverviewWidget`";
            $out[] = "```php";
            $out[] = "namespace App\\Filament\\Widgets;";
            $out[] = "";
            $out[] = "use Filament\\Widgets\\StatsOverviewWidget as BaseWidget;";
            $out[] = "use Filament\\Widgets\\StatsOverviewWidget\\Stat;";
            $out[] = "";
            $out[] = "class {$title}StatsOverviewWidget extends BaseWidget";
            $out[] = "{";
            $out[] = "    protected function getStats(): array";
            $out[] = "    {";
            $out[] = "        return [";
            $out[] = "            Stat::make('Total Records', '1,280')->description('32% increase')->descriptionIcon('heroicon-m-arrow-trending-up'),";
            $out[] = "            Stat::make('Active Status', '940')->color('success'),";
            $out[] = "        ];";
            $out[] = "    }";
            $out[] = "}";
            $out[] = "```";
            $out[] = "";
        }

        $out[] = "## 4. Authorization & Security Policies";
        $out[] = "";
        foreach ($entities as $name => $meta) {
            $out[] = "### Policy `App\\Policies\\{$name}Policy`";
            $out[] = "- `viewAny(User \$user): bool` => return true / check role";
            $out[] = "- `view(User \$user, {$name} \$model): bool` => return true";
            $out[] = "- `create(User \$user): bool` => return true";
            $out[] = "- `update(User \$user, {$name} \$model): bool` => return true";
            $out[] = "- `delete(User \$user, {$name} \$model): bool` => return true";
            if ($multiTenancy) {
                $out[] = "- **Tenancy Check**: `$user->team_id === $model->team_id`";
            }
            $out[] = "";
        }

        $out[] = "## 5. Pest PHP Test Specifications";
        $out[] = "";
        foreach ($entities as $name => $meta) {
            $plural = Str::plural($name);
            $out[] = "### Test `tests/Feature/Filament/{$plural}Test.php`";
            $out[] = "```php";
            $out[] = "use App\\Filament\\Resources\\{$plural}\\Pages\\List{$plural};";
            $out[] = "use App\\Filament\\Resources\\{$plural}\\Pages\\Create{$name};";
            $out[] = "use App\\Models\\{$name};";
            $out[] = "use function Pest\\Livewire\\livewire;";
            $out[] = "";
            $out[] = "it('can render list page', function () {";
            $out[] = "    livewire(List{$plural}::class)->assertSuccessful();";
            $out[] = "});";
            $out[] = "";
            $out[] = "it('can validate and create {$name}', function () {";
            $out[] = "    livewire(Create{$name}::class)";
            $out[] = "        ->fillForm([";
            $out[] = "            'name' => 'Test {$name}',";
            $out[] = "            'status' => 'active',";
            $out[] = "        ])";
            $out[] = "        ->call('create')";
            $out[] = "        ->assertHasNoFormErrors();";
            $out[] = "";
            $out[] = "    expect({$name}::where('name', 'Test {$name}')->exists())->toBeTrue();";
            $out[] = "});";
            $out[] = "```";
            $out[] = "";
        }

        return implode("\n", $out);
    }
}
