# Phase 0 Research: Full Filament Feature Coverage & Documentation MCP Tooling

## Context & Objectives
To maximize AI productivity in Laravel Boost, Fila-boost must expand beyond basic Resources and Tables to support **all Filament features** (Infolists, Widgets, Multi-Tenancy, Themes/Styling, Advanced Layouts), while providing dedicated MCP tools that let AI agents search and retrieve the bundled documentation in `docs/`.

---

### Research Item 1: In-Package Markdown Documentation Indexing & Retrieval via MCP

- **Decision**: Implement a zero-dependency PHP documentation indexing service `DocsRepository` and expose two standardized MCP tools:
  1. `search_filament_docs`: Takes a search query (`query`, optional `category`, optional `limit`), computes relevance rankings against file titles, headings, and markdown content across `docs/`, and returns structured results (slug, title, category, snippet, relative path).
  2. `get_filament_doc`: Takes a topic `topic_id` (or path, e.g. `12-components/02-infolist` or `infolist`), reads the markdown content, and returns the full content with structured metadata.
- **Rationale**: The package repository already embeds an extensive, authoritative documentation library under `docs/` (40+ markdown files). Indexing this locally provides sub-second, 100% offline querying for coding agents without relying on third-party API rates or internet connections.
- **Alternatives Considered**:
  - *External web scraper / Google search*: Fragile, requires internet, high latency, context polluting.
  - *Bundling entire docs in system prompt*: Rejected; would exceed model context limits and waste tokens.

---

### Research Item 2: Comprehensive Filament v5.x Domain Support in Blueprints

- **Decision**: Expand `BlueprintRenderer` and Agent Skills to generate blueprints covering:
  1. **Infolists**: `App\Filament\Resources\{Cluster?}\{Name}s\Infolists\{Name}Infolist.php` (modular) or `infolist(Infolist $infolist)` with `TextEntry`, `IconEntry`, `ImageEntry`, `RepeatableEntry`.
  2. **Widgets**: `App\Filament\Widgets\{Name}Widget.php` or resource-specific widgets (`App\Filament\Resources\{Name}s\Widgets\...`) including `StatsOverviewWidget`, `ChartWidget`, and embedded `TableWidget`.
  3. **Multi-Tenancy**: Tenant models implementing `Filament\Models\Contracts\HasTenants` or `Filament\Models\Contracts\FilamentUser`, panel tenancy registration in `PanelProvider` (`tenant(Team::class)`), and tenant-scoped relationship queries.
  4. **Themes & Styling**: Panel styling configurations (`colors()`, `font()`, `viteTheme()`), custom CSS hooks, and render hooks (`Filament\View\PanelsRenderHook`).
  5. **Advanced Layouts**: Layout containers (`Tabs`, `Wizard`, `Split`, `Section`, `Grid`, `Fieldset`, `ModalAction`) across both form and infolist schemas.
- **Rationale**: Official Filament v5 architecture strongly emphasizes modularity and rich component composition. Providing complete patterns ensures agents generate production-grade enterprise panels without guesswork.
- **Alternatives Considered**:
  - *Separate packages for each feature*: Adds installation friction and fragments the agent experience. A unified package ensures cohesive blueprints.

---

### Research Item 3: Extended Implementation Auditing

- **Decision**: Upgrade `ImplementationAuditor` and `ReviewImplementationTool` to audit:
  - Multi-tenancy scoping on Eloquent queries.
  - Presence of modular infolists when specified in the blueprint.
  - Presence and registration of specified dashboard widgets.
  - Verification that theme/color customizations follow v5 panel provider hooks.
- **Rationale**: Aligns with Constitution Principle IV (Rigorous Quality & Verification Gates).
