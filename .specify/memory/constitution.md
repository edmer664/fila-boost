<!--
Sync Impact Report:
- Version change: Unversioned / Initial scaffold → 1.0.0
- List of modified principles:
  - PRINCIPLE_1: "I. Spec-First & Lightweight Agility" (established)
  - PRINCIPLE_2: "II. Full Laravel Boost & AI Agent Ecosystem Compatibility" (established)
  - PRINCIPLE_3: "III. Official Filament Standards & Safe Defaults" (established)
  - PRINCIPLE_4: "IV. Rigorous Quality & Verification Gates" (established)
  - PRINCIPLE_5: "V. Free, Open Source (MIT) & Frictionless Developer Experience" (established)
- Added sections:
  - Technical Constraints & Security Standards
  - Agent Operational Protocol & MCP Architecture
- Removed sections:
  - Generic template placeholder sections
- Follow-up TODOs:
  - None (all initial governance parameters concrete)
-->

# Filament Blueprint Constitution

## Core Principles

### I. Spec-First & Lightweight Agility
Every feature and iteration MUST begin with a lightweight specification that defines expected behaviors, component hierarchy, and explicit acceptance criteria before prompting or coding. The specification MUST prioritize working, deliverable functionality over rigid, bureaucratic overhead. Plans MUST be unambiguous, concise, and focused on enabling rapid feedback loops.

### II. Full Laravel Boost & AI Agent Ecosystem Compatibility
All generated blueprint artifacts, tooling interfaces, agent skills, and prompt templates MUST maintain first-class compatibility with Laravel Boost, valid MCP (Model Context Protocol) standards, and leading AI coding agents (such as Claude Code, Cursor, and GitHub Copilot). Tools and schemas exposed to agents MUST strictly conform to expected MCP tool definitions, minimizing context window footprint while maximizing precision.

### III. Official Filament Standards & Safe Defaults
Generated code structures, resource scaffolding, form schemas, table definitions, actions, infolists, and widgets MUST strictly adhere to official Filament conventions and best practices. Architectural patterns MUST enforce secure-by-default principles, including strict authorization policies, validated input schemas, scoped database queries, tenant isolation where applicable, and safe mass-assignment protections.

### IV. Rigorous Quality & Verification Gates
A task is considered "done" ONLY when it satisfies three non-negotiable verification gates:
1. **Testing**: All relevant Pest PHP automated test suites pass without regression.
2. **Code Style**: Code adheres cleanly to Laravel and Filament standards via Laravel Pint formatting.
3. **Agent Reproducibility**: The blueprint and associated instructions successfully guide an AI coding agent to implement clean, working, idiomatic Filament code on the target application checkout.

### V. Free, Open Source (MIT) & Frictionless Developer Experience
Filament Blueprint is and MUST remain completely free and licensed under the permissive MIT License. Architectures, packages, and skills MUST NOT impose proprietary licensing barriers or vendor lock-in. Installation, configuration, and consumption workflows MUST remain frictionless, adhering to standard Composer and Artisan workflows.

## Technical Constraints & Security Standards

- **Licensing & Distribution**: Released strictly under the MIT License; dependencies must be compatible with permissive open-source distribution.
- **Ecosystem Grounding**: Fully aligned with modern PHP (>= 8.2), Laravel (>= 11.x), and current/supported Filament versions.
- **MCP & Tooling Safety**: All MCP tool endpoints and agent-callable functions MUST implement safe read-only defaults for inspection and enforce explicit authorization and guardrails on mutation operations.
- **Data Protection & Policies**: Blueprint specifications MUST define explicit authorization policies (e.g., Laravel Gate/Policy integration) for every Filament resource and page generated.

## Agent Operational Protocol & MCP Architecture

- **Separation of Concerns**: Planning skills (e.g., blueprint generation) MUST remain decoupled from implementation runtime contexts to preserve agent context windows.
- **Self-Contained Blueprints**: Generated blueprints MUST capture complete specifications (fully qualified class names, exact namespaces, component hierarchies, validation rules, relationship configurations, and test expectations) so that implementing agents operate without ambiguity.
- **Feedback & Review Cycle**: Iterations follow a structured cycle: lightweight spec generation → user/agent alignment → implementation → verification via Pest & Pint → review against acceptance criteria.

## Governance

- **Supremacy & Compliance**: This constitution supersedes all contradictory ad-hoc coding patterns or informal project habits. All pull requests, code contributions, and AI-generated blueprints MUST demonstrate compliance with these core principles.
- **Amendment Procedure**: Amendments to this constitution require a documented rationale, evaluation of impact on existing blueprint specifications and agent workflows, and a formal version increment.
- **Versioning Policy**:
  - **MAJOR (X.0.0)**: Removal, fundamental alteration, or breaking changes to core principles, security defaults, or licensing terms.
  - **MINOR (x.Y.0)**: Addition of new principles, sections, architectural boundaries, or materially expanded governance rules.
  - **PATCH (x.y.Z)**: Clarifications, non-semantic wording refinements, typo corrections, or formatting adjustments.

**Version**: 1.0.0 | **Ratified**: 2026-10-08 | **Last Amended**: 2026-10-08
