<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\CatalogFilterSorter\Plugin\Catalog\Model\Layer;

use Infrangible\CatalogFilterSorter\Model\Layer\Filter\Sorter;
use Infrangible\CatalogFilterSorter\Model\Layer\Filter\SorterFactory;
use Infrangible\Core\Helper\Stores;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\FilterList as Subject;

/**
 * Adds the sort options as first or last group to the layered navigation of category and search result pages.
 */
class FilterList
{
    /** @var array<int, Sorter> the sort options by layer object */
    private array $sorters = [];

    /**
     * @param SorterFactory $sorterFactory
     * @param Stores $storeHelper
     */
    public function __construct(protected SorterFactory $sorterFactory, protected Stores $storeHelper)
    {
    }

    /**
     * Adds the sort options to the filters of the layer.
     *
     * @param Subject $subject
     * @param array $result
     * @param Layer $layer
     * @phpstan-param array<int, mixed> $result
     *
     * @return array
     * @phpstan-return array<int, mixed>
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterGetFilters(Subject $subject, array $result, Layer $layer): array
    {
        $layerId = spl_object_id($layer);

        $this->sorters[ $layerId ] ??= $this->sorterFactory->create(['layer' => $layer]);

        if ($this->storeHelper->getStoreConfig('infrangible_catalogfiltersorter/general/direction') === 'top') {
            array_unshift($result, $this->sorters[ $layerId ]);
        } else {
            $result[] = $this->sorters[ $layerId ];
        }

        return $result;
    }
}
