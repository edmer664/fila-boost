# Fila-boost: AI Architecture Planning & Code Review Toolkit for Filament v5.x

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

> [!IMPORTANT]
> **Independent Project Notice**: Fila-boost is an independently developed open-source community project. It is **not affiliated with, maintained by, sponsored by, or endorsed by the Filament core team**. Filament™ is a trademark of its respective owners.

Fila-boost is an open-source development toolkit and agent skill for Filament v5.x applications, natively designed for **Laravel Boost** and modern AI coding agents (Claude Code, Cursor, GitHub Copilot).

It empowers developers and AI agents to design, scaffold, and verify modular Filament architectures—ensuring clean separation of concerns, version-accurate APIs, and testable code before implementing features.

---

## What Fila-boost Does

Fila-boost accelerates administrative panel development in Filament v5.x by providing two core capabilities:

1. **Spec-First Architecture Planning (`planning-filament`)**:
   Generates comprehensive, self-contained Markdown architecture plans (`plans/{feature}.md`) defining Eloquent models, casts, policies, modular Filament v5 schemas, infolists, widgets, and Pest test specifications.
2. **Implementation Auditing & Verification (`reviewing-filament-plans`)**:
   Inspects the local application codebase against a generated plan to ensure strict adherence to Filament v5 modular patterns, required policies, tenant scoping, and test suites.

---

## Relationship to Filament Blueprint

[Filament Blueprint](https://filamentphp.com) is an official commercial planning product developed and maintained directly by the Filament core team, helping fund ongoing framework development. If you want an official first-party solution and wish to financially support Filament core development, we encourage you to explore [Filament Blueprint](https://filamentphp.com).

Fila-boost is an independent, open-source (MIT) community alternative tailored specifically for teams using Laravel Boost's Model Context Protocol (MCP) ecosystem and open agent workflows.

---

## Key Features

- **Native Laravel Boost Grounding**:
  - Leverages Laravel Boost's native `search-docs` MCP tool to fetch version-accurate Filament v5 documentation directly from the official docs index, preventing hallucinations.
- **Strict Filament v5.x Modular Architecture**:
  - Automatically structures resources into isolated classes: `Schemas/{Name}Form.php`, `Tables/{Name}sTable.php`, and `Infolists/{Name}Infolist.php`.
- **Full Filament Feature Coverage**:
  - **Infolists**: Modular schemas with `TextEntry`, `IconEntry`, `ImageEntry`, `Badge`, and `RepeatableEntry`.
  - **Dashboard & Page Widgets**: Pre-configured `StatsOverviewWidget`, chart widgets, and table widgets.
  - **Multi-Tenancy**: Tenant models (`Team` / `Company`), panel `->tenant()` configuration, and tenant-scoped query filters.
  - **Themes & Styling**: Custom color palettes (`Color::Amber`, `Color::Slate`), font definitions, and render hooks.
- **Ambiguity Detection**:
  - Identifies and highlights unresolved business decisions (status workflows, pricing precision, tenant boundaries) at the top of every generated plan.
- **Automated Verification**:
  - Audits local checkouts against generated plans to check for missing files, unmodularized schemas, or absent tests.
- **Open & Permissive**:
  - Distributed freely under the MIT License.

---

## Installation

Install `fila-boost/fila-boost` into your Laravel application:

```bash
composer require fila-boost/fila-boost --dev
```

Run the installer to register and link agent skills into `.agents/skills`:

```bash
php artisan fila-boost:install
```

---

## Usage with AI Coding Agents

### 1. Planning Features (`planning-filament`)

Prompt your AI coding agent (Claude Code, Cursor, Copilot) with Laravel Boost active:

> *"Using the planning-filament skill, create a Filament architecture plan for a multi-tenant client management portal with record infolists, revenue chart widget, and custom amber theme. Save to plans/client-portal.md."*

The agent produces a comprehensive architecture plan at `plans/client-portal.md`:
- Eloquent Models, Migrations, and Team Tenancy
- Filament v5 Resource: `App\Filament\Resources\Clients\ClientResource`
- Modular Form Schema: `App\Filament\Resources\Clients\Schemas\ClientForm.php`
- Modular Table Schema: `App\Filament\Resources\Clients\Tables\ClientsTable.php`
- Modular Infolist Schema: `App\Filament\Resources\Clients\Infolists\ClientInfolist.php`
- Revenue Chart Widget: `App\Filament\Widgets\ClientStatsOverviewWidget.php`
- Panel Theme Customization (`->colors(...)`)
- Authorization Policy: `App\Policies\ClientPolicy.php` with tenancy checks
- Pest PHP Test Suites: `tests/Feature/Filament/ClientsTest.php`

### 2. Reviewing the Implementation (`reviewing-filament-plans`)

Prompt your AI agent after implementation:

> *"Using the reviewing-filament-plans skill, review the current checkout against plans/client-portal.md."*

The agent audits your codebase against the plan, highlighting missing modular schemas, missing policies, or unwritten test suites.

---

## Testing

Run the test suite using Pest PHP:

```bash
vendor/bin/pest
```

Check code style with Laravel Pint:

```bash
vendor/bin/pint --test
```

---

## License

The MIT License (MIT). See [LICENSE](./LICENSE) for details.
