# Orbis Voys

The Orbis Voys plugin shows Voys phone calls on Orbis contacts, such as persons and organizations.

## Requirements

- PHP 8.3+
- WordPress 7.1+
- [Orbis Contacts](https://github.com/pronamic/wp-orbis-contacts)
- Optional: [Orbis Persons](https://github.com/pronamic/wp-orbis-persons) and/or [Orbis Organizations](https://github.com/pronamic/wp-orbis-organizations), or any other post type that supports `orbis-contact`
- Composer
- Node.js / npm

## How it works

Voys sends a [call notification](https://www.voys.nl/hulp/freedom/gespreksnotificaties/) (webhook) for every status change of a call. The standalone script in [`call-notification-controller/`](call-notification-controller/) stores these notifications in the `orbis_voys_notifications` database table. This script runs outside WordPress and is not part of the plugin build.

On a single contact page (every post type that supports `orbis-contact`, such as persons and organizations) the plugin adds a "Phone Calls" card through the `orbis_after_side_content` action. It lists the last 40 calls from or to the phone numbers of the contact in Orbis Contacts. Both the E.164 format (`+31612345678`) and the national format (`0612345678`) are matched.

## Installation

```sh
composer install
npm install
```

## Development

This plugin follows the [SolveBeam WordPress Plugin Boilerplate](https://github.com/solvebeam/solvebeam-wordpress-plugin-boilerplate) conventions.

### Local environment (wp-env)

```sh
npx wp-env start
npx wp-env stop
```

The `.wp-env.json` maps the plugin twice into the WordPress environment, next to release builds of Orbis Contacts, Orbis Persons and Orbis Organizations:

| Mount path | Source | Purpose |
|---|---|---|
| `wp-content/plugins/orbis-voys-dev` | `./` | Live development (including dev files) |
| `wp-content/plugins/orbis-voys` | `./build/orbis-voys/` | Built distribution version |

### Build

```sh
composer run build
```

### Deploy

```sh
composer run deploy
```

The hosts are defined in the shared `~/deployer.php`, imported through the `DEPLOYER_IMPORT` environment variable.

### Translations

```sh
composer run make-pot
```

### Linting & analysis

```sh
composer run phpcs
composer run phpstan
composer run rector
composer run qa
```

## Links

- [Voys call notifications](https://www.voys.nl/hulp/freedom/gespreksnotificaties/)
- [libphonenumber for PHP](https://github.com/giggsey/libphonenumber-for-php)

## License

GPL-2.0-or-later
