# Center Row

This is an Omeka S theme featuring delicate serifs and a single-line, centered navigation. 

## Installation

For basic out-of-the-box use of the theme, follow the [Omeka S User Manual instructions for installing themes](https://omeka.org/s/docs/user-manual/sites/site_theme/#installing-themes). 

For more advanced use, such as customizing the theme with Sass, you'll need to install the tools with [NodeJS](https://nodejs.org/en/) (0.12 or greater). Navigate to your theme directory and run `npm install`.

## Customizing the Theme

For those dipping their toes into customizing sites with CSS, the [CSS Editor](https://omeka.org/s/modules/CSSEditor/) module allows site administrators to write style overrides.

The Sass sources build two stylesheets, loaded in this order:

* `asset/sass/style.scss` builds `asset/css/style.css` from the original Center Row partials (`_normalize.scss`, `_base.scss`, `_screen.scss`, and `_mobile.scss`). Keep project customizations out of these files.
* `asset/sass/maobjects.scss` builds `asset/css/maobjects.css` from `_maobjects.scss`, which contains the Mannheim colors, mixins, and theme overrides, including mobile adjustments. It imports the shared variables from `_base.scss` without duplicating the original CSS rules.

Add MAObjects S styling to `asset/sass/_maobjects.scss` and rebuild both stylesheets. Commit the generated CSS alongside its Sass sources.

MAObjects S uses the current Classic Mannheim palette (`#00325d` and `#abb8ca`), with shared link, heading, form, button, and keyboard-focus styles. Body text stays at 18 pixels with a 1.5 line height; standard form controls share a 2.5rem minimum height. Header quick search and footer typography retain their own compact sizes.

Text uses a local system sans-serif stack starting with `system-ui`, followed by platform fonts and Arial/Noto/Liberation Sans fallbacks. It does not require Myriad Pro, a font service, or a university stylesheet. Bundled upstream font files and third-party notices are retained; icon fonts and monospace code retain their own font families.

### Sass Tasks

Run these commands within the theme's root directory.

* **node node_modules/gulp/bin/gulp.js css**: Compile both stylesheets once.
* **node node_modules/gulp/bin/gulp.js css:watch**: Watch the Sass sources and recompile both stylesheets when they change.

Install the locked development dependencies with `npm ci` before building. Sass is included in the development dependencies.

## Theme Configuration

* **Logo**: Upload an image asset to use as a logo in place of a text site title.
* **Header subtitle / position**: Optional plain text below or beside the logo or site title. The site title is used as the logo's alternative text.
* **Header search**: Keep the Omeka S search form behind a search toggle (default), show an advanced item-search link, or hide header search. The toggle respects the site's search settings and any module override of the core search partial.
* **Banner**: Upload an image asset to use a banner that sits above the main content area of every view.
* **Banner height**: The banner container height, for example `300px` or `15rem`. Supported values are nonnegative lengths in `px`, `em`, `rem`, `vh`, `vw`, `vmin`, `vmax`, or `%`, plus `0` and `auto`. Leave empty for natural image height. Invalid values are ignored; CSS functions such as `calc()` are not supported.
* **Banner height for mobile devices**: The height at widths up to 640 pixels, using the same values. Leave empty to inherit the desktop height, or use `auto` for natural image height.
* **Banner position**: Where to anchor the banner image within its container: centered, stuck to the top, or stuck to the bottom.
* **Banner alternative text**: Describe meaningful banner content, or leave empty for a decorative image.
* **Banner visibility**: All pages (default), homepage only, all except the homepage, or hidden. The homepage is the site's configured homepage, falling back to its first linked page as Omeka S does.
* **Top navigation depth**: Maximum number of menu levels. `0` shows all levels, `1` shows only top-level links, and `2` includes their children. Accessible parent pages remain linked even when deeper descendants are excluded.
* **Layout for Browse Pages**: Fixed grid/list, or a toggle with either default. Toggle choices are stored in the `browse_layout` URL parameter and survive reload, sorting, and both pagination controls. Fixed modes retain the configured layout. Page previews switch independently while JavaScript is available.
* **Show metadata output links**: Show native API representations for items, item sets, media, and the current page of browse results. Enabled by default; disable it when another module already supplies output controls. See [Metadata output](#metadata-output).
* **Footer**: Configure branding, up to three link columns, optional HTML content, legal links, social icons, and a gradient background. See [Footer configuration](#footer-configuration).
* **Boolean value display**: Display Boolean metadata as True/False, Yes/No, or accessible checkmark/X icons.

The header keeps the navigation configured under the site's **Navigation** tab. At widths up to 800 pixels, a **Menu** button opens navigation below the branding. Dropdowns retain a direct link to their parent page. Escape closes a dropdown and returns focus to its trigger; a second Escape closes the mobile menu. Search focuses the query field on opening and returns focus to its button on Escape. Navigation and search remain available without JavaScript.

### Footer Configuration

Open the site's **Theme → Edit theme settings**. Footer settings are grouped under Branding, Link columns, and Legal and social links. They apply independently to each site.

The footer background and content rows span the full available width, with responsive side gutters. Columns stack on small screens. The theme uses the current Classic Mannheim blue (`#00325d`); **Use footer gradient background** adds the Classic-style gradient.

* **Logos**: The two bundled institutional logos remain visible by default. Upload an asset to replace either one, or uncheck **Show footer logo** to hide it. Clearing an uploaded asset restores the bundled logo. Each slot has an optional label, alternative text, and destination; clear the destination for an unlinked logo. When replacing a logo, update its alternative text and destination as well.
* **Link columns**: Configure up to three titles and link lists. Empty columns are omitted. Titles and link labels are plain text.
* **Footer Content**: The existing customizable HTML field now appears below the columns and defaults to "Powered by Omeka S". Replace that text with your own content, or clear the field to omit the row. Review previously saved content when upgrading: this setting was previously ignored. Image-only HTML is supported.
* **Legal links**: The university privacy link is supplied by default. Add an imprint link after creating a public imprint page in the site. Clearing this field hides the legal links.
* **Social icons**: Enter individual HTTP(S) URLs for Instagram, Facebook, LinkedIn, TikTok, YouTube, or Mastodon. Empty or unsupported URLs are omitted. Links open in the same tab and have accessible platform names.

Link columns and legal links use one `"url":"label"` pair per line:

```text
"/page/imprint":"Imprint"
"/page/about":"About this project"
"/item":"Browse items"
"https://www.bib.uni-mannheim.de/":"University Library"
"mailto:project@example.org":"Contact"
```

A leading slash resolves within the **current Omeka S site**, including its installation subdirectory. `/page/imprint` therefore works from home, item, browse, and nested page URLs. Use full URLs for other sites. When copying Classic configuration, change paths such as `/imprint` to `/page/imprint` and verify that the pages exist and are public. Links are not automatically checked for destination availability.

Legacy Classic lists containing HTML line breaks and encoded quotes are accepted. Escape a quotation mark inside a label as `\"`, for example `"/page/about":"About \"Our project\""`. Invalid entries, unsupported schemes, network-relative URLs (`//example.org`), and paths that traverse outside the site are omitted. The bundled social SVGs are reused from the Classic MAObjects theme; their source is recorded alongside the assets.

### Browse presentation

Items, item sets, Hierarchy item results, and browse-preview blocks share card and thumbnail styling. Grid cards reflow with the available width. Lists use alternating backgrounds with thumbnails beside titles and descriptions; missing thumbnails leave readable text without an empty image slot. Preview component choices and locale filtering are preserved.

The grid/list switch uses stable values independently of translated button labels. In toggle mode, ordinary browse pages also support switching without JavaScript through a GET form. With JavaScript, focus stays on the selected button, its pressed state is announced, and both pagination controls and the sorting form retain the choice and existing filters. Item sets now expose the native sort form for ID, title, creation date, and modification date. Preview switches affect only their own block and are hidden when JavaScript is unavailable.

### Metadata output

The [native Omeka S API](https://omeka.org/s/docs/developer/api/rest_api/) supports JSON-LD. Omeka S 4.1+ also supports RDF/XML, Turtle, N3, and N-Triples; the theme only offers those extra formats on supported versions. These links use public API access and require no export module.

On browse pages, **Metadata formats (this page)** contains exactly the displayed resource IDs, including filtered and item-set results. It is a page-sized dataset; empty results show no output links. Individual resource pages link to that resource's API representation. These outputs contain metadata, not the original uploaded files. Citations, CSV/bulk exports, and Classic RSS/Atom endpoints are separate integrations.

## Resource Page Configuration

Omeka S 4.0 introduced configurable resource pages. Center Row supports block configuration for items, item sets, and media show pages. Each of those views contains a single main configurable region.

## Copyright
Center Row is Copyright © 2016-present Corporation for Digital Scholarship, Vienna, Virginia, USA http://digitalscholar.org

The Corporation for Digital Scholarship distributes the Omeka source code
under the GNU General Public License, version 3 (GPLv3). The full text
of this license is given in the license file.

The Omeka name is a registered trademark of the Corporation for Digital Scholarship.

Third-party copyright in this distribution is noted where applicable.

All rights not expressly granted are reserved.
