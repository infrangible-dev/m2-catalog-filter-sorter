<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\CatalogFilterSorter\Test\Unit\Model\Layer\Filter;

use Infrangible\CatalogFilterSorter\Model\Layer\Filter\Sorter;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Config;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Filter\Item\DataBuilder;
use Magento\Catalog\Model\Layer\Filter\ItemFactory;
use Magento\Catalog\Model\Layer\Search;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filter\StripTags;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;

/**
 * Tests the sort options as group of the layered navigation.
 */
#[AllowMockObjectsWithoutExpectations]
class SorterTest extends TestCase
{
    /**
     * Tests the request variable, the name and the sort options of the category, like Magento's toolbar.
     *
     * @return void
     * @throws LocalizedException
     */
    public function testSortOptionsOfCategory(): void
    {
        $sorter = $this->createSorter($this->createLayer(Layer::class, ['name' => 'Product Name', 'price' => 'Price']));

        self::assertSame('product_list_order', $sorter->getRequestVar());
        self::assertSame('Sort By', $sorter->getName());
        self::assertSame(['name' => 'Product Name', 'price' => 'Price'], $sorter->getAvailableOrders());
    }

    /**
     * Tests that search results are sorted by relevance instead of position, like Magento's toolbar.
     *
     * @return void
     * @throws LocalizedException
     */
    public function testSortOptionsOfSearch(): void
    {
        $sorter = $this->createSorter(
            $this->createLayer(Search::class, ['position' => 'Position', 'name' => 'Product Name'])
        );

        self::assertSame(['name' => 'Product Name', 'relevance' => 'Relevance'], $sorter->getAvailableOrders());
    }

    /**
     * Tests that the options of the store are used when the category has none.
     *
     * @return void
     * @throws LocalizedException
     */
    public function testSortOptionsOfStore(): void
    {
        $catalogConfig = $this->createStub(Config::class);
        $catalogConfig->method('getAttributeUsedForSortByArray')->willReturn(['position' => 'Position']);

        $sorter = $this->createSorter($this->createLayer(Layer::class, []), $catalogConfig);

        self::assertSame(['position' => 'Position'], $sorter->getAvailableOrders());
    }

    /**
     * Tests that the group filters nothing.
     *
     * @return void
     * @throws LocalizedException
     */
    public function testApplyFiltersNothing(): void
    {
        $layer = $this->createMock(Layer::class);
        $layer->expects($this->never())->method('getProductCollection');
        $layer->expects($this->never())->method('getState');

        $sorter = $this->createSorter($layer);

        self::assertSame($sorter, $sorter->apply($this->createStub(RequestInterface::class)));
    }

    /**
     * Returns a layer whose current category has the sort options.
     *
     * @param class-string<Layer> $class
     * @param array<string, string> $orders
     *
     * @return Layer
     */
    private function createLayer(string $class, array $orders): Layer
    {
        $category = $this->createStub(Category::class);
        $category->method('getAvailableSortByOptions')->willReturn($orders);

        $layer = $this->createStub($class);
        $layer->method('getCurrentCategory')->willReturn($category);

        return $layer;
    }

    /**
     * Returns the group of the layer.
     *
     * @param Layer $layer
     * @param Config|null $catalogConfig
     *
     * @return Sorter
     * @throws LocalizedException
     */
    private function createSorter(Layer $layer, ?Config $catalogConfig = null): Sorter
    {
        return new Sorter(
            $this->createStub(ItemFactory::class),
            $this->createStub(StoreManagerInterface::class),
            $layer,
            new DataBuilder(),
            $catalogConfig ?? $this->createStub(Config::class),
            new StripTags(new Escaper())
        );
    }
}
