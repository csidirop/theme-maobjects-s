# MAObjects Classic → MAObjects S migration plan

Scope: migrate the recognizable presentation and useful theme features, with header and footer first. Content/database migration, production deployment, and installing modules are separate work.

Completed tasks are marked `[x]`; remaining tasks use `[ ]`.

## Evidence and initial state

Inspected baselines:

- Classic checkout: `~/Workplaces/UB-workspace/FDZ/OMEKA/omeka-docker/common/themes/maobjects`,
  commit `01b3cc4` (`Bump version`), with header/footer templates, helpers,
  settings, Sass, JavaScript, changelog, and documented screenshots.
- S checkout: commit `e014481` (`Merge upstream Center Row master`).
- Running S site: `http://127.0.0.1:8209/onheyb/`, serving MAObjects S on Omeka S
  4.2.1. Desktop and mobile screenshots were inspected at 1440 and 390 pixels.

Initial comparison, before Deliveries 1 and 2:

| Area | Classic implementation | Initial S implementation | Migration implication |
| --- | --- | --- | --- |
| Header identity | Logo/title plus optional subtitle below or beside it; identity at left, navigation/search at right | Logo **or** title; Center Row title spacing remains | Rebuild the header's inner layout and add subtitle options |
| Navigation | Active underline, blue dropdowns, responsive styling | Accessible mega-menu and depth setting already present | Adapt styling and controls around the existing S navigation |
| Search | Toggle, replacement form, and hide options; disclosure state, Escape and outside-click handling | Core S form and simple toggle; no expanded/controls attributes or Escape handling | Use one S search-mode setting and improve disclosure behavior |
| Banner | Asset, heights, alignment, alternative text, homepage visibility | Asset, heights, alignment; image has no `alt` attribute | Keep existing keys; add alternative text and visibility choices |
| Footer | Separate shell; two logo slots, three link sections, HTML text, legal/social rows, gradient and compact variants | Hard-coded institutional logos and two links inside `#wrap` | This is the largest missing component |
| Footer settings | Templates consume the Classic options | `footer` HTML setting is declared but never rendered | Restore this existing setting as the additional text slot |
| Browse | Grid/list presentation, zebra rows, sort controls | Grid/list toggles, sorting, pagination, and preview overrides already exist | Refine the current implementation instead of porting Classic templates |
| Resource pages | Media/metadata layout options, captions, citations, output formats | Configurable S resource blocks, grouped metadata, Boolean display | Extend blocks and styling while retaining these S features |
| Design foundation | Current Classic Sass uses `#00325d` and `#abb8ca` | S uses `#232e58` and `#abb8ca` | Make the palette change an explicit, separately reviewable decision |

The initial local screenshots showed concrete header defects: desktop search
was above the visible page area, and mobile navigation occupied a narrow column
beside the title. `CenterRow.mobileMenu()` listened for a `.menu-button`, but the
theme did not render one. These were addressed in Delivery 2.

Public reference roles:

| Reference | Useful configuration to reproduce |
| --- | --- |
| [Kotzebue](https://fdz.bib.uni-mannheim.de/kotzebue/) | Exhibition branding, subtitle, image-led landing page, curated navigation |
| [Karibische Literaturen](https://fdz.bib.uni-mannheim.de/karibische-literaturen/) | Bilingual content, poster presentation, expanded footer with link columns and social links; omit its placeholder content |
| [Nikephoros](https://fdz.bib.uni-mannheim.de/nikephoros/) | Restrained bibliography landing page with prominent search |
| [Cynisca](https://fdz.bib.uni-mannheim.de/cynisca/) | Long project title, extensive navigation, text-heavy records and collection/tag discovery |

These are content and configuration references, not verified pixel-level targets.

Source map for implementation: Classic `common/header.php:48`,
`common/footer.php:19`, footer helpers in `functions.php:386`, and footer Sass in
`css/sass/_maobjects.scss:722`. On the S side, start at
`view/layout/layout.phtml:59`, `view/common/footer.phtml:1`,
`config/theme.ini:12`, and `asset/sass/_maobjects.scss:85`.

## Delivery 1: shared shell and configurable footer

This delivery can ship independently of the header redesign and provides the clearest visual gain.

- [x] Move footer rendering outside the constrained `#wrap`. Keep a centered
      footer inner container aligned with the main content, initially using the
      existing 1100-pixel maximum width. Use a full-width background and
      ordinary document flow so the footer sits below short and long pages
      without covering content.
- [x] Reproduce the Classic footer hierarchy: branding column plus up to three
      link columns, optional HTML text below, then a separator and legal/social
      row. Use local CSS Grid/Flexbox rather than importing Classic's Foundation
      grid.
- [x] Make content configurable per site. Keep the bundled university/library
      logos available as institutional defaults, with a clear way to replace or
      hide either slot. Each slot needs an asset, optional label, alternative
      text, and optional destination. Preserve the linked institutional branding
      available in S.
- [x] Add three section title/link fields, legal links, the six optional social
      URLs used by Classic, and a solid/gradient background choice. Empty
      optional sections must not leave blank columns or spacing. Social icons
      can be local SVGs with accessible link names.
- [x] Render the existing `footer` setting as the optional additional HTML row.
      Document that previously ignored content may become visible after
      upgrading. Review of saved pilot-site values is tracked under
      configuration transfer below.
- [x] Replace the hard-coded `page/imprint` link with an explicit site-aware
      URL. The previous relative form resolved differently from home, item, and
      page URLs.
- [x] Stack footer groups at narrow widths, preserve readable link spacing, and
      let legal links wrap. Keep the full footer as the default. The compact
      variant with the optional floating homepage is tracked separately in
      Delivery 3.

Suggested settings retain Classic names where useful: `footer_logo_primary`,
`footer_logo_secondary`, their label/alt/link options,
`footer_section_1_title` / `footer_section_1_links` through section 3,
`footer_legal_links`, `footer_social_*`, and `footer_use_gradient`.
Use grouped S theme settings for Header, Footer, and existing feature groups.
[S theme settings and groups](https://omeka.org/s/docs/developer/themes/theme_settings/)
support this without a new administration interface.

For link lists, retain Classic's one `"url":"label"` pair per line for easy
transfer, using a plain textarea rather than a rich-text editor. Accept legacy
`<br>` line breaks when copying old values. Define URL behavior explicitly:

- Full HTTP(S), `mailto:`, and `tel:` destinations retain their meaning.
- Paths such as `/page/imprint` and `/item` are relative to the current S site,
  resolved from its canonical site URL, never the current browser path.
- Classic `/imprint` must be mapped to S `/page/imprint` during configuration.
  Cross-site links use explicit URLs; they are not interpreted as local pages.
- Escape labels and attributes, restrict supported destination types, and skip
  malformed entries. Publish examples and check transferred lists during setup.
  Keep intentional HTML confined to the existing `footer` HTML field.

Do not ship sample legal pages or project links that do not exist. Missing
local destinations are tracked under pilot-site validation below.

Primary files: `view/layout/layout.phtml`, `view/common/footer.phtml`,
`config/theme.ini`, and `asset/sass/_maobjects.scss`. Put shared link parsing and
URL resolution in a small theme helper registered through `helpers[]`, following
the [S helper mechanism](https://omeka.org/s/docs/developer/themes/theme_functions/).
Keep Classic's global `functions.php` out of the port.

- [x] Declare the Sass development dependency in the manifest and lockfile so
      clean-install CSS builds work.
- [x] Verify institutional defaults, custom and empty configurations, links from
      home/resource/browse/nested page URLs, footer text rendered once, and no
      desktop/mobile horizontal overflow.

## Delivery 2: responsive header, navigation, search, and banner

Pilot-installation limitation: the optional advanced item-search destination
currently returns HTTP 500 because the installed Common module's
`DataType::getSelect()` signature is incompatible with Omeka S 4.2.1. The default
header search works.

- [x] Extract a `view/common/header.phtml` partial from the layout. Give
      branding, navigation/search, and the optional banner separate containers.
      Keep the banner below the header row so it does not compete for horizontal
      space.
- [x] Match the Classic identity arrangement: logo/title at left, navigation and
      search at right. Add plain-text `logo_text` and `logo_text_position`
      (`below`/`beside`). Retain the existing `logo` asset and title fallback.
      Override inherited uppercase/letter-spacing and large title margins where
      they conflict with project identity. Avoid duplicating the site name when
      the logo already contains it.
- [x] Apply active underlines and blue dropdown panels to the existing
      mega-menu. Preserve navigation filtering, translations, depth settings,
      and parent-page access. In particular, do not copy Classic's `.parent {
      display: none; }` rule: the original S menu used that link to reach a
      parent whose top-level link was `#`.
- [x] Add a real mobile menu button with an accessible name, controlled
      navigation ID, and expanded state. Place navigation below branding at
      narrow widths; let it wrap or collapse at the point where actual content
      no longer fits. Support touch, keyboard, Escape, and predictable focus
      restoration. Keep navigation usable before JavaScript initializes.
- [x] Translate Classic's competing search booleans into `header_search_mode`:
      `toggle` (existing default), `link` (native advanced item search), or
      `hidden`. Keep S's core search form and query handling. Style its actual S
      IDs rather than copying Classic's `#query`/`#submit_search` selectors.
      Update expanded state, focus the query field on opening, close on
      Escape/outside click, and restore focus on keyboard dismissal. Remove the
      inherited negative placement that puts desktop search above the viewport.
      Respect applicable S site search settings and module-provided forms.
- [x] Preserve `banner`, `banner_height`, `banner_height_mobile`, and
      `banner_position`. Add `banner_alt` and `banner_visibility` (`all`,
      `home`, `inner`, `none`), with `all` preserving current behavior. Resolve
      the actual S homepage through site/page routing rather than Classic's
      `#home` selector. Empty alt text may deliberately mark a decorative
      banner. Validate supported length/alignment values before using them in
      CSS.
- [x] Keep the current jQuery/global/mega-menu/theme script order and initialize
      each control once. Extend the existing theme JS without introducing a new
      frontend framework or loading the entire Classic `uma-app.js` bundle.
- [x] Verify title-only, logo-only, subtitle, long-title, long-navigation, and
      deep-menu configurations at desktop/mobile widths; usable search, skip
      link and focus indicators; and banner visibility on
      home/item/item-set/search/content pages.
- [ ] Pilot follow-up (installation/module work): resolve the Common module
      compatibility issue before choosing the advanced-search link mode on this
      installation.

## Delivery 3: design consistency and landing-page variants

The first two tasks were implemented on 18 November 2026. The shared styles use
the current Classic palette, a system sans-serif stack, consistent heading and
paragraph spacing, matching form/button sizes, and visible keyboard focus.
Verified home, browse, item, item-set, Hierarchy, and search pages at
320–1440-pixel widths, control states, icon fonts, quick search, and 200% zoom.
Homepage recipes and variants remain pending below.

- [x] Unify colors, link treatments, headings, form controls, spacing, and
      buttons in `_maobjects.scss`, including visible focus styles. The current
      Classic palette (`#00325d` / `#abb8ca`) is the visual reference. The
      palette change from S’s `#232e58` was made in Delivery 1; review
      consistency across content as well as header/footer. The documented
      Classic screenshots also contain older colors, so keep the chosen baseline
      explicit.
- [x] Use a reliable system sans-serif stack matching the Classic approach. S
      previously requested `myriad-pro` without bundling that font. Do not make
      visual parity depend on an unavailable font or a remotely hosted university
      stylesheet. Keep third-party notices when reusing local assets; do not
      import the entire Classic reset/grid.

Provide two documented S homepage recipes:

- [ ] Document an exhibition homepage: banner, introduction, curated
      item/item-set previews, and links to exhibition pages. Use native page
      blocks and existing browse-preview styling.
- [ ] Document a research-collection homepage: restrained title/introduction and
      prominent search, followed by relevant collection links or previews.

S pages and blocks replace Classic's hard-coded featured item/collection/exhibit
homepage. [S page blocks](https://omeka.org/s/docs/user-manual/sites/site_pages/)
provide the composition model. An optional carousel can use an evaluated S block
module later; a new slider dependency is not necessary for the first release.

- [ ] Add the background-image/floating-homepage treatment as an opt-in page
      template or explicit homepage setting. Leave it off by default and use
      normal flow.
- [ ] Add the compact footer for the floating homepage: keep legal links and
      branding, and document which other rows it hides.
- [ ] Respect reduced-motion preferences for any added animation; any later
      autoplay needs pause controls.

## Delivery 4: browse and resource presentation

Grid/list presentation, sort/pagination state, and native metadata output links
were implemented on 18 November 2026. Shared cards preserve preview components
and locale filtering; fixed layouts stay fixed, while toggle layouts persist in
the URL. Both paginators and native item/item-set sorting retain that choice.
Preview switches remain independent. Output links use Omeka S's API formats and
limit browse output to the displayed records; citations remain a separate task.

Verified settings, PHP/JS syntax, CSS build, helper/card checks, native output
responses, reload/sort/pagination, translated labels, independent previews,
320–1440-pixel reflow, 200% zoom, and no-JavaScript controls. Toggle-mode browser
checks used fully rendered Omeka S requests with an in-memory setting override;
saved pilot settings were not changed. The pilot has no enabled Hierarchy module
or media records, so their live integration checks remain pending.

- [x] **Grid/list browse:** Keep existing item, item-set, Hierarchy, and
      browse-preview implementations; harmonize cards, thumbnails, spacing, and
      alternating list rows.
- [x] **Sort and pagination:** Keep S's native controls; verify layout choice
      survives reload, sorting, page links, page-number submission, and
      translated labels.
- [x] **Browse selector mismatch:** Adapt existing `.item-meta.hentry.list`
      styling to S `.resource-list.list` markup; do not copy more Classic
      selectors that cannot match S.
- [ ] **Breadcrumbs and exhibition navigation:** Style the S equivalents and add
      visibility controls only where editors need them; preserve parent-page
      access.
- [ ] **Media and metadata layout:** Keep the existing `main` resource region.
      Start with stacked presentation; add optional media/sidebar regions if
      side-by-side layouts are needed, without moving existing saved blocks
      automatically.
- [ ] **Captions and galleries:** Evaluate S's core LightGallery resource block
      before adding another viewer; confirm caption handling in the installed
      core because `media_caption` has no consumer in this theme's own templates.
- [ ] **Multiple files and PDFs:** Exercise image-only, PDF-only, mixed-media,
      and no-media records with S renderers; adapt layout/captions/download
      links around their output.
- [ ] **Heading/title duplication:** Use resource/block configuration where
      possible, then add narrowly scoped display settings if necessary; retain
      locale filtering and grouped metadata.
- [x] **Output links:** Use native JSON-LD output, plus RDF/XML, Turtle, N3,
      and N-Triples on Omeka S 4.1+. Make the links configurable and limit browse
      output to the displayed page. Do not transplant Classic export endpoints.
- [ ] **Citations:** Separate follow-up using verified S APIs or installed
      modules; do not transplant Classic citation endpoints.
- [ ] **Integration validation:** Check the Hierarchy result view with the
      installed/enabled module and verify individual media output with media
      records on the pilot site.

S supports [configurable resource regions and media blocks](https://omeka.org/s/docs/developer/themes/theme_use_resource_page_blocks/).
Preserve `resourcePageBlocks()` calls, existing site configurations,
AdvancedResourceTemplate grouping with its module-absent fallback, Boolean
display settings, and Hierarchy behavior throughout.

## Delivery 5: optional feature integrations

Evaluate these as individual optional integrations:

- [ ] Evaluate tag filtering.

- [ ] Evaluate faceted discovery.

- [ ] Evaluate table/index browsing.

- [ ] Evaluate citations.

- [ ] Evaluate export formats.

- [ ] Evaluate specialized PDF viewing.

Classic plugins and S modules are not interchangeable. For each integration,
inspect the installed module version, templates, and enabled site configuration
before planning an override. The surrounding installation has candidate modules
on disk, but that does not prove they are enabled or appropriate for a given site.

Do not copy Classic's cookie notice, Matomo initialization, shared cookie domain,
or site IDs as part of footer styling. Those are deployment behavior and need to
integrate with the S installation's existing approach. Similarly, Classic
Exhibit Builder and Admin Tools overrides need separate S equivalents rather
than copied templates.

## Reusable validation checklist for each remaining implementation delivery:

- Run `php -l` for changed PHP/templates/helpers and `node --check` for
   changed JavaScript. Add focused helper checks for link parsing and
   site-relative URL resolution, including a subdirectory install and two
   site slugs.

- Build Sass with `node node_modules/gulp/bin/gulp.js css`; inspect both
   tracked CSS diffs. Keep project overrides in `_maobjects.scss`. Sass is
   now declared in the dependency manifest/lockfile; the build-preparation
   change was completed in Delivery 1.

- Check 390, 768, and 1440-pixel widths, narrow reflow, 200% zoom,
   keyboard/touch navigation, and JavaScript-disabled fallback. Include long
   translated labels, missing assets, empty footer sections, and very
   short/long pages.

- Check home, content page, browse, search, item, item-set, media, and
   Hierarchy views. Exercise optional module paths only when those modules
   are enabled, and check the module-absent fallbacks.

- Review a pilot site using the existing S settings, then exercise the four
   reference configuration profiles. Keep old settings/assets available for
   rollback and record the settings changed during the pilot.

- Run `git diff --check`; review the final diff and report any blocked
   checks.
