<?php

namespace Eilander\Repository\Traits\Elasticsearch;

/**
 * Parser.
 */
trait Parser
{
    /**
     * set extended bounds for use with date histogram.
     */
    protected function dateHistogram($field = 'timestamp', $format = 'yyyy-MM-dd', $interval = 'month')
    {
        return '
            "date_histogram": {
                "field": "'.$field.'",
                "interval": "'.$interval.'",
                "format": "'.$format.'",
                "min_doc_count": 0,
                '.$this->extendedBounds.'
            }
        ';
    }

    /**
     * Search query in elasticsearch.
     *
     * @param array|string $request
     *
     * @return mixed
     */
    protected function body($selection = '')
    {
        if (is_array($selection)) {
            $selection = json_encode($selection);
        }
        // return json string with or without filters
        if (trim($this->filters) != '') {
            return '{
                '.$this->filters.',
                '.$selection.'
            }';
        } else {
            return '{
                '.$selection.'
            }';
        }
    }
}
