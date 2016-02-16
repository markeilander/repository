<?php
namespace Eilander\Repository\Contracts;

/**
 * Interface Filterable
 * @package Eilander\Gateway\Contracts
 */
interface Filterable
{
    /**
     * add filters
     *
     * @param string $filters
     */
    function filters($filters = '');
}