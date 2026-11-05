# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Changed

- Modernized the plugin according to the [SolveBeam WordPress Plugin Boilerplate](https://github.com/solvebeam/solvebeam-wordpress-plugin-boilerplate): PSR-4 classes in `psr-4/`, Jetpack Autoloader, PHPCS, PHPStan and Rector.
- Phone numbers of a person are now read from Orbis Contacts (`_orbis_contact_phone`) instead of the `_orbis_phone_number` and `_orbis_mobile_number` post meta.
- Requires PHP 8.3, WordPress 7.1 and the Orbis Contacts and Orbis Persons plugins.
- Deploy with Deployer through the shared `~/deployer.php` hosts.

### Removed

- The `debug_voys` query parameter that printed the SQL query.

## [1.0.0]

### Added

- Initial release.

[unreleased]: https://github.com/pronamic/wp-orbis-voys/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/pronamic/wp-orbis-voys/releases/tag/v1.0.0
