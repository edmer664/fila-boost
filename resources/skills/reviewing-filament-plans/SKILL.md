---
name: reviewing-filament-plans
description: "Reviews the current application repository checkout against a previously generated Filament Blueprint, checking adherence to Filament v5.x modular structure (Schemas/, Tables/, and Infolists/ classes), widgets, multi-tenancy scoping, authorization policies, and test suites."
version: 1.1.0
triggers:
  - "review filament"
  - "verify filament"
  - "audit filament blueprint"
  - "filament blueprint review"
---

# Filament v5.x Review Skill (`reviewing-filament-plans`)

When invoked, the AI coding agent MUST evaluate an existing application codebase checkout against a saved Filament v5.x Blueprint document.

## Auditing Rules

1. **Filament v5.x Modular Directory Compliance**:
   - Verify that Resource classes do NOT declare monolithic inline form, table, or infolist closures.
   - Confirm presence of `app/Filament/Resources/{Cluster?}/{Name}s/Schemas/{Name}Form.php`.
   - Confirm presence of `app/Filament/Resources/{Cluster?}/{Name}s/Tables/{Name}sTable.php`.
   - If an infolist was planned, confirm presence of `app/Filament/Resources/{Cluster?}/{Name}s/Infolists/{Name}Infolist.php`.

2. **Widgets & Dashboards**:
   - Verify that planned widgets (`StatsOverviewWidget`, charts, tables) exist in `app/Filament/Widgets/` or resource subdirectories.

3. **Multi-Tenancy Scoping**:
   - If multi-tenancy is planned, confirm the Tenant model (`Team` or `Company`) exists and that panel routing configures `->tenant()`.
   - Verify scoped models possess tenant relationship foreign keys (`team_id`).

4. **Security & Authorization Defaults**:
   - Confirm Model Policies exist (`app/Policies/{Name}Policy.php`) with explicit gate abilities (`viewAny`, `create`, `update`, `delete`).
   - If multi-tenancy is active, verify policies check tenant ownership.

5. **Pest Tests & Quality Gates**:
   - Ensure corresponding test suites exist in `tests/Feature/Filament/`.
   - Require automated verification via `vendor/bin/pint --test` and `vendor/bin/pest`.
