# Phase 0 Research: Filament v5.x Blueprint Agent Skill (Fila-boost)

## Context & Objectives
Fila-boost is an open-source, MIT-licensed alternative to proprietary Filament blueprint tools. It provides reusable agent skills and Model Context Protocol (MCP) tooling natively integrated with Laravel Boost.

Following the clarification session, Fila-boost enforces **Filament v5.x modular architecture**, generating blueprints and review checks aligned with v5 standards.

---

### Research Item 1: Filament v5.x Architectural Patterns

- **Decision**: All generated blueprints, planning guidelines, and review tools MUST adhere to Filament v5.x conventions:
  1. **Modular Directory Structure**: Resources are scaffolded in directory namespaces:
     ```text
     app/Filament/Resources/Customers/
     ├── CustomerResource.php
     ├── Pages/
     │   ├── ListCustomers.php
     │   ├── CreateCustomer.php
     │   ├── EditCustomer.php
     │   └── ViewCustomer.php
     ├── Schemas/
     │   └── CustomerForm.php       # Extends Schema configuration
     └── Tables/
         └── CustomersTable.php     # Extends Table configuration
     ```
  2. **Schema & Form Configuration**:
     - Uses `Filament\Schemas\Schema` rather than legacy form closures.
     - Defined via static configure method: `CustomerForm::configure(Schema $schema): Schema`.
     - Layout components imported from `Filament\Schemas\Components\...` or `Filament\Forms\Components\...`.
  3. **Table Configuration**:
     - Defined via static configure method: `CustomersTable::configure(Table $table): Table`.
     - Action groupings use `recordActions([...])` and `toolbarActions([...])` with actions from `Filament\Actions\...`.
- **Rationale**: Official Filament v5 documentation establishes this modular architecture as the standard to avoid bloated 500+ line monolithic Resource classes. Implementing agents guided by v5 conventions will write clean, scalable code.
- **Alternatives Considered**:
  - *Filament v3 inline form/table closures*: Rejected based on user clarification specifying strict Filament v5.x adherence.

---

### Research Item 2: Boost MCP Integration & Skill Architecture

- **Decision**: Structure Fila-boost as a standard Laravel Composer package (`fila-boost/filament-blueprint`) providing:
  1. An Agent Skill directory containing `SKILL.md` formatted to Agent Skills / Boost specifications (`planning-filament` and `reviewing-filament-plans`).
  2. An Artisan registration/installer command (`php artisan fila-boost:install`) that copies/symlinks the agent skill definitions into the project's recognized `.agents/skills` or `.boost/skills` directory, ensuring seamless zero-friction discovery by agents like Claude Code, Cursor, and Copilot.
  3. PHP classes implementing MCP tool schema payloads (JSON Schema compliant) exposing a callable blueprint generation and review interface.
- **Rationale**: Laravel Boost detects skills installed in the project root or vendor configurations. Providing standard `SKILL.md` modules ensures immediate compatibility without needing custom binary daemons or proprietary license servers.
- **Alternatives Considered**:
  - *Standalone Node.js/Python MCP server*: Rejected because Laravel Boost already runs an MCP server within the Laravel ecosystem.

---

### Research Item 3: Self-Contained Blueprint Specification for Filament v5

- **Decision**: Define a standardized, self-contained Markdown blueprint specification (`blueprints/{feature}.md`) structured in unambiguous sections:
  1. **Overview & Business Flows**: High-level capability and state transitions; unresolved business questions prominently flagged at the top.
  2. **Domain & Data Model**: Eloquent models, table name, migrations, primary keys, fillable attributes, cast definitions, relationships (with foreign keys and cascade rules), and PHP 8.2+ backed enums (`HasLabel`, `HasColor`).
  3. **Filament v5 Resource Configuration**: Resource class with fully qualified namespace (`App\Filament\Resources\Customers\CustomerResource`), navigation group, icon, cluster, `$recordTitleAttribute`, and associated page classes.
  4. **Modular Form Schemas**: `Schemas\{Resource}Form.php` using `Schema` and `configure(Schema $schema)`, layout components (`Section`, `Grid`), exact component types (`TextInput`, `Select`, `DatePicker`, `Repeater`), validations, and reactive hooks (`live()`).
  5. **Modular Table Schemas**: `Tables\{Resource}Table.php` using `Table` and `configure(Table $table)`, column types (`TextColumn`, `IconColumn`), searchability, sortability, filters (`Filter`, `SelectFilter`), `recordActions`, and `toolbarActions`.
  6. **Authorization & Policies**: Dedicated Policy mapping (`App\Policies\...`), gate checks (`viewAny`, `create`, `update`, `delete`), and tenant ownership scoping where applicable.
  7. **Pest Test Specifications**: Complete Pest PHP test cases testing resource rendering, form validation, table sorting/filtering, and action authorization.
- **Rationale**: Preserves agent context windows and eliminates circular prompting where the implementing agent tries to guess namespace structures or uses obsolete v3 method APIs.

---

### Research Item 4: Reviewing & Verification Pipeline

- **Decision**:
  - Review skill audits whether files exist at `app/Filament/Resources/{Cluster?}/{Name}/` with proper `Schemas/` and `Tables/` classes.
  - Verification Gates: Automatic verification runs `vendor/bin/pint --test` and `vendor/bin/pest` on target codebases to confirm zero regression and proper syntax.
- **Rationale**: Complies directly with Constitution Principle IV (Done criteria = Pest tests + Pint formatting + agent reproducibility).
