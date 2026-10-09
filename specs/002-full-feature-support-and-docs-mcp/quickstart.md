# Quickstart & Validation Guide: Full Filament Coverage & Documentation MCP Tooling

## Prerequisites
- PHP >= 8.2
- Composer
- `fila-boost/fila-boost` package installed
- Laravel application with `laravel/boost` or compatible MCP client

---

## 1. Documentation Querying via MCP

### Search Documentation (`search_filament_docs`)
In your agent session or MCP inspector, execute:

```json
{
  "tool": "search_filament_docs",
  "arguments": {
    "query": "infolist entries"
  }
}
```

**Expected Outcome**: Returns matched topics including `12-components/02-infolist.md` with snippets explaining `TextEntry`, `IconEntry`, and layout components.

### Read Complete Document (`get_filament_doc`)
Execute:

```json
{
  "tool": "get_filament_doc",
  "arguments": {
    "topic_id": "12-components/02-infolist"
  }
}
```

**Expected Outcome**: Returns the full markdown content of the official Infolist component documentation.

---

## 2. Planning Complex Features (Infolists, Widgets, Tenancy, Themes)

Instruct the AI agent:

> *"Using the planning-filament skill, create an architecture plan for a multi-tenant Agency Client Portal featuring a customer infolist view, monthly revenue chart widget, custom slate/amber brand theme, and tenant isolation. Save to plans/agency-portal.md."*

**Expected Outcome**:
The agent writes `plans/agency-portal.md` including:
- Tenant model (`Agency`) and scoping rules.
- Panel theme configuration (`colors()`, `font()`).
- Modular Infolist schema: `App\Filament\Resources\Clients\Infolists\ClientInfolist.php`.
- Revenue Chart Widget: `App\Filament\Widgets\RevenueChartWidget.php`.
- Model policies and Pest test specifications.

---

## 3. Auditing the Implementation

Run the review tool against the generated plan:

```json
{
  "tool": "review_filament_implementation",
  "arguments": {
    "plan_file": "plans/agency-portal.md"
  }
}
```

**Expected Outcome**:
Reports status and coverage, auditing whether the modular infolists, widgets, and tenant-scoped policies are present in the checkout.
