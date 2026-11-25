<?php

declare(strict_types=1);

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2026 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace Infrangible\CatalogFilterSorter\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Phrase;

/**
 * The positions of the sort options in the layered navigation.
 */
class Direction implements OptionSourceInterface
{
    /**
     * Returns the positions: first or last group of the layered navigation.
     *
     * @return array
     * @phpstan-return list<array{value: string, label: Phrase}>
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => 'top', 'label' => __('Top')],
            ['value' => 'bottom', 'label' => __('Bottom')]
        ];
    }
}
