# Boost MCP Tool Contract: `search_filament_docs`

## Overview
Allows AI coding agents to search the bundled Filament documentation repository on-demand, returning relevant guide excerpts, categories, and topic slugs.

## Tool Definition Schema (MCP JSON Schema)

```json
{
  "name": "search_filament_docs",
  "description": "Searches the bundled official Filament v5 documentation repository for guides, components, styling, widgets, infolists, tenancy, and testing patterns. Returns matched topics with summaries and relative paths.",
  "parameters": {
    "type": "object",
    "properties": {
      "query": {
        "type": "string",
        "description": "Search keyword or phrase (e.g., 'infolist', 'stats widget', 'multi-tenancy', 'custom theme colors', 'render hooks')."
      },
      "category": {
        "type": "string",
        "description": "Optional category filter (e.g., 'components', 'styling', 'resources', 'advanced', 'testing', 'plugins')."
      },
      "limit": {
        "type": "integer",
        "description": "Maximum number of results to return (default: 5, max: 20).",
        "default": 5
      }
    },
    "required": ["query"]
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
      "enum": ["success", "not_found"]
    },
    "query": { "type": "string" },
    "total_matches": { "type": "integer" },
    "results": {
      "type": "array",
      "items": {
        "type": "object",
        "properties": {
          "slug": { "type": "string" },
          "title": { "type": "string" },
          "category": { "type": "string" },
          "relative_path": { "type": "string" },
          "snippet": { "type": "string" }
        },
        "required": ["slug", "title", "relative_path", "snippet"]
      }
    }
  },
  "required": ["status", "query", "results"]
}
```
