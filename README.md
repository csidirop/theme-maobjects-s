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

### Sass Tasks

Run these commands within the theme's root directory.

* **node node_modules/gulp/bin/gulp.js css**: Compile both stylesheets once.
* **node node_modules/gulp/bin/gulp.js css:watch**: Watch the Sass sources and recompile both stylesheets when they change.

Install the locked development dependencies with `npm ci` before building. Sass is included in the development dependencies.

## Theme Configuration

* **Logo**: Upload an image asset to use as a logo in place of a text site title.
* **Banner**: Upload an image asset to use a banner that sits above the main content area of every view.
* **Banner height**: The maximum banner image height in pixels.
* **Banner height for mobile devices**: The maximum banner image height in pixels at narrower viewport widths.
* **Banner position**: Where to anchor the banner image within its container: centered, stuck to the top, or stuck to the bottom.
* **Top navigation depth**: If the main navigation is set to display child pages, this setting controls how many navigation levels to display. Setting this to '0' shows all levels.
* **Footer**: Configure branding, up to three link columns, optional HTML content, legal links, social icons, and a gradient background. See [Footer configuration](#footer-configuration).
* **Boolean value display**: Display Boolean metadata as True/False, Yes/No, or accessible checkmark/X icons.

### Footer Configuration

Open the site's **Theme → Edit theme settings**. Footer settings are grouped under Branding, Link columns, and Legal and social links. They apply independently to each site.

The footer uses a full-width background with content aligned to the main page. Columns stack on small screens. The theme uses the current Classic Mannheim blue (`#00325d`); **Use footer gradient background** adds the Classic-style gradient.

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
