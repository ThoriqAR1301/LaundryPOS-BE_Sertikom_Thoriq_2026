Title: chore(swagger): stabilize generation, add CI validation and improve OpenAPI docs

Summary:

- Stabilize `l5-swagger` generation by ensuring docblock parsing works and avoiding duplicate/invalid annotations.
- Add CI workflow to automatically generate and validate OpenAPI JSON on push.
- Fix workflow PHP setup so required Laravel extensions are installed in the GitHub runner.
- Add annotation and schema checks to fail CI early (PHP lint, annotation sanity checks, lightweight JSON checks, extended structural checks, swagger-cli validation).
- Add/clean OpenAPI annotations for models and key API endpoints (Auth, Customer, Service, Transaction, Report).
- Improve Swagger UI appearance with a custom documentation header and tag grouping.

Files/changes of interest:

- .github/workflows/generate-swagger.yml (new CI workflow)
- scripts/debug_parse.php (annotation sanity checks)
- scripts/validate_openapi.php (lightweight + extended JSON checks)
- scripts/validate_openapi_strict.php (cebe/php-openapi attempt kept for reference)
- app/Swagger/ApiInfo.php (single source-of-truth for `@OA\Info` and security scheme)
- app/Http/Controllers/Api/\* (improved `@OA` annotations for responses, including report endpoints)
- resources/views/vendor/l5-swagger/index.blade.php (enhanced Swagger UI layout)

How to test locally:

1. php -l app/Http/Controllers/Api/ReportController.php
2. php scripts/debug_parse.php
3. php artisan l5-swagger:generate --no-interaction
4. php scripts/validate_openapi.php

Notes:

- `composer install` passes locally; workflow failed earlier because the runner needed more PHP extensions.
- `App\Swagger\ApiInfo` is required for scanner and docs generation.
- `PR_BODY.md` is included so the pull request can be opened easily once `gh` is authenticated.

Suggested reviewers: backend team, documentation owner

---

You can create the PR after logging in with `gh auth login` using:

```bash
gh pr create --title "chore(swagger): stabilize generation, add CI validation and improve OpenAPI docs" --body-file PR_BODY.md --base main --head feat/swagger-ci --draft
```
