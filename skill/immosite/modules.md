# Add or remove a module

The starter's `modules.md` is the catalogue; the project's `modules.md` lists what is installed.

## Remove

```bash
php artisan module:remove <name> [--dry-run]   # also removes modules that need it
```

It deletes the files, the hook lines (marked `module:<name>`), imports of deleted classes,
the manifest section and the module's packages from `package.json` / `composer.json`. Then
run `npm install` / `composer update --lock` if it says so, remove the listed env keys from
`.env` / `.env.example`, drop the module's tables with a new migration if the project is live.

## Add

1. In the starter's `modules.md`, read the module's section and its `needs:`; add missing
   needed modules first, in order.
2. Copy every file under `Files:` from the starter (same paths). Directories: the whole directory.
3. Hooks: `grep -rn "module:<name>" app bootstrap config database resources routes tests` in the
   starter. Put each line into the same file of the project at the matching place (same
   neighbour lines; the project's page content may differ). Lines shared by several modules
   (`module:a,b`) may already exist; then only add the name to the marker.
4. Copy the section from the starter's `modules.md` into the project's.
5. `Packages:` → `npm install <pkg>` / `composer require <pkg>`; `Config/env:` → `.env.example` and `.env`.
6. New migrations → `php artisan migrate`. Module setup commands (`commercial:seed`,
   `user:create`, `isometry:build`, `apartments:fetch`) as the module section says.

Done when tests (including the module's own test file), build and screenshots of the affected
pages pass, and `php artisan module:remove <name> --dry-run` lists exactly the files and hooks
you added.

Landing → full site: add `pages-full`, then `apartments` + a driver, then the rest.
