# Boost MCP Tool Contract: `generate_filament_blueprint`

## Overview
Exposes the planning capability to AI coding agents via the Model Context Protocol (MCP) embedded in Laravel Boost, tailored for Filament v5.x.

## Tool Definition Schema (MCP JSON Schema)

```json
{
  "name": "generate_filament_blueprint",
  "description": "Generates a structured, self-contained Filament v5.x Blueprint implementation plan (Markdown) for an administrative feature, including models, migrations, Filament v5 modular resources (CustomerResource, Schemas/CustomerForm, Tables/CustomersTable), policies, and Pest tests.",
  "parameters": {
    "type": "object",
    "properties": {
      "feature_description": {
        "type": "string",
        "description": "Detailed natural language description of the Filament administrative feature to plan (e.g., 'Customer invoicing with line items, tax calculation, and payment status tracking')."
      },
      "target_file": {
        "type": "string",
        "description": "Relative path where the generated blueprint Markdown should be saved (default: 'blueprints/{slug}.md')."
      },
      "filament_version": {
        "type": "string",
        "description": "Target Filament version. Strict Filament v5.x enforced.",
        "enum": ["5.x"],
        "default": "5.x"
      },
      "multi_tenancy": {
        "type": "boolean",
        "description": "Whether the feature requires multi-tenancy isolation and tenant ownership scoping.",
        "default": false
      }
    },
    "required": ["feature_description"]
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
      "enum": ["success", "error"]
    },
    "blueprint_file": {
      "type": "string",
      "description": "Project relative path where blueprint was written."
    },
    "title": {
      "type": "string",
      "description": "Feature title extracted from description."
    },
    "filament_version": {
      "type": "string",
      "description": "Framework version targeting Filament v5.x."
    },
    "unresolved_decisions": {
      "type": "array",
      "items": { "type": "string" },
      "description": "List of flagged business questions requiring human confirmation."
    },
    "entities_detected": {
      "type": "array",
      "items": { "type": "string" },
      "description": "List of Eloquent models planned."
    },
    "resources_planned": {
      "type": "array",
      "items": { "type": "string" },
      "description": "List of Filament v5 resources specified with modular Schemas and Tables."
    },
    "markdown_content": {
      "type": "string",
      "description": "Full Markdown text of the generated blueprint."
    }
  },
  "required": ["status", "blueprint_file", "markdown_content"]
}
```
