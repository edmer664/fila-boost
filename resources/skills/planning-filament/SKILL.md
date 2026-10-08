---
name: planning-filament
description: "Generates comprehensive, self-contained Filament v5.x Blueprints for Laravel Boost. Activates when planning Filament v5 administrative resources, modular schemas, tables, clusters, widgets, infolists, tenancy, themes, or full admin panels."
version: 1.1.0
triggers:
  - "filament blueprint"
  - "plan filament"
  - "filament resource"
  - "create filament panel"
  - "filament admin"
  - "filament v5"
  - "filament infolist"
  - "filament widget"
  - "filament tenancy"
  - "filament theme"
---

# Filament v5.x Planning Skill (`planning-filament`)

When invoked, the AI coding agent MUST generate a structured, self-contained Filament v5.x Blueprint implementation plan adhering strictly to official Filament v5 modular architecture.

## AI Grounding via MCP Documentation Tools

Before guessing method signatures or component syntax, the agent SHOULD query the bundled official Filament documentation via MCP:
- `search_filament_docs`: Search by keyword (e.g. `query: "infolist entries"`, `query: "multi-tenancy"`, `query: "custom theme colors"`).
- `get_filament_doc`: Retrieve the full markdown reference (e.g. `topic_id: "12-components/02-infolist"`).

## Guidelines & Rules

1. **Spec-First & Lightweight Agility**:
   - Focus on complete, unambiguous requirements without administrative bloat.
   - Prominently flag unresolved business decisions in an `## ⚠️ Unresolved Decisions` section at the top of the plan.

2. **Strict Filament v5.x Modular Class Layout**:
   - Every resource MUST separate form schemas, tables, and infolists into modular classes:
     ```text
     app/Filament/Resources/{Cluster?}/{Name}s/
     ├── {Name}Resource.php
     ├── Pages/
     │   ├── List{Name}s.php
     │   ├── Create{Name}.php
     │   ├── Edit{Name}.php
     │   └── View{Name}.php
     ├── Schemas/
     │   └── {Name}Form.php          # Extends Schema configuration
     ├── Tables/
     │   └── {Name}sTable.php        # Extends Table configuration
     └── Infolists/
         └── {Name}Infolist.php      # Extends Infolist configuration
     ```

3. **Full Domain Coverage**:
   - **Infolists**: Modular class with `configure(Infolist $infolist): Infolist` using `TextEntry`, `IconEntry`, `Badge`, and `RepeatableEntry`.
   - **Widgets**: Dashboard and page widgets (`StatsOverviewWidget`, `ChartWidget`, `TableWidget`).
   - **Multi-Tenancy**: Tenant models, panel provider `->tenant(Team::class)` binding, and tenant-scoped query filters.
   - **Themes & Styling**: Panel custom color palettes (`Color::Amber`, `Color::Slate`), font definitions, and `PanelsRenderHook`.
   - **Authorization Policies**: Complete model policies (`App\Policies\{Name}Policy`) with tenant ownership scoping.
   - **Pest PHP Tests**: Complete test suites for forms, tables, and infolists.
