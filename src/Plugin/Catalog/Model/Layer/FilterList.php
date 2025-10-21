<?php

declare(strict_types=1);

namespace Infrangible\CatalogFilterSorter\Plugin\Catalog\Model\Layer;

use Infrangible\CatalogFilterSorter\Model\Layer\Filter\Sorter;
use Infrangible\Core\Helper\Instances;
use Infrangible\Core\Helper\Stores;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Filter\AbstractFilter;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class FilterList
{
    /** @var Instances */
    protected $instanceHelper;

    /** @var Stores */
    protected $storeHelper;

    private $attributeSetFilter;

    public function __construct(Instances $instanceHelper, Stores $storeHelper)
    {
        $this->instanceHelper = $instanceHelper;
        $this->storeHelper = $storeHelper;
    }

    /**
     * @param array|AbstractFilter[] $result
     *
     * @return array|AbstractFilter[]
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterGetFilters(Layer\FilterList $subject, array $result, Layer $layer): array
    {
        if ($this->attributeSetFilter === null) {
            $this->attributeSetFilter = $this->instanceHelper->getInstance(
                Sorter::class,
                ['layer' => $layer]
            );
        }

        if ($this->storeHelper->getStoreConfig('infrangible_catalogfiltersorter/general/direction') === 'top') {
            array_unshift(
                $result,
                $this->attributeSetFilter
            );
        } else {
            $result[] = $this->attributeSetFilter;
        }

        return $result;
    }
}
