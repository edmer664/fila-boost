# Tasks: Filament v5.x Blueprint Agent Skill (Fila-boost)

**Feature**: Filament Blueprint Agent Skill (`001-filament-blueprint-skill`)  
**Input**: [spec.md](./spec.md), [plan.md](./plan.md), [data-model.md](./data-model.md), [contracts/](./contracts/), [research.md](./research.md)

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Package initialization, Composer definition, and development tooling.

- [X] T001 Initialize package structure with `src/`, `resources/skills/`, and `tests/` directories per plan.md
- [X] T002 Configure package manifest in `composer.json` for `fila-boost/filament-blueprint` requiring PHP >= 8.2, `laravel/framework` (^11.0 || ^12.0), `filament/filament` (^5.0), and dev dependencies `orchestra/testbench` and `pestphp/pest`
- [X] T003 [P] Add permissive open-source license in `LICENSE` (MIT) adhering to Constitution Principle V
- [X] T004 [P] Create package base test case in `tests/TestCase.php` extending Orchestra Testbench with `FilaBoostServiceProvider` registered

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core package service provider and base infrastructure that all user stories depend on.

- [X] T005 Implement `FilaBoostServiceProvider` in `src/FilaBoostServiceProvider.php` registering package configuration and commands
- [X] T006 [P] Implement markdown renderer core helper in `src/Support/BlueprintRenderer.php` supporting Filament v5.x modular structure templates
- [X] T007 [P] Implement implementation auditor core helper in `src/Support/ImplementationAuditor.php` checking code checkouts against blueprint requirements
- [X] T008 Unit test for `BlueprintRenderer` in `tests/Unit/BlueprintRendererTest.php` verifying markdown generation of Filament v5 modular classes

**Checkpoint**: Core package foundation and renderers ready. User story implementation can now begin.

---

## Phase 3: User Story 1 - Planning a Filament Feature via Agent Skill (Priority: P1) 🎯 MVP

**Goal**: Deliver the primary planning skill (`planning-filament`) and MCP tool (`generate_filament_blueprint`) that enables AI coding agents to plan self-contained Filament v5.x modular resources (with models, migrations, `Schemas/`, `Tables/`, `Pages/`, policies, and Pest tests).

**Independent Test**: Can be verified by invoking `GenerateBlueprintTool` with a feature description (e.g., "Customer invoicing with line items and payment status"), asserting that it outputs a structured Markdown blueprint containing exact namespaces, model casts, modular `Schemas/CustomerForm.php`, modular `Tables/CustomersTable.php`, policy rules, and Pest tests.

### Tests for User Story 1

- [X] T009 [P] [US1] Unit test for `generate_filament_blueprint` MCP tool schema and response in `tests/Unit/McpToolSchemaTest.php`
- [X] T010 [P] [US1] Feature test for end-to-end blueprint planning output in `tests/Feature/GenerateBlueprintTest.php`

### Implementation for User Story 1

- [X] T011 [P] [US1] Create Agent Skill definition in `resources/skills/planning-filament/SKILL.md` with YAML frontmatter (name `planning-filament`, version `1.0.0`, triggers) and system instructions enforcing Filament v5 modular architecture per `contracts/agent-skill-planning.md`
- [X] T012 [P] [US1] Implement MCP tool `GenerateBlueprintTool` in `src/Mcp/GenerateBlueprintTool.php` declaring MCP JSON schema parameters (`feature_description` required, `target_file` default "blueprints/{slug}.md", `filament_version` enum ["5.x"], `multi_tenancy` bool) matching `contracts/mcp-generate-blueprint.md`
- [X] T013 [US1] Implement blueprint generation logic in `src/Support/BlueprintRenderer.php` to render `EntityDefinition`, `FilamentV5ResourcePlan` (`Schemas/{Name}Form.php`, `Tables/{Name}sTable.php`), `AuthorizationPlan`, and `TestPlan` per `data-model.md`
- [X] T014 [US1] Add business ambiguity detection in `src/Support/BlueprintRenderer.php` that identifies unresolved decisions and flags them at the top of generated blueprints
- [X] T015 [US1] Integrate `GenerateBlueprintTool` into `src/FilaBoostServiceProvider.php` for Laravel Boost MCP discovery

**Checkpoint**: User Story 1 is functional. Agents can now plan complete, self-contained Filament v5.x features with modular architecture.

---

## Phase 4: User Story 2 - Boost MCP Skill Registration & Discovery (Priority: P2)

**Goal**: Deliver the package installer command (`php artisan fila-boost:install`) to publish and link agent skills into the project's `.agents/skills` or `.boost/skills` directory, ensuring zero-friction agent discovery.

**Independent Test**: Can be tested by running `php artisan fila-boost:install` in a test application environment and verifying that `planning-filament/SKILL.md` and `reviewing-filament-plans/SKILL.md` are copied or symlinked to the project's agent skills directory.

### Tests for User Story 2

- [X] T016 [P] [US2] Feature test for installer command in `tests/Feature/InstallSkillCommandTest.php` verifying skill publishing and directory creation

### Implementation for User Story 2

- [X] T017 [US2] Implement Artisan command `InstallSkillCommand` in `src/Commands/InstallSkillCommand.php` (`php artisan fila-boost:install`) to install skills into `.agents/skills` or `.boost/skills`
- [X] T018 [US2] Register `InstallSkillCommand` and asset publishing hooks in `src/FilaBoostServiceProvider.php`
- [X] T019 [US2] Add diagnostic checks in `src/Commands/InstallSkillCommand.php` to detect missing Boost dependencies or invalid permissions with actionable feedback

**Checkpoint**: User Story 2 is functional. Developers can install the package and have skills registered into Boost immediately.

---

## Phase 5: User Story 3 - Blueprint Implementation Review & Verification (Priority: P3)

**Goal**: Deliver the review agent skill (`reviewing-filament-plans`) and MCP tool (`review_filament_implementation`) to audit an application checkout against a generated blueprint for Filament v5.x compliance, security defaults, Pint formatting, and Pest tests.

**Independent Test**: Can be tested by running `ReviewImplementationTool` against sample checkouts (one fully conforming, one with missing modular `Schemas/` or missing policies), asserting that it returns accurate coverage scores and lists explicit discrepancies.

### Tests for User Story 3

- [X] T020 [P] [US3] Unit test for `review_filament_implementation` MCP tool schema and response in `tests/Unit/ReviewToolSchemaTest.php`
- [X] T021 [P] [US3] Feature test for codebase audit in `tests/Feature/ReviewImplementationTest.php` verifying detection of missing modular files or policy gaps

### Implementation for User Story 3

- [X] T022 [P] [US3] Create Agent Skill definition in `resources/skills/reviewing-filament-plans/SKILL.md` with YAML frontmatter and rules for auditing Filament v5.x modular classes and safety checks
- [X] T023 [P] [US3] Implement MCP tool `ReviewImplementationTool` in `src/Mcp/ReviewImplementationTool.php` declaring MCP JSON schema parameters (`blueprint_file` required, `run_pest_tests` default true, `run_pint_check` default true) matching `contracts/mcp-review-implementation.md`
- [X] T024 [US3] Implement blueprint parsing and structural auditing in `src/Support/ImplementationAuditor.php` to verify presence of `Schemas/{Name}Form.php`, `Tables/{Name}sTable.php`, policies, and test files
- [X] T025 [US3] Add automated Pint check and Pest test execution runner with safe process wrappers in `src/Support/ImplementationAuditor.php`
- [X] T026 [US3] Integrate `ReviewImplementationTool` into `src/FilaBoostServiceProvider.php` for Laravel Boost MCP discovery

**Checkpoint**: User Story 3 is functional. The full loop of planning, installing, and reviewing Filament v5 features is complete.

---

## Phase 6: Polish & Cross-Cutting Concerns

**Purpose**: Documentation, developer experience, and end-to-end verification.

- [X] T027 [P] Create comprehensive package documentation in `README.md` including installation, Boost integration, Filament v5 modular architecture examples, and license details
- [X] T028 [P] Configure default Laravel Pint rule preset in `pint.json` and ensure all package source code passes formatting
- [X] T029 Execute end-to-end walkthrough verifying scenarios described in `specs/001-filament-blueprint-skill/quickstart.md`

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies — can start immediately.
- **Foundational (Phase 2)**: Depends on Phase 1 completion — BLOCKS all user stories.
- **User Story 1 (Phase 3 - P1)**: Depends on Phase 2 completion. Delivers core MVP.
- **User Story 2 (Phase 4 - P2)**: Depends on Phase 2 completion and skill assets from Phase 3.
- **User Story 3 (Phase 5 - P3)**: Depends on Phase 2 completion and blueprint structure established in Phase 3.
- **Polish (Phase 6)**: Depends on all user stories (Phases 3-5) being complete.

### User Story Dependencies

- **User Story 1 (P1)**: Independent of US2 and US3.
- **User Story 2 (P2)**: Uses the `resources/skills/` directory created in US1 and US3 for copying/symlinking.
- **User Story 3 (P3)**: Audits the blueprint structure produced by US1; can proceed once US1's blueprint format is finalized.

### Parallel Opportunities

- Within Phase 1: `T003` (LICENSE) and `T004` (TestCase) can run in parallel.
- Within Phase 2: `T006` (`BlueprintRenderer`) and `T007` (`ImplementationAuditor`) can be developed in parallel.
- Within Phase 3 (US1): `T009` (schema test), `T010` (feature test), `T011` (`SKILL.md`), and `T012` (`GenerateBlueprintTool`) can start in parallel.
- Within Phase 4 (US2): `T016` (installer test) can be written in parallel with commands.
- Within Phase 5 (US3): `T020` (schema test), `T021` (feature test), `T022` (`SKILL.md`), and `T023` (`ReviewImplementationTool`) can run in parallel.
- Within Phase 6: `T027` (`README.md`) and `T028` (`pint.json`) can run in parallel.

---

## Implementation Strategy

### MVP First (User Story 1 Only)

1. Complete Phase 1: Setup (`composer.json`, `LICENSE`, `tests/TestCase.php`).
2. Complete Phase 2: Foundational (`FilaBoostServiceProvider.php`, base renderers).
3. Complete Phase 3: User Story 1 (`planning-filament/SKILL.md`, `GenerateBlueprintTool.php`, `BlueprintRenderer.php`).
4. **Validate MVP**: Run unit and feature tests (`vendor/bin/pest`) to verify that the planning skill generates complete Filament v5 modular blueprints with `Schemas/` and `Tables/` classes.

### Incremental Delivery

1. Setup + Foundational -> Package structure established.
2. User Story 1 -> Core planning capability and MCP tool working (MVP).
3. User Story 2 -> Artisan installer (`fila-boost:install`) for zero-friction Boost discovery.
4. User Story 3 -> Post-implementation auditing tool and review skill.
5. Polish -> Documentation (`README.md`), Pint linting, and quickstart validation.
