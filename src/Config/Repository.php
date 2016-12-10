<?php
/*
|--------------------------------------------------------------------------
| Eilander Repository Config
|--------------------------------------------------------------------------
|
|
*/
return [
    /*
    |--------------------------------------------------------------------------
    | Repository Pagination Limit Default
    |--------------------------------------------------------------------------
    |
    */
    'pagination'=> [
        'limit'=> 15,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Config
    |--------------------------------------------------------------------------
    |
    */
    'cache'=> [
        /*
         |--------------------------------------------------------------------------
         | Cache Status
         |--------------------------------------------------------------------------
         |
         | Enable or disable cache
         |
         */
        'enabled'   => true,

        /*
         |--------------------------------------------------------------------------
         | Cache Store Path
         |--------------------------------------------------------------------------
         |
         | Path to store cache keys in..
         |
         */
        'store'   => [
            'path' => storage_path('eilander/repository/'),
            'file' => 'cached-keys.json',
        ],
        'search'=> [
            /*
            |--------------------------------------------------------------------------
            | Methods Allowed
            |--------------------------------------------------------------------------
            |
            | methods cacheable : search, filter
            |
            | Ex:
            |
            | 'only'  =>['search'],
            |
            | or
            |
            | 'except'  =>['filter'],
            */
             'allowed'=> [
                 'only'  => null,
                 'except'=> null,
             ],

             /*
             |--------------------------------------------------------------------------
             | Cache Days
             |--------------------------------------------------------------------------
             |
             | Time of expiration cache
             |
             */
            'minutes' => 1440,

            /*
              |--------------------------------------------------------------------------
              | Cache Clean Listener
              |--------------------------------------------------------------------------
              |
              |
              |
              */
            'clean'     => [

                /*
                  |--------------------------------------------------------------------------
                  | Enable clear cache on repository changes
                  |--------------------------------------------------------------------------
                  |
                  */
                'enabled' => true,

                /*
                  |--------------------------------------------------------------------------
                  | Actions in Repository
                  |--------------------------------------------------------------------------
                  |
                  | search : Clean cache when new search is done
                  |
                  */
                'on' => [
                    'search'=> true,
                ],
            ],
        ],
        'repository'=> [
            /*
            |--------------------------------------------------------------------------
            | Methods Allowed
            |--------------------------------------------------------------------------
            |
            | methods cacheable : all, find, where
            |
            | Ex:
            |
            | 'only'  =>['all', 'find'],
            |
            | or
            |
            | 'except'  =>['where'],
            */
             'allowed'=> [
                 'only'  => null,
                 'except'=> null,
             ],

             /*
             |--------------------------------------------------------------------------
             | Cache Minutes
             |--------------------------------------------------------------------------
             |
             | Time of expiration cache
             |
             */
            'minutes' => 30,

            /*
              |--------------------------------------------------------------------------
              | Cache Clean Listener
              |--------------------------------------------------------------------------
              |
              |
              |
              */
            'clean'     => [

                /*
                  |--------------------------------------------------------------------------
                  | Enable clear cache on repository changes
                  |--------------------------------------------------------------------------
                  |
                  */
                'enabled' => true,

                /*
                  |--------------------------------------------------------------------------
                  | Actions in Repository
                  |--------------------------------------------------------------------------
                  |
                  | create : Clear Cache on create Entry in repository
                  | update : Clear Cache on update Entry in repository
                  | delete : Clear Cache on delete Entry in repository
                  |
                  */
                'on' => [
                    'create'=> true,
                    'update'=> true,
                    'delete'=> true,
                ],
            ],
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Criteria Config
    |--------------------------------------------------------------------------
    |
    | Settings of request parameters names that will be used by Criteria
    |
    */
    'criteria'=> [
        /*
        |--------------------------------------------------------------------------
        | Accepted Conditions
        |--------------------------------------------------------------------------
        |
        | Conditions accepted in consultations where the Criteria
        |
        | Ex:
        |
        | 'acceptedConditions'=>['=','like']
        |
        | $query->where('foo','=','bar')
        | $query->where('foo','like','bar')
        |
        */
        'acceptedConditions'=> [
            '=', 'like',
        ],
        /*
        |--------------------------------------------------------------------------
        | Request Params
        |--------------------------------------------------------------------------
        |
        | Request parameters that will be used to filter the query in the repository
        |
        | Params :
        |
        | - search : Searched value
        |   Ex: http://eilander.local/?search=lorem
        |
        | - searchFields : Fields in which research should be carried out
        |   Ex:
        |    http://eilander.local/?search=lorem&searchFields=name;email
        |    http://eilander.local/?search=lorem&searchFields=name:like;email
        |    http://eilander.local/?search=lorem&searchFields=name:like
        |
        | - filter : Fields that must be returned to the response object
        |   Ex:
        |   http://eilander.local/?search=lorem&filter=id,name
        |
        | - orderBy : Order By
        |   Ex:
        |   http://eilander.local/?search=lorem&orderBy=id
        |
        | - sortedBy : Sort
        |   Ex:
        |   http://eilander.local/?search=lorem&orderBy=id&sortedBy=asc
        |   http://eilander.local/?search=lorem&orderBy=id&sortedBy=desc
        |
        */
        'params'=> [
            'search'        => 'search',
            'searchFields'  => 'searchFields',
            'filter'        => 'filter',
            'orderBy'       => 'orderBy',
            'sortedBy'      => 'sortedBy',
            'with'          => 'include',
        ],
    ],
];
