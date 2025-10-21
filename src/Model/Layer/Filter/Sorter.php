<?php

declare(strict_types=1);

namespace Infrangible\CatalogFilterSorter\Model\Layer\Filter;

use Magento\Catalog\Model\Config;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Filter\AbstractFilter;
use Magento\Catalog\Model\Layer\Filter\Item\DataBuilder;
use Magento\Catalog\Model\Layer\Filter\ItemFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Filter\StripTags;
use Magento\Framework\Phrase;
use Magento\Store\Model\StoreManagerInterface;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Sorter extends AbstractFilter
{
    /** @var Config */
    protected $catalogConfig;

    /** @var StripTags */
    protected $tagFilter;

    /** @var array */
    private $availableOrder;

    public function __construct(
        ItemFactory $filterItemFactory,
        StoreManagerInterface $storeManager,
        Layer $layer,
        DataBuilder $itemDataBuilder,
        Config $catalogConfig,
        StripTags $tagFilter,
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

        $this->catalogConfig = $catalogConfig;
        $this->tagFilter = $tagFilter;
    }

    public function getName(): Phrase
    {
        return __('Sort By');
    }

    public function apply(RequestInterface $request): Sorter
    {
        return $this;
    }

    protected function _getItemsData(): array
    {
        foreach ($this->getAvailableOrders() as $attributeCode => $attributeLabel) {
            $this->itemDataBuilder->addItemData(
                __($this->tagFilter->filter($attributeLabel)),
                $attributeCode,
                1
            );
        }

        return $this->itemDataBuilder->build();
    }

    public function getAvailableOrders(): array
    {
        if ($this->availableOrder === null) {
            $this->availableOrder = $this->catalogConfig->getAttributeUsedForSortByArray();
        }

        return $this->availableOrder;
    }
}
