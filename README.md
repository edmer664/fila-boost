# Fila-boost: Filament v5.x Blueprint Agent Skills for Laravel Boost

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Fila-boost is a 100% free, open-source (MIT) alternative to proprietary Filament blueprint tools. It provides reusable agent skills and Model Context Protocol (MCP) tooling natively designed for **Laravel Boost** and leading AI coding agents (Claude Code, Cursor, GitHub Copilot).

Fila-boost supports the **entire Filament feature suite**—Resources, Modular Forms, Tables, Infolists, Dashboard Widgets, Multi-Tenancy, Themes/Styling, and Advanced Layouts—grounded with **Laravel Boost's native `search-docs`** tool for unhallucinated, version-accurate AI generation.

---

## Key Features

- **Native Laravel Boost Grounding**:
  - Leverages Laravel Boost's native `search-docs` MCP tool to fetch up-to-date Filament v5 documentation directly from the vendor ecosystem.
- **Full Filament Feature Coverage**:
  - **Infolists**: Modular schemas (`Infolists/{Name}Infolist.php`) with text, icon, image, badge, and repeatable entries.
  - **Widgets**: Pre-configured `StatsOverviewWidget`, Chart widgets, and Table widgets.
  - **Multi-Tenancy**: Tenant models (`Team` / `Company`), panel `->tenant()` binding, and tenant-scoped query filters.
  - **Themes & Styling**: Panel custom color palettes (`Color::Amber`, `Color::Slate`), font definitions, CSS hooks, and render hooks.
  - **Filament v5.x Modular Architecture**: Resources separate schemas into `Schemas/{Name}Form.php` and `Tables/{Name}sTable.php`.
- **Spec-First Planning**: Generates self-contained Markdown blueprints (`blueprints/{feature}.md`) with exact namespaces, models, casts, policies, and Pest tests.
- **Ambiguity Detection**: Prominently flags unresolved business and workflow decisions.
- **Implementation Auditing**: Audits checkouts against blueprints to verify structural compliance, policies, widgets, and tests.
- **Zero Proprietary Lock-In**: Released under the permissive MIT License.

---

## Installation

Install `fila-boost/filament-blueprint` into your Laravel application:

```bash
composer require fila-boost/filament-blueprint --dev
```

Run the installer to register and link agent skills into `.agents/skills`:

```bash
php artisan fila-boost:install
```

---

## Usage with AI Coding Agents

### 1. Planning Comprehensive Features (`planning-filament`)

Prompt your AI coding agent (Claude Code, Cursor, Copilot) with Laravel Boost active:

> *"Using the planning-filament skill, create a Filament Blueprint for a multi-tenant client management portal with record infolists, revenue chart widget, and custom amber theme. Save to blueprints/client-portal.md."*

The agent produces a comprehensive blueprint at `blueprints/client-portal.md`:
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

Prompt your AI agent:

> *"Using the reviewing-filament-plans skill, review the current checkout against blueprints/client-portal.md."*

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
