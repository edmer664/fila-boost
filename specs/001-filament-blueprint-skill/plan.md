# Implementation Plan: Filament v5.x Blueprint Agent Skill (Fila-boost)

**Branch**: `001-filament-blueprint-skill` | **Date**: 2026-10-08 | **Spec**: [spec.md](./spec.md)

**Input**: Feature specification from `specs/001-filament-blueprint-skill/spec.md` (Updated with Filament v5.x Modular Clarification)

## Summary

Build `fila-boost/filament-blueprint`, an open-source (MIT), free alternative to proprietary Filament blueprint tools. The package provides a lightweight spec-first Filament planning skill natively integrated with Laravel Boost and AI coding agents (Claude Code, Cursor, GitHub Copilot). It strictly enforces **Filament v5.x modular architecture**, generating blueprints that separate resource definitions into modular classes (`CustomerResource.php`, `Schemas/CustomerForm.php`, `Tables/CustomersTable.php`, and `Pages/`). It includes self-contained agent skill definitions (`planning-filament`, `reviewing-filament-plans`), MCP tool schemas for programmatic blueprint generation and review, and an Artisan installer command. All outputs adhere to official Filament v5 standards, secure-by-default rules, and pass strict quality gates (Pest and Pint).

## Technical Context

**Language/Version**: PHP >= 8.2

**Primary Dependencies**: `laravel/framework` (^11.0 || ^12.0), `filament/filament` (^5.0), `laravel/boost` (MCP integration)

**Storage**: Local files (`blueprints/*.md`, `.agents/skills/*`)

**Testing**: Pest PHP (`pestphp/pest` ^2.0 || ^3.0)

**Target Platform**: Cross-platform (Linux, macOS, Windows) inside Laravel applications

**Project Type**: Laravel Package / Composer Library / Agent Skills Suite

**Performance Goals**: Generate complete blueprints in < 60 seconds; 0 external runtime latency

**Constraints**: MIT licensed; zero proprietary license keys or external auth; strictly adheres to Model Context Protocol (MCP) tool schemas and Agent Skills specification format, enforcing Filament v5.x modular layouts

**Scale/Scope**: Covers core administrative features (Models, Migrations, Filament v5 Resources, Modular Schemas, Modular Tables, Infolists, Actions, Clusters, Policies, Pest Tests)

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

- **Principle I (Spec-First & Lightweight Agility)**: **PASS** — Standardized self-contained Markdown blueprints (`blueprints/{feature}.md`) focus on clear requirements and rapid iteration without bureaucratic overhead.
- **Principle II (Full Laravel Boost & AI Compatibility)**: **PASS** — Native Agent Skills format (`SKILL.md`) and standard MCP JSON Schemas integrated with Laravel Boost.
- **Principle III (Official Filament Standards & Safe Defaults)**: **PASS** — Strictly enforces Filament v5.x modular architecture (`Schemas/`, `Tables/`), fully qualified namespaces, official component APIs, and mandatory Laravel Policies for authorization.
- **Principle IV (Rigorous Quality & Verification Gates)**: **PASS** — Definition of done requires passing Pest tests, Pint formatting, and proven agent reproducibility.
- **Principle V (Free, Open Source & Frictionless DX)**: **PASS** — MIT license, standard Composer distribution, zero license server requirements.

## Project Structure

### Documentation (this feature)

```text
specs/001-filament-blueprint-skill/
├── plan.md              # This file
├── research.md          # Phase 0 output
├── data-model.md        # Phase 1 output
├── quickstart.md        # Phase 1 output
├── contracts/           # Phase 1 output
│   ├── mcp-generate-blueprint.md
│   ├── mcp-review-implementation.md
│   └── agent-skill-planning.md
└── tasks.md             # Phase 2 output (/speckit-tasks command)
```

### Source Code (repository root)

```text
fila-boost/
├── composer.json
├── LICENSE
├── README.md
├── src/
│   ├── Commands/
│   │   └── InstallSkillCommand.php       # php artisan fila-boost:install
│   ├── FilaBoostServiceProvider.php      # Package service provider
│   ├── Mcp/
│   │   ├── GenerateBlueprintTool.php     # MCP tool implementation
│   │   └── ReviewImplementationTool.php  # MCP tool implementation
│   └── Support/
│       ├── BlueprintRenderer.php         # Generates Markdown blueprint with v5 modular structure
│       └── ImplementationAuditor.php     # Reviews v5 modular code against blueprint
├── resources/
│   └── skills/
│       ├── planning-filament/
│       │   └── SKILL.md                  # Planning agent skill definition (Filament v5 modular)
│       └── reviewing-filament-plans/
│           └── SKILL.md                  # Review agent skill definition (Filament v5 modular)
└── tests/
    ├── Feature/
    │   └── InstallSkillCommandTest.php
    ├── Unit/
    │   ├── BlueprintRendererTest.php
    │   └── McpToolSchemaTest.php
    └── TestCase.php
```

**Structure Decision**: Standard modern Laravel Composer package layout. The package encapsulates the service provider, Artisan installer, MCP tool definitions, and bundled `SKILL.md` assets in `resources/skills/`.

## Complexity Tracking

*No violations to record. Architecture uses minimal, standard Laravel package components.*
