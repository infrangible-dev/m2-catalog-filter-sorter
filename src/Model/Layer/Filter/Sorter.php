<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\CatalogFilterSorter\Model\Layer\Filter;

use Magento\Catalog\Model\Config;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Filter\AbstractFilter;
use Magento\Catalog\Model\Layer\Filter\Item\DataBuilder;
use Magento\Catalog\Model\Layer\Filter\ItemFactory;
use Magento\Catalog\Model\Layer\Search;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filter\StripTags;
use Magento\Store\Model\StoreManagerInterface;

/**
 * A group of the layered navigation with the sort options of the product list. It filters nothing: its items link to
 * the product list sorted by the option ("product_list_order").
 */
class Sorter extends AbstractFilter
{
    /** @var array<string, string>|null sort options of the store by attribute code */
    private ?array $availableOrders = null;

    /**
     * @param ItemFactory $filterItemFactory
     * @param StoreManagerInterface $storeManager
     * @param Layer $layer
     * @param DataBuilder $itemDataBuilder
     * @param Config $catalogConfig
     * @param StripTags $tagFilter
     * @param array $data
     * @phpstan-param array<string, mixed> $data
     *
     * @throws LocalizedException
     */
    public function __construct(
        ItemFactory $filterItemFactory,
        StoreManagerInterface $storeManager,
        Layer $layer,
        DataBuilder $itemDataBuilder,
        protected Config $catalogConfig,
        protected StripTags $tagFilter,
        array $data = []
    ) {
        parent::__construct(
            $filterItemFactory,
            $storeManager,
            $layer,
            $itemDataBuilder,
            $data
        );

        $this->setRequestVar('product_list_order');
    }

    /**
     * Returns the name of the group, translated.
     *
     * @return string
     */
    public function getName(): string
    {
        return (string)__('Sort By');
    }

    /**
     * Applies nothing: Magento's toolbar sorts the product list by the request parameter.
     *
     * @param RequestInterface $request
     *
     * @return $this
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function apply(RequestInterface $request): static
    {
        return $this;
    }

    /**
     * Returns the sort options of the store as items.
     *
     * @return array
     * @phpstan-return array<int, array<string, mixed>>
     */
    protected function _getItemsData(): array
    {
        foreach ($this->getAvailableOrders() as $attributeCode => $attributeLabel) {
            $this->itemDataBuilder->addItemData(
                (string)__($this->tagFilter->filter($attributeLabel)),
                $attributeCode,
                1
            );
        }

        return $this->itemDataBuilder->build();
    }

    /**
     * Returns the sort options of the product list by attribute code, like Magento's toolbar: those of the current
     * category (all attributes used for sorting unless the category limits them); on search result pages without
     * "Position" and with "Relevance".
     *
     * @return array<string, string>
     */
    public function getAvailableOrders(): array
    {
        if ($this->availableOrders === null) {
            $this->availableOrders = [];

            $layer = $this->getLayer();
            $category = $layer->getCurrentCategory();
            $orders = $category->getAvailableSortByOptions();

            if (! is_array($orders) || count($orders) === 0) {
                $orders = $this->catalogConfig->getAttributeUsedForSortByArray();
            }

            if ($layer instanceof Search) {
                unset($orders[ 'position' ]);
                $orders[ 'relevance' ] = __('Relevance');
            }

            foreach ($orders as $attributeCode => $attributeLabel) {
                $this->availableOrders[ (string)$attributeCode ] = (string)$attributeLabel;
            }
        }

        return $this->availableOrders;
    }
}
