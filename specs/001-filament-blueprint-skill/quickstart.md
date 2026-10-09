# Quickstart & Validation Guide: Filament v5.x Planning & Verification Skill (Fila-boost)

## Prerequisites
- PHP >= 8.2 with standard extensions (pdo, mbstring, tokenizer)
- Composer installed
- A Laravel 11+ application with `laravel/boost` and `filament/filament` v5.x installed
- Pest PHP and Laravel Pint configured

---

## 1. Package Installation & Skill Setup

In your Laravel project root:

```bash
# 1. Require Fila-boost as a development dependency
composer require fila-boost/fila-boost --dev

# 2. Run the Fila-boost installer to link skills into Boost
php artisan fila-boost:install
```

**Verification Check**:
Confirm that `planning-filament/SKILL.md` and `reviewing-filament-plans/SKILL.md` exist in your project's `.agents/skills` or `.boost/skills` directory:

```bash
ls .agents/skills/planning-filament/SKILL.md
```

---

## 2. Planning a Feature (End-to-End Validation)

Instruct your AI coding agent (e.g. Claude Code, Cursor, Copilot) with planning mode enabled:

> *"Using the planning-filament skill, create a Filament architecture plan for a customer feedback and bug report management system adhering to Filament v5.x modular architecture. Write the plan to plans/feedback-management.md."*

**Expected Result**:
The agent outputs a structured Markdown file at `plans/feedback-management.md` containing:
- High-level business flow and unresolved decisions.
- Eloquent models (`FeedbackReport`, `FeedbackCategory`) with migrations, relationships, casts, and enums.
- Filament v5 Resource (`App\Filament\Resources\FeedbackReports\FeedbackReportResource`) referencing modular:
  - Form schema: `App\Filament\Resources\FeedbackReports\Schemas\FeedbackReportForm.php`
  - Table schema: `App\Filament\Resources\FeedbackReports\Tables\FeedbackReportsTable.php`
- Pages: `ListFeedbackReports`, `CreateFeedbackReport`, `EditFeedbackReport`.
- Authorization policy (`FeedbackReportPolicy`).
- Pest test scenarios for testing the form schema and table actions.

---

## 3. Implementing the Plan

Switch out of planning mode and instruct the implementing agent:

> *"Implement plans/feedback-management.md in this application checkout. Follow the plan and run the relevant tests."*

The agent reads the plan file directly and writes the models, migrations, policies, Filament v5 modular classes, and Pest tests.

---

## 4. Reviewing the Implementation

Prompt your AI agent:

> *"Using the reviewing-filament-plans skill, review the current checkout against plans/feedback-management.md."*

**Verification Quality Gate**:
1. Run Pint:
   ```bash
   vendor/bin/pint --test
   ```
2. Run Pest:
   ```bash
   vendor/bin/pest
   ```
Both commands must pass with 0 errors.
