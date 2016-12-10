<?php

namespace Eilander\Repository\Traits\Elasticsearch;

/**
 * Class Filter.
 */
trait Filterable
{
    /**
     * set proper filter.
     */
    public function filter($filters)
    {
        if ($filters != '') {
            $this->filters = '
                "query": {
                    "query_string": {
                        "query": "'.$this->parseUrlFilter($filters).'"
                    }
                }
            ';
        }
    }

    /**
     * ParseFiler based on url data.
     *
     * @example
     * {"provider":["kpn","vodafone"],"betweenDate":["2015-01-01","2015-12-31"]}
     *
     * "(provider: Vodafone, KPN) AND timestamp: [2015-01-01 TO 2015-12-31] AND (soreg: simonly, regular) AND (doelgroep: prepaid, postpad)"
     */
    private function parseUrlFilter($filters)
    {
        $filters = json_decode($filters); // return std class
        $parsedFilters = [];
        if (is_object($filters)) {
            foreach ($filters as $name => $filter) {
                if ($name == 'betweenDate') {
                    $parsedFilters[] = $this->betweenDate($filter, $field = 'timestamp');
                } else {
                    $parsedFilters[] = $this->multi($filter, $name);
                }
            }

            return implode(' AND ', $parsedFilters);
        }
    }

    private function betweenDate($filter, $field = '', $parseExtendedBounds = true)
    {
        if ($field != '') {
            $from = head($filter);
            $till = last($filter);
            if (strtotime($from) > strtotime($till)) {
                $from = last($filter);
                $till = head($filter);
            }
            // set extended bounds based on date
            if ($parseExtendedBounds) {
                $this->extendedBounds($from, $till);
            }

            return $field.': ['.$from.' TO '.$till.']';
        }
    }

    private function multi($filter, $field = '')
    {
        if ($field != '') {
            $data = implode(',', $filter);

            return '('.$field.': '.$data.')';
        }
    }

    private function extendedBounds($min, $max)
    {
        $this->extendedBounds = '
            "extended_bounds": {
                "min": "'.$min.'",
                "max": "'.$max.'"
            }
        ';
    }
}
