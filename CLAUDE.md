# Zenith Moodle Theme — Development Context

## Project
- **Root**: `/home/redman/zenith-moodle-theme`
- **Type**: Premium Moodle Theme (extends Boost, Bootstrap 5)
- **Target**: Moodle 4.5 LTS → 5.1+
- **Live URL**: https://lms.endlessmaker.com/
- **Deployment**: Coolify (docker-compose.coolify.yml on `coolify` external network)
- **Port**: 8081 (web), 3306 (MariaDB) — avoids conflicts with TadHub

## Reference Projects
- **Edwiser RemUI**: `/home/redman/Edwiser-RemUI/theme_remui/remui/` — reference theme
- **TadHub**: `/home/redman/TadHub/` — deployment patterns, screenshot testing
- **Documentation**: `/home/redman/Edwiser-RemUI/moodle-theme-development-guide.html`
- **Strategy**: `/home/redman/Edwiser-RemUI/THEME_DEVELOPMENT_STRATEGY.md`

## Architecture
- Boost child theme (Bootstrap 5, no Tailwind)
- CSS design tokens with `--z-` prefix (CSS custom properties)
- Selective Bootstrap imports (target <200KB CSS)
- ES Modules transpiled to AMD via Grunt
- Mustache templates with `{{#str}}` for i18n
- PSR-14 Hooks API (Moodle 4.4+)

## Key Conventions
- PHP: PSR-12, Moodle coding style, namespace `theme_zenith\`
- SCSS: BEM naming `.z-component__element--modifier`, design tokens
- JS: ES Modules, no jQuery, vanilla JS only
- Templates: ARIA attributes on all interactive elements
- All strings via language files (never hardcoded)

## Deployment
- Uses Coolify with Traefik proxy on `coolify` external network
- Moodle runs on port 8081 internally, MariaDB on 3306
- Container names: zenith-moodle, zenith-mariadb
- Traefik routes lms.endlessmaker.com → zenith-moodle:8081

## Testing
- Playwright for screenshot testing (e2e/)
- PHPUnit for PHP unit tests (tests/)
- Behat for acceptance tests
- Mobile viewports: 375px (iPhone SE), 768px (iPad), 1440px (Desktop)
