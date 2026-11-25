# Changelog

## 2.0.0

### Breaking changes

- Requires Magento 2.4.6 or newer and PHP 8.2 or newer. Older versions of Magento and PHP 7.1 to 8.1 are no longer
  supported; use version 1.x for these.
- Requires `infrangible/m2-layout-update` 2.0, `infrangible/m2-core` 5.0 and `infrangible/m2-foundation` 3.0.
- All classes have native types and use constructor property promotion. `Model\Layer\Filter\Sorter::getName()` returns
  the translated name as string, like the filters of Magento; the plugin creates the group with its generated factory.

### Fixed

- The group offered the sort options of the store instead of those of the product list: on categories that limit
  their sort options it showed options that the toolbar does not offer (for example "Position"), and on search result
  pages "Position", which changes nothing there, while "Relevance" was missing. Now it offers the options of the
  toolbar.

### Added

- Translations in 24 locales.
- Unit tests.
