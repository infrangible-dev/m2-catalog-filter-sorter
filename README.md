# Infrangible Catalog Filter Sorter

Adds the sort options of the product list ("Sort By") as a group to the layered navigation of category and search
result pages, so shoppers find sorting next to the filters, for example on mobile devices or when the filters are
shown in a panel.

## How it works

- The group offers the same options as the sort selector of Magento's toolbar: on category pages those of the category
  (all attributes used for sorting, unless the category limits them under "Available Product Listing Sort By"), on
  search result pages "Relevance" instead of "Position".
- Choosing an option sorts the product list and keeps the applied filters and the search term.
- The group shows no product counts.
- The sort selector of the toolbar can be hidden, so sorting is offered only once.

## Configuration

Stores > Configuration > Infrangible Solutions > Catalog Filter Sorter > General (per website and store view):

- **Direction:** the group is the first (**Top**) or the last (**Bottom**, default) group of the layered navigation.
- **Show Toolbar:** whether the sort selector of the product list toolbar stays visible (default: **No**).

The positions and the hidden toolbar are set with CSS classes through
[Infrangible Layout Update](https://www.infrangible.dev/modules/layout-update):
`sidebar-main-sorter-top` or `sidebar-main-sorter-bottom` on the sidebar, `toolbar-no-sorter` on the main column.
[Infrangible Catalog Filter Sorter Hyvä Theme](https://www.infrangible.dev/modules/catalog-filter-sorter-hyva) brings
the group to stores with the Hyvä theme.

## Requirements

- Magento Open Source or Adobe Commerce 2.4.6 or newer
- PHP 8.1 or newer

## Installation

```bash
composer require infrangible/m2-catalog-filter-sorter
bin/magento module:enable Infrangible_CatalogFilterSorter
bin/magento setup:upgrade
```

In production mode, also run `bin/magento setup:di:compile` and `bin/magento setup:static-content:deploy`.

## License

Infrangible Catalog Filter Sorter is licensed under the MIT License - see the LICENSE file for details.
