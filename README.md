# Workbench: WordPress Block Theme

Workbench is an editorial, documentation-dense WordPress block theme built specifically for systems labs, AI studios, and developer portfolios. It replaces bloated multipurpose themes with high-density layout primitives, native Full Site Editing (FSE), and an ergonomic two-column workstation shell.

---

## Technical Specifications

- **Theme Type**: Full Site Editing (FSE) Block Theme (`theme.json` v3)
- **Minimum Requirements**: WordPress 6.5+, PHP 8.0+
- **Typography**: Inter Variable (Primary Sans), Newsreader (Display Serif), System Monospace (Code)
- **Design System**: Happy Hues #17 native palette (`cream #fef6e4`, `navy #001858`, `pink #f582ae`, `teal #8bd3dd`) with 8 alternative style variations
- **JavaScript Footprint**: Zero external libraries. 1 lightweight native DOM filter (`assets/js/nav-filter.js`, 1.2 KB unminified)

---

## Directory Organization

```
workbench/
├── style.css           # Core theme styles, CSS variables, shell geometry
├── theme.json          # Block theme schema, token bindings, layout constraints
├── functions.php       # Enqueuing, query loop modifiers, shortcode handlers
├── inc/
│   └── newsletter.php  # Substack modal, inline newsletter card, project footer
├── parts/
│   ├── header.html     # Fixed top header with external social/studio links
│   └── sidebar.html    # Fixed rail containing filter and project query loop
├── templates/
│   ├── front-page.html # Studio index template
│   ├── project.html    # Project showcase layout with [workbench_project_nav]
│   ├── single.html     # Single article template
│   ├── page.html       # Generic page template
│   ├── index.html      # Fallback query template
│   └── 404.html        # Clean 404 error page
├── patterns/
│   ├── project-notes.php
│   └── showcase-hero.php
├── styles/             # FSE Style variations
│   ├── happy-hues.json # Built-in Happy Hues #17 palette
│   ├── cobalt.json
│   ├── editorial-serif.json
│   ├── ember.json
│   ├── forest.json
│   ├── graphite.json
│   ├── midnight.json
│   └── rose.json
└── assets/
    ├── favicon.svg     # SVG studio favicon
    ├── fonts/          # Self-hosted WOFF2 webfonts
    └── js/
        └── nav-filter.js
```

---

## Installation & Setup

1. Place the `workbench` directory into `wp-content/themes/workbench`:
   ```bash
   cp -r workbench /path/to/wordpress/wp-content/themes/
   ```
2. Activate the theme using WP-CLI:
   ```bash
   wp theme activate workbench
   ```
3. Set your front page in **WP Admin → Settings → Reading**:
   - Set **Your homepage displays** to **A static page**.
   - Select your Home page as **Homepage**.

---

## Hierarchical Project Pattern

Workbench streamlines developer project portfolios by establishing a strict 3-tier hierarchical architecture:

```
/project-name/                  # Parent Project Page (Overview)
├── /project-name/setup/        # Child Page: Setup, Installation, Configuration
└── /project-name/docs/         # Child Page: Architecture, API, Tool Schemas
```

### Template Setup
Assign the **Project (with notes card)** (`project`) template to the parent page and each of its child pages.

### Shortcode Integration
- `[workbench_project_nav]`: Placed above the content. Automatically inspects the parent ID, discovers all child pages matching `setup` and `docs`, and outputs cohesive navigation tabs.
- `[workbench_project_footer]`: Placed below the content. Automatically renders the conversion card, source links, and newsletter box.
- `[workbench_search_box]`: Placed in the sidebar. Real-time DOM filtering for fast project lookup.
- `[workbench_newsletter_box]`: Embeds an inline Substack subscription form.
- `[workbench_copyright]`: Dynamically outputs the current copyright notice.

### Post Meta Fields
Populate custom post meta on the parent page to generate outbound tab links:
- `github_url`: Outbound GitHub repository URL
- `website_url`: Outbound live deployment URL
- `pypi_url`: Outbound PyPI distribution URL
- `npm_url`: Outbound npm package URL
- `wporg_url`: Outbound WordPress plugin directory URL

---

## Customization & Styles

To switch or customize the palette:
1. Navigate to **Appearance → Editor → Styles**.
2. Select **Happy Hues** for the signature BuildItWithAI palette, or choose from 7 other curated variations (Ember, Cobalt, Forest, Graphite, Rose, Editorial Serif, Midnight).
3. Tokens are bound to CSS variables (`--wp--preset--color--*`) and update instantly across all components.

---

## License

MIT License. Copyright (c) 2026 Surendran B. / BuildItWithAI.
