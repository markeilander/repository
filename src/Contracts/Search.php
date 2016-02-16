<?php
namespace Eilander\Repository\Contracts;

/**
 * Interface Search
 * @package Eilander\Repository\Contracts
 */
interface Search extends Repository
{
    /**
     * Filter/query search results
     * @param array|string $request
     * @return mixed
     */
    public function search($request = array('*'));
}