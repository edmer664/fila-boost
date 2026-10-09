---
name: planning-filament
description: "Generates comprehensive, self-contained Filament v5.x architecture plans for Laravel Boost. Activates when planning Filament v5 administrative resources, modular schemas, tables, clusters, widgets, infolists, tenancy, themes, or full admin panels."
version: 1.2.0
triggers:
  - "plan filament"
  - "filament plan"
  - "filament architecture"
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

When invoked, the AI coding agent MUST generate a structured, self-contained Filament v5.x architecture and implementation plan adhering strictly to official Filament v5 modular architecture.

## AI Grounding via Laravel Boost Documentation

Before guessing method signatures or component syntax, the agent SHOULD query Filament documentation using Laravel Boost's native `search-docs` tool:
- Use `search-docs` with `packages: ["filament/filament"]` and topic queries (e.g. `queries: ["infolist entries", "repeatable entry"]`, `queries: ["stats overview widget", "chart widget"]`, `queries: ["multi-tenancy"]`).
- Ground all generated form schemas, entry types, table columns, and widget signatures against official Filament v5.x documentation before drafting architecture plans.

## Guidelines & Rules

1. **Spec-First & Lightweight Agility**:
   - Focus on complete, unambiguous requirements without administrative bloat.
   - Prominently flag unresolved business decisions in an `## ⚠️ Unresolved Decisions` section at the top of the plan.
   - Save the plan to `plans/{feature}.md`.

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
