# Boost MCP Tool Contract: `get_filament_doc`

## Overview
Retrieves the full markdown body of an indexed Filament documentation guide or component reference by slug or relative path.

## Tool Definition Schema (MCP JSON Schema)

```json
{
  "name": "get_filament_doc",
  "description": "Retrieves the complete markdown content of a bundled official Filament documentation guide by topic slug or file path.",
  "parameters": {
    "type": "object",
    "properties": {
      "topic_id": {
        "type": "string",
        "description": "The documentation topic slug or relative path (e.g., '12-components/02-infolist', '08-styling/03-colors', 'infolist', or '09-advanced/05-modular-architecture')."
      }
    },
    "required": ["topic_id"]
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
    "slug": { "type": "string" },
    "title": { "type": "string" },
    "category": { "type": "string" },
    "relative_path": { "type": "string" },
    "content": {
      "type": "string",
      "description": "Complete Markdown content of the documentation article."
    }
  },
  "required": ["status", "slug", "content"]
}
```
