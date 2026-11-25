<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\CatalogFilterSorter\Test\Unit\Plugin\Catalog\Model\Layer;

use Infrangible\CatalogFilterSorter\Model\Layer\Filter\Sorter;
use Infrangible\CatalogFilterSorter\Model\Layer\Filter\SorterFactory;
use Infrangible\CatalogFilterSorter\Plugin\Catalog\Model\Layer\FilterList;
use Infrangible\Core\Helper\Stores;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\FilterList as Subject;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;

/**
 * Tests the position of the sort options in the layered navigation.
 */
#[AllowMockObjectsWithoutExpectations]
class FilterListTest extends TestCase
{
    /**
     * Tests the group at the top and at the bottom, and that each layer gets its own group once.
     *
     * @return void
     */
    public function testAfterGetFilters(): void
    {
        $direction = 'bottom';

        $storeHelper = $this->createStub(Stores::class);
        $storeHelper->method('getStoreConfig')->willReturnCallback(
            static function () use (&$direction): string {
                return $direction;
            }
        );

        $factory = $this->createMock(SorterFactory::class);
        $factory->expects($this->exactly(2))->method('create')->willReturnCallback($this->createSorter(...));

        $plugin = new FilterList($factory, $storeHelper);
        $subject = $this->createStub(Subject::class);
        $categoryLayer = $this->createStub(Layer::class);

        $bottom = $plugin->afterGetFilters($subject, ['category', 'price'], $categoryLayer);

        self::assertSame(['category', 'price'], array_slice($bottom, 0, 2));
        self::assertInstanceOf(Sorter::class, $bottom[ 2 ]);

        $direction = 'top';
        $top = $plugin->afterGetFilters($subject, ['category', 'price'], $categoryLayer);

        self::assertSame($bottom[ 2 ], $top[ 0 ]);
        self::assertSame(['category', 'price'], array_slice($top, 1));

        $searchLayer = $this->createStub(Layer::class);

        self::assertNotSame($top[ 0 ], $plugin->afterGetFilters($subject, [], $searchLayer)[ 0 ]);
    }

    /**
     * Returns a group with the sort options.
     *
     * @return Sorter
     */
    private function createSorter(): Sorter
    {
        return $this->createStub(Sorter::class);
    }
}
