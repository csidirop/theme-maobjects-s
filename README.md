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

The build requires `sass` in addition to the declared npm dependencies. It is currently available in the local development environment but is not declared in `package.json` or `package-lock.json`.

## Theme Configuration

* **Logo**: Upload an image asset to use as a logo in place of a text site title.
* **Banner**: Upload an image asset to use a banner that sits above the main content area of every view.
* **Banner height**: The maximum banner image height in pixels.
* **Banner height for mobile devices**: The maximum banner image height in pixels at narrower viewport widths.
* **Banner position**: Where to anchor the banner image within its container: centered, stuck to the top, or stuck to the bottom.
* **Top navigation depth**: If the main navigation is set to display child pages, this setting controls how many navigation levels to display. Setting this to '0' shows all levels.
* **Footer content**: Control what appears in the footer. This field takes HTML markup.
* **Boolean value display**: Display Boolean metadata as True/False, Yes/No, or accessible checkmark/X icons.

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
