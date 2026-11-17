# Agent instructions for MAObjects S

These instructions apply to this theme repository and all its subdirectories.

## Project context

MAObjects S is the University Library Mannheim's Omeka S theme, derived from
Center Row. It runs inside an Omeka S installation; it is not a standalone PHP
application. `config/theme.ini` declares compatibility with Omeka S `^4.0.0`.
The README, Composer metadata, npm metadata, and JavaScript namespace still
contain upstream Center Row names. Preserve those names unless the task calls
for changing them.

## Repository map

- `config/theme.ini`: theme metadata, administrator settings, and default resource
  page regions and blocks.
- `view/layout/layout.phtml`: shared page layout and asset loading.
- `view/common/`: shared partials, navigation, footer, media, and metadata output.
- `view/omeka/site/`: item and item-set browse overrides.
- `view/hierarchy/`: templates for the Hierarchy module.
- `asset/sass/`: stylesheet sources. `style.scss` imports the original Center Row
  partials, including `_base.scss` for shared variables and `_mobile.scss` for
  upstream mobile styles. `maobjects.scss` imports `_maobjects.scss` for Mannheim
  colors, mixins, and all project overrides, including mobile adjustments.
- `asset/css/style.css` and `asset/css/maobjects.css`: compiled, tracked
  stylesheets, loaded in that order. Regenerate them after Sass changes and
  include the relevant output in the change.
- `asset/js/`: theme behavior in `centerrow.js` and `browse.js`, plus the bundled
  accessible mega-menu plugin.
- `asset/img/`, `asset/fonts/`, and `asset/css/lightgallery.min.css`: branding and
  bundled assets. Preserve third-party notices and avoid unrelated vendor edits.

## Development workflow

Run commands from this repository's root. Keep changes within the theme unless
the task explicitly includes the surrounding Omeka installation or modules.
Inspect the working tree first and preserve existing user changes.

Install the declared JavaScript development dependencies with `npm ci`. The
Gulp build also requires the `sass` package, which is currently absent from
`package.json` and `package-lock.json`. Check its availability with
`node -p "require.resolve('sass')"`. If missing, report the setup issue or make
an explicit dependency fix when that is part of the task; do not silently
rewrite dependency files during unrelated work.

Use the local Gulp CLI through Node so commands do not depend on a global
installation, executable npm shims, or implicitly downloaded tooling:

```sh
node node_modules/gulp/bin/gulp.js css
node node_modules/gulp/bin/gulp.js css:watch
```

The first command builds CSS once; the second watches Sass files. Edit Sass
sources rather than applying lasting changes directly to generated CSS. Keep
MAObjects S styling in `_maobjects.scss` and leave the original Center Row
partials free of project overrides. Inspect the generated diff for unrelated
changes. There is no `start` script.

## Implementation guidance

- Follow the surrounding PHP, Sass, and JavaScript style; keep diffs focused.
- Use Omeka/Laminas view helpers for URLs, assets, settings, and rendering.
  Translate new interface strings with `translate()`. Escape plain text and
  attribute values for their output context; retain intentional HTML produced
  by Omeka rendering helpers.
- Preserve keyboard navigation, focus behavior, accessible labels, skip links,
  and responsive layouts when modifying navigation or controls.
- Retain theme-setting behavior, locale filtering, and configurable resource
  page blocks when changing views. Keep setting names and their uses in sync.
- `view/common/resource-values.phtml` adapts AdvancedResourceTemplate property
  grouping and falls back when the module is absent. Preserve both paths and
  check upstream compatibility when changing this integration.
- Treat Hierarchy templates as module overrides: check the installed module's
  view data and helpers before changing their assumptions.
- Preserve the existing jQuery integration and script loading order. Avoid
  introducing a new frontend framework or build system for routine theme work.

## Validation

There is no automated test suite configured here. The npm `test` script is a
placeholder, not a useful validation command. Select checks for the change:

- PHP templates: run `php -l path/to/changed-template.phtml` for each changed file.
- JavaScript: run `node --check path/to/changed-script.js`, then exercise the
  affected browser behavior.
- Sass: run the one-off Gulp CSS build and review both generated stylesheets.
- Theme behavior: use an available Omeka S installation to check affected pages
  at desktop and mobile widths. As relevant, check navigation/search, browse
  grid/list toggles and pagination, resource metadata and Boolean display,
  Hierarchy pages, and optional module behavior.
- Run `git diff --check` and review the final diff. Report checks performed and
  any checks blocked by missing tools, dependencies, or a running installation.
  Documentation-only changes do not require rebuilding assets.

## Conventions

1. Commits are signed off (`git commit -s`). Work materially shaped by an AI
   tool is declared with a trailer in the form used by the
   [Koha Coding Guidelines](https://wiki.koha-community.org/wiki/Coding_Guidelines),
   and the human author stays responsible for the change:

   ```yaml
   Assisted-by: <ModelName> <Version> (<Vendor>)
   Signed-off-by: Jane Doe <jane@example.org>
   ```
