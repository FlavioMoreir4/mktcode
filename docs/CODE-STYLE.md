# Code Style – MKT Code

This document defines the code style rules used in the project.

---

## Philosophy

Code should be:

- Readable
- Predictable
- Consistent
- Easy to refactor

---

## PHP

Rules:

- Follow PSR‑12
- Use strict types
- No business logic inside controllers
- No large models

Tools used:

- Laravel Pint
- PHPStan
- Rector

---

## Naming Conventions

### Classes

Use clear business names.

Example:

```
PublishPostCommand
CreateInquiryCommand
FeatureProjectCommand
```

---

### Methods

Methods must describe behavior.

Good:
```
publish()
archive()
markAsFeatured()
```

Bad:
```
handle()
doStuff()
process()
```

---

## Controllers

Controllers must be thin.

Responsibilities:

- Validate request
- Call command/query
- Return response

---

## Vue

Vue components should be:

- Small
- Focused
- Reusable

---

## Goal

The goal of this document is to keep the codebase consistent as it grows.
