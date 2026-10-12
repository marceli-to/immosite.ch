---
name: immosite
description: Real-estate websites (Laravel) for agencies, built from the immosite.ch starter. Use for a new site from a designer brief, a sister project / reskin of an existing site, adding or removing a module (registration, apartments, Flatfox, Melon, isometry …), turning a design PDF or Figma into pages, or the go-live check.
---

# immosite

Every site is a copy of the **starter** at `/Users/marceli.to/Jamon.digital/Webroot/immosite.ch`
(GitHub `marceli-to/immosite.ch`). The starter is the source of truth for code; this skill
holds the process. Read the starter's `CLAUDE.md` (conventions) and `modules.md` (what each
module is, its files, hooks and `needs:`) before changing a project.

Pick the flow and read its file:

| Task | Read |
|---|---|
| New site (landing page or full site) | [new-site.md](new-site.md) |
| "Like X, but with these colours" (sister project / reskin) | [sister-project.md](sister-project.md) |
| Add or remove a module in an existing project | [modules.md](modules.md) |
| Design brief / PDF / Figma / screenshots → tokens, pages, open items | [design-to-code.md](design-to-code.md) |
| Before launch | [go-live.md](go-live.md) |
| Flatfox, Melon, Mieterspiegel, how agencies want tables | [domain.md](domain.md) |

## Always

- Projects live in `/Users/marceli.to/Jamon.digital/Webroot/<domain>`, served by Herd at `https://<domain>.test`.
- Code, comments, docs and commits in English; visitor-facing text in German (Swiss spelling, no ß).
- Git remote over SSH (`git@github.com:marceli-to/<repo>.git`): the HTTPS account on this machine can't push to marceli-to.
- A step is done when `php artisan test`, `npm run build` and `npm run screenshots -- <urls>` are green (✓ for every width, no overflow, no JS errors). Look at the screenshots, don't just count ticks.
- Commit after scaffolding and after each finished step.
- After scaffolding, the tests are the **project's** tests: when content moves (e.g. the form leaves the home page), update the tests with it.
- A fix or improvement that every site needs goes into the starter too (with tests and its `modules.md` entry), so the next site gets it. This skill lives in the starter (`skill/immosite`, symlinked to `~/.claude/skills/immosite`); fix it there and push the starter.
