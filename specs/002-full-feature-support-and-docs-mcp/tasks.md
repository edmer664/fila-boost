# Tasks: Full Filament Feature Coverage & Documentation MCP Tooling

**Feature**: `002-full-feature-support-and-docs-mcp`  
**Input**: [spec.md](./spec.md), [plan.md](./plan.md), [data-model.md](./data-model.md), [contracts/](./contracts/), [research.md](./research.md)

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Initialize directory structures and test harness for documentation MCP tools and extended domain models.

- [X] T001 Initialize documentation indexing and MCP test structures in `tests/Unit/` and `tests/Feature/`
- [X] T002 [P] Verify bundled documentation tree in `docs/` contains complete component and styling guides per FR-003

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core documentation indexing repository and base schema builders that all user stories depend on.

- [X] T003 Implement `DocsRepository` in `src/Support/DocsRepository.php` to parse frontmatter, extract headings, compute search relevance scores, and retrieve full markdown articles from `docs/`
- [X] T004 Unit test for `DocsRepository` in `tests/Unit/DocsRepositoryTest.php` testing keyword search, category filtering, and slug resolution
- [X] T005 [P] Register `DocsRepository` as a singleton in `src/FilaBoostServiceProvider.php`

**Checkpoint**: Documentation repository indexed and tested. User story implementation can now begin.

---

## Phase 3: User Story 1 - AI Agent Documentation Querying via MCP (Priority: P1) 🎯 MVP

**Goal**: Deliver `search_filament_docs` and `get_filament_doc` MCP tools so AI agents can query and read official Filament documentation directly through Laravel Boost.

**Independent Test**: Can be tested by executing `SearchDocsTool` and `GetDocTool` with sample queries (e.g. "infolist entries", "tenancy", "colors"), verifying accurate topic matches and complete markdown content.

### Tests for User Story 1

- [X] T006 [P] [US1] Unit test for documentation MCP tool schemas in `tests/Unit/DocsMcpToolSchemaTest.php` verifying conformance to `contracts/mcp-search-docs.md` and `contracts/mcp-get-doc.md`
- [X] T007 [P] [US1] Feature test for documentation querying in `tests/Feature/DocsMcpToolsTest.php` verifying search ranking and document retrieval

### Implementation for User Story 1

- [X] T008 [P] [US1] Implement `SearchDocsTool` MCP tool in `src/Mcp/SearchDocsTool.php` declaring MCP JSON schema parameters (`query` required, `category` optional, `limit` default 5) matching `contracts/mcp-search-docs.md`
- [X] T009 [P] [US1] Implement `GetDocTool` MCP tool in `src/Mcp/GetDocTool.php` declaring MCP JSON schema parameters (`topic_id` required) matching `contracts/mcp-get-doc.md`
- [X] T010 [US1] Register `SearchDocsTool` and `GetDocTool` in `src/FilaBoostServiceProvider.php` for Laravel Boost MCP discovery
- [X] T011 [US1] Update `resources/skills/planning-filament/SKILL.md` to instruct AI agents on when and how to call `search_filament_docs` and `get_filament_doc` to retrieve authoritative component syntax

**Checkpoint**: User Story 1 is functional. AI coding agents can now query and read all bundled Filament documentation offline via MCP.

---

## Phase 4: User Story 2 - Comprehensive Filament Feature Blueprint Planning (Priority: P2)

**Goal**: Expand `BlueprintRenderer` and planning skills to support all Filament features: Infolists, Widgets, Multi-Tenancy, Themes/Styling, and Advanced Layouts (Tabs, Wizards, Split).

**Independent Test**: Can be tested by invoking `GenerateBlueprintTool` with complex prompts (e.g. "Agency portal with client infolists, revenue chart widget, slate/amber theme, and tenant isolation"), asserting that the generated blueprint contains full specifications for all requested features.

### Tests for User Story 2

- [X] T012 [P] [US2] Feature test for extended blueprint generation in `tests/Feature/FullFeatureBlueprintTest.php` verifying infolists, widgets, tenancy, and theme outputs

### Implementation for User Story 2

- [X] T013 [P] [US2] Extend `BlueprintRenderer` in `src/Support/BlueprintRenderer.php` to generate modular Infolist schemas (`Schemas/{Name}Infolist.php`) with `TextEntry`, `IconEntry`, `Badge`, and `RepeatableEntry` per FR-004
- [X] T014 [P] [US2] Extend `BlueprintRenderer` in `src/Support/BlueprintRenderer.php` to generate Widget classes (`StatsOverviewWidget`, `ChartWidget`, and `TableWidget`) per FR-005
- [X] T015 [US2] Extend `BlueprintRenderer` in `src/Support/BlueprintRenderer.php` to generate Multi-Tenancy configurations (tenant models, panel `tenant()` registration, tenant-scoped policies) per FR-006
- [X] T016 [US2] Extend `BlueprintRenderer` in `src/Support/BlueprintRenderer.php` to generate Panel Theme configurations (colors, font, CSS hooks, render hooks) per FR-007
- [X] T017 [US2] Update `resources/skills/planning-filament/SKILL.md` with guidelines and code patterns for Infolists, Widgets, Tenancy, Themes, and Layouts

**Checkpoint**: User Story 2 is functional. Generated blueprints provide comprehensive specifications for all Filament domains.

---

## Phase 5: User Story 3 - Comprehensive Implementation Review & Verification (Priority: P3)

**Goal**: Upgrade `ImplementationAuditor` and review skills to audit infolists, widgets, theme configurations, and tenant query scoping alongside resources and tables.

**Independent Test**: Can be tested by running `ReviewImplementationTool` against codebases with partial implementations, verifying that missing tenant scoping, unregistered widgets, or missing infolist classes are flagged.

### Tests for User Story 3

- [X] T018 [P] [US3] Feature test for extended codebase auditing in `tests/Feature/ExtendedReviewTest.php` verifying detection of missing infolists, widgets, and tenancy gaps

### Implementation for User Story 3

- [X] T019 [US3] Extend `ImplementationAuditor` in `src/Support/ImplementationAuditor.php` to verify presence of modular Infolist classes, Widget registrations, and Panel Theme configurations
- [X] T020 [US3] Add tenant scoping audit checks in `src/Support/ImplementationAuditor.php` ensuring queries and models enforce tenant ownership when tenancy is planned
- [X] T021 [US3] Update `resources/skills/reviewing-filament-plans/SKILL.md` with audit criteria for infolists, widgets, tenancy, and theme styling

**Checkpoint**: User Story 3 is functional. The review auditor verifies compliance across all Filament features.

---

## Phase 6: Polish & Cross-Cutting Concerns

**Purpose**: Documentation updates, Pint formatting, and validation walkthroughs.

- [X] T022 [P] Update package documentation in `README.md` detailing documentation MCP tools (`search_filament_docs`, `get_filament_doc`) and comprehensive feature blueprinting examples
- [X] T023 Run PHP syntax verification across all newly created and updated source and test files
- [X] T024 Execute quickstart validation scenarios defined in `specs/002-full-feature-support-and-docs-mcp/quickstart.md`

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies — can start immediately.
- **Foundational (Phase 2)**: Depends on Phase 1 completion — BLOCKS all user stories.
- **User Story 1 (Phase 3 - P1)**: Depends on Phase 2 (`DocsRepository`). Delivers documentation MCP tooling (MVP).
- **User Story 2 (Phase 4 - P2)**: Can proceed after Phase 2; leverages documentation tools to ground blueprint extensions.
- **User Story 3 (Phase 5 - P3)**: Depends on blueprint formats finalized in User Story 2.
- **Polish (Phase 6)**: Depends on all user stories (Phases 3-5) being complete.

### User Story Dependencies

- **User Story 1 (P1)**: Independent of US2 and US3.
- **User Story 2 (P2)**: Independent implementation of renderer extensions.
- **User Story 3 (P3)**: Audits the blueprint structure produced by US2.

### Parallel Opportunities

- Within Phase 1: `T001` and `T002` can run in parallel.
- Within Phase 3 (US1): `T006` (schemas test), `T007` (feature test), `T008` (`SearchDocsTool`), and `T009` (`GetDocTool`) can be written in parallel.
- Within Phase 4 (US2): `T012` (test), `T013` (infolists), and `T014` (widgets) can be developed in parallel.
- Within Phase 5 (US3): `T018` (test) and `T019` (infolist/widget checks) can run in parallel.
- Within Phase 6: `T022` (`README.md`) can run in parallel with syntax and validation checks.

---

## Implementation Strategy

### MVP First (User Story 1 Only)

1. Complete Phase 1: Setup (`tests/Unit/`, `tests/Feature/`).
2. Complete Phase 2: Foundational (`DocsRepository.php` and `DocsRepositoryTest.php`).
3. Complete Phase 3: User Story 1 (`SearchDocsTool.php`, `GetDocTool.php`, `DocsMcpToolsTest.php`).
4. **Validate MVP**: Prove that AI agents can search and read official documentation directly through MCP.

### Incremental Delivery

1. Setup + Foundational -> Documentation indexing engine ready.
2. User Story 1 -> AI Agent Documentation MCP Tooling functional (MVP!).
3. User Story 2 -> Comprehensive blueprint generation (Infolists, Widgets, Tenancy, Themes, Layouts).
4. User Story 3 -> Extended audit and review tooling.
5. Polish -> Documentation and quickstart validation.
