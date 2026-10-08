# Boost MCP Tool Contract: `review_filament_implementation`

## Overview
Exposes the post-implementation review capability to AI coding agents via the Model Context Protocol (MCP) in Laravel Boost, verifying compliance with Filament v5.x modular standards.

## Tool Definition Schema (MCP JSON Schema)

```json
{
  "name": "review_filament_implementation",
  "description": "Reviews the current application repository checkout against a previously generated Filament Blueprint, checking adherence to Filament v5.x modular structure (Schemas/ and Tables/ classes), validation rules, authorization policies, and test suites.",
  "parameters": {
    "type": "object",
    "properties": {
      "blueprint_file": {
        "type": "string",
        "description": "Relative path to the blueprint markdown file (e.g., 'blueprints/invoicing.md')."
      },
      "run_pest_tests": {
        "type": "boolean",
        "description": "Whether to execute Pest tests to verify implementation.",
        "default": true
      },
      "run_pint_check": {
        "type": "boolean",
        "description": "Whether to verify code style using Laravel Pint.",
        "default": true
      }
    },
    "required": ["blueprint_file"]
  }
}
```

## Response Schema

```json
{
  "type": "object",
  "properties": {
    "status": {
      "type": "string",
      "enum": ["passed", "failed_verification", "error"]
    },
    "coverage_score": {
      "type": "number",
      "description": "Percentage (0-100) of blueprint requirements fulfilled in codebase."
    },
    "checks": {
      "type": "object",
      "properties": {
        "models_and_migrations": { "type": "boolean" },
        "filament_v5_modular_structure": { "type": "boolean" },
        "form_schemas": { "type": "boolean" },
        "table_schemas": { "type": "boolean" },
        "authorization_policies": { "type": "boolean" },
        "pest_tests": { "type": "boolean" },
        "pint_formatting": { "type": "boolean" }
      }
    },
    "discrepancies": {
      "type": "array",
      "items": { "type": "string" },
      "description": "List of missing or mismatched Filament v5 classes, methods, or components."
    },
    "recommendations": {
      "type": "array",
      "items": { "type": "string" },
      "description": "Concrete code changes needed to satisfy the blueprint."
    }
  },
  "required": ["status", "coverage_score", "checks", "discrepancies"]
}
```
