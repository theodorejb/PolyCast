# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).


## [Unreleased]
### Changed
- Require PHP 7+
- Use GitHub Actions instead of Travis CI


## [1.0.0] Established Sincerity - 2015-10-25
### Fixed
- Bug where `safe_float` did not accept strings containing fractions less than one (issue [#1])

### Changed
- Improved test coverage
- Simplified readme


## [0.9.0] Simplified Safety - 2015-03-17
### Changed
- Renamed `int_castable`, `float_castable`, and `string_castable` functions
  to `safe_int`, `safe_float`, and `safe_string`.


## [0.8.0] Conventional Finality - 2015-03-16
### Changed
- Moved to `theodorejb\polycast` namespace


## [0.7.0] Binary Truthiness - 2015-03-08
### Changed
- Replaced `try_*` conversion functions with boolean `*_castable` functions.
- Improved readme documentation and added usage examples.

### Fixed
- Bug where float validation accepted trailing whitespace characters


## [0.6.0] Exceptional Transformation - 2014-11-19
### Added
- New `try_int`, `try_float`, and `try_string` functions which return `null` on failure

### Changed
- `to_int`, `to_float`, and `to_string` now throw a `CastException` instead of returning
  `null` if a value cannot be safely cast.
- A leading + sign is now accepted when casting to int or float.


## [0.5.0] Lossless Conversion - 2014-11-12
### Changed
- The functions now return `null` on cast failure instead of `false`.
- `to_int` and `to_float` now reject leading zeros and the plus sign,
  to adhere to the principle of zero data loss (`to_Y($a) !== null iff (X)(Y)$a === $a`).


## [0.4.1] Edge of Perfection - 2014-10-21
### Changed
- Match new PHP 7 implementation tests

### Fixed
 - Incorrect `to_float` hex string validation


## [0.4.0] Expedited Decoration - 2014-10-20
### Added
- Additional edge case tests

### Changed
- `to_int` and `to_float` no longer trim strings
- Improved `to_int` performance


## [0.3.0] Fragmentary Dismissal - 2014-10-09
### Changed
- Non-integral floats passed to `to_int` are now rejected, rather than cast.


## [0.2.0] Comprehensive Establishment - 2014-10-07
### Fixed
- `to_int` now ensures that the value isn't less than `PHP_INT_MIN`.
- `to_int` now allows values to be less than or equal to `PHP_INT_MAX` on non-64-bit platforms.


## [0.1.1] float_to_inty - 2014-10-06
- Initial pre-release

[Unreleased]: https://github.com/theodorejb/PolyCast/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/theodorejb/PolyCast/compare/v0.9.0...v1.0.0
[0.9.0]: https://github.com/theodorejb/PolyCast/compare/v0.8.0...v0.9.0
[0.8.0]: https://github.com/theodorejb/PolyCast/compare/v0.7.0...v0.8.0
[0.7.0]: https://github.com/theodorejb/PolyCast/compare/v0.6.0...v0.7.0
[0.6.0]: https://github.com/theodorejb/PolyCast/compare/v0.5.0...v0.6.0
[0.5.0]: https://github.com/theodorejb/PolyCast/compare/v0.4.1...v0.5.0
[0.4.1]: https://github.com/theodorejb/PolyCast/compare/v0.4.0...v0.4.1
[0.4.0]: https://github.com/theodorejb/PolyCast/compare/v0.3.0...v0.4.0
[0.3.0]: https://github.com/theodorejb/PolyCast/compare/v0.2.0...v0.3.0
[0.2.0]: https://github.com/theodorejb/PolyCast/compare/v0.1.1...v0.2.0
[0.1.1]: https://github.com/theodorejb/PolyCast/tree/v0.1.1

[#1]: https://github.com/theodorejb/PolyCast/issues/1
