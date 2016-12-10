<?php

namespace Eilander\Repository\Contracts;

/**
 * Interface Filterable.
 */
interface Filterable
{
    /**
     * add filters.
     *
     * @param string $filters
     */
    public function filters($filters = '');
}
