# Agent Skill Contract: `planning-filament`

## Skill Metadata (`SKILL.md` Specification)

```yaml
---
name: planning-filament
description: "Generates comprehensive, self-contained Filament v5.x Blueprints for Laravel Boost. Activates when planning Filament v5 administrative resources, modular schemas, tables, clusters, widgets, or full admin features."
version: 1.0.0
triggers:
  - "filament blueprint"
  - "plan filament"
  - "filament resource"
  - "create filament panel"
  - "filament admin"
  - "filament v5"
---
```

## System Prompt Contextual Rules (Filament v5.x Modular Standards)

When generating a Filament Blueprint, the agent MUST follow these strict rules:

1. **Lightweight Spec-First Approach**:
   - Begin by identifying user flows and data entities.
   - Flag unresolved business choices in an `Unresolved Decisions` callout at the top of the plan.

2. **Filament v5.x Modular Class Layout**:
   - Every resource MUST be planned according to the official Filament v5 modular architecture:
     ```text
     app/Filament/Resources/{Cluster?}/{Name}s/
     ├── {Name}Resource.php
     ├── Pages/
     │   ├── List{Name}s.php
     │   ├── Create{Name}.php
     │   ├── Edit{Name}.php
     │   └── View{Name}.php (if needed)
     ├── Schemas/
     │   └── {Name}Form.php
     └── Tables/
         └── {Name}sTable.php
     ```
   - In `{Name}Resource.php`, reference the modular classes:
     ```php
     public static function form(Schema $schema): Schema
     {
         return {Name}Form::configure($schema);
     }

     public static function table(Table $table): Table
     {
         return {Name}sTable::configure($table);
     }
     ```
   - In `{Name}Form.php`:
     - Implement `public static function configure(Schema $schema): Schema`
     - Return `$schema->components([...])` using components from `Filament\Schemas\Components\...` and `Filament\Forms\Components\...`.
   - In `{Name}sTable.php`:
     - Implement `public static function configure(Table $table): Table`
     - Return `$table->columns([...])->filters([...])->recordActions([...])->toolbarActions([...])`.

3. **Self-Contained Completeness**:
   - Every blueprint document MUST include:
     - Exact class names and fully qualified namespaces (`App\Filament\Resources\...`).
     - Models, migrations, attribute casts, and relationships with foreign keys.
     - Filament Resources, Clusters, and navigation items with icons.
     - Model Policy definitions with method abilities (`viewAny`, `view`, `create`, `update`, `delete`).
     - Pest PHP test specifications for resource rendering, form submissions, and table actions.

4. **Target Output File**:
   - Save the plan as a Markdown document in the project's `blueprints/` directory (e.g. `blueprints/feature-name.md`).
