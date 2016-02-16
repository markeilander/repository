# Laravel 5 Repository

An laravel implementation of the Repository Pattern

```
The repository mediates between the data source layer and the business layers of the application. It queries the data source for the data, maps the data from the data source to a business entity, and persists changes in the business entity to the data source. A repository separates the business logic from the interactions with the underlying data source or Web service.
```

Further reading: http://ryantablada.com/post/two-design-patterns-that-will-make-your-applications-better

### Table of contents  

[TOC]

## Usage

### Step 1: Add the Service Provider

In your `config/app.php` add `Eilander\Repository\Providers\RepositoryServiceProvider:class` to the end of the `providers` array:


```
<?php
'providers' => [
    ...
    Eilander\Repository\Providers\RepositoryServiceProvider::class,
],

```

### Step 2: Add package to composer.json for autoloading

Add the package to the main `composer.json` for autoloading and run `composer dump-autoload`, like so:

```
<?php
   "autoload": {
        "classmap": [
            "database"
        ],
        "psr-4": {
            "App\\": "app/",
            "Eilander\\Repository\\": "../library/eilander/repository/src/"
        }
    },
```


```
#!json

composer dump-autoload
```

### Step 3: Bind interface to implementation in `ApiServiceProvider`

```
<?php
    public function register()
    {
        // Bind FruitRepository interface to Elasticsearch\FruitRepository implementation
        $this->app->bind('Modules\Api\Repositories\FruitRepository', 'Modules\Api\Repositories\Elasticsearch\FruitRepository');
        $this->app->bind('Modules\Api\Repositories\FruitRepository', 'Modules\Api\Repositories\Eloquent\FruitRepository');
    }
```

# Elasticsearch
## Methods

Search in Elasticsearch Repository

```
$posts = $this->repository->query($selection = '');
```

When u make use of the `ElasticsearchGateway` filters will be automagically parsed from the url in this format:

```
http://www.url.nl?filter={"provider":["kpn","vodafone"],"betweenDate":["2015-01-01","2015-12-31"]}
```

### betweenDate
BetweenDate is a special function that makes using datehistograms in Elasticseaerch a breeze.
It expects a start and end date and parses this to a fully functional datehistogram.

# Eloquent
## Methods

Find all results in Repository

```
$posts = $this->repository->all();
```

Find all results in Repository with pagination

```
$posts = $this->repository->paginate($limit = null, $columns = ['*']);
```

Find by result by id

```
$post = $this->repository->find($id);
```

Loading the Model relationships

```
$post = $this->repository->with(['state'])->find($id);
```

Find by result by field name

```
$posts = $this->repository->findByField('country_id','15');
```

Find by result by multiple fields

```
$posts = $this->repository->where([
    //Default Condition =
    'state_id'=>'10',
    'country_id'=>'15',
    //Custom Condition
    ['columnName','>','10']
]);
```

Create new entry in Repository

```
$post = $this->repository->create( Input::all() );
```

Update entry in Repository

```
$post = $this->repository->update( Input::all(), $id );
```

Delete entry in Repository

```
$this->repository->delete($id)
```

## Using the Filter
The repository code is smart enough to perform filtering and searchin from parameters sent in the request.

You can perform a dynamic search, filter the data and customize the queries.

### Enabling in your Repository

```
<?php

namespace App\Repositories\Eloquent;

use Eilander\Repository\Eloquent\BaseRepository;
use Eilander\Repository\Contracts\CacheableInterface;
use App\Repositories\FruitRepository as Repository;
use App\Presenters\FruitPresenter;
use App\Entities\Fruit;

/**
 * Class FruitRepository
 */
class FruitRepository extends BaseRepository implements Repository
{
     /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'email'
    ];
    ...
}
```

You can set the type of condition which will be used to perform the query, the default condition is "="

```
protected $fieldSearchable = [
    'name'=>'like',
    'email', // Default Condition "="
    'your_field'=>'condition'
];
```

### Examples
Request all data without filter by request

`http://stash.directsurvey.nl/api/v1/users`

```
[
    {
        "id": 1,
        "name": "John Doe",
        "email": "john@gmail.com",
        "created_at": "-0001-11-30 00:00:00",
        "updated_at": "-0001-11-30 00:00:00"
    },
    {
        "id": 2,
        "name": "Lorem Ipsum",
        "email": "lorem@ipsum.com",
        "created_at": "-0001-11-30 00:00:00",
        "updated_at": "-0001-11-30 00:00:00"
    },
    {
        "id": 3,
        "name": "Laravel",
        "email": "laravel@gmail.com",
        "created_at": "-0001-11-30 00:00:00",
        "updated_at": "-0001-11-30 00:00:00"
    }
]
```

Conducting research in the repository

`http://stash.directsurvey.nl/api/v1/users?search=John%20Doe`

OR

`http://stash.directsurvey.nl/api/v1/users?search=John&searchFields=name:like`

OR

`http://stash.directsurvey.nl/api/v1/users?search=john@gmail.com&searchFields=email:=`

OR

`http://stash.directsurvey.nl/api/v1/users?search=name:John Doe;email:john@gmail.com`

OR

`http://stash.directsurvey.nl/api/v1/users?search=name:John;email:john@gmail.com&searchFields=name:like;email:=`

```
[
    {
        "id": 1,
        "name": "John Doe",
        "email": "john@gmail.com",
        "created_at": "-0001-11-30 00:00:00",
        "updated_at": "-0001-11-30 00:00:00"
    }
]
```

Filtering fields
`http://stash.directsurvey.nl/api/v1/users?filter=id;name`

```
[
    {
        "id": 1,
        "name": "John Doe"
    },
    {
        "id": 2,
        "name": "Lorem Ipsum"
    },
    {
        "id": 3,
        "name": "Laravel"
    }
]
```

Sorting the results
`http://stash.directsurvey.nl/api/v1/users?filter=id;name&orderBy=id&sortedBy=desc`

```
[
    {
        "id": 3,
        "name": "Laravel"
    },
    {
        "id": 2,
        "name": "Lorem Ipsum"
    },
    {
        "id": 1,
        "name": "John Doe"
    }
]
```

Relationships

`http://stash.directsurvey.nl/api/v1/users?include=groups`

## Cache
Add a fully automated cache layer to your repository

### Cache Usage
Implements the interface `CacheableInterface` and use `CacheableRepository` Trait.

```
<?php

namespace App\Repositories\Eloquent;

use Eilander\Repository\Eloquent\BaseRepository;
use Eilander\Repository\Contracts\CacheableInterface;
use Eilander\Repository\Traits\CacheableRepository;
use App\Repositories\FruitRepository as Repository;
use App\Entities\Fruit;

/**
 * Class FruitRepository
 */
class FruitRepository extends BaseRepository implements Repository, CacheableInterface
{
    use CacheableRepository;

    ...
}
```

The repository cache will be cleared whenever an item is created, added or deleted.

### Cache Config
You can change cache settings in the config file `config/repository.php` or directly on your repository.

**config/repository.php**

```
    /*
    |--------------------------------------------------------------------------
    | Cache Config
    |--------------------------------------------------------------------------
    |
    */
    'cache'=>[
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
         | Cache Minutes
         |--------------------------------------------------------------------------
         |
         | Time of expiration cache
         |
         */
        'minutes'   => 30,

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
            'file' => 'cached-keys.json'
        ],

        /*
        |--------------------------------------------------------------------------
        | Methods Allowed
        |--------------------------------------------------------------------------
        |
        | methods cacheable : all, paginate, find, findByField, findWhere
        |
        | Ex:
        |
        | 'only'  =>['all','paginate'],
        |
        | or
        |
        | 'except'  =>['find'],
        */
         'allowed'=>[
             'only'  =>null,
             'except'=>null
         ],

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
                'create'=>true,
                'update'=>true,
                'delete'=>true,
            ]
        ]
    ]
```

**Directly in repository**

```
<?php
namespace App\Repositories\Eloquent;

use Eilander\Repository\Eloquent\BaseRepository;
use Eilander\Repository\Contracts\CacheableInterface;
use Eilander\Repository\Traits\CacheableRepository;
use App\Repositories\FruitRepository as Repository;
use App\Entities\Fruit;

/**
 * Class FruitRepository
 */
class FruitRepository extends BaseRepository implements Repository, CacheableInterface
{
    // Setting the lifetime of the cache to a repository specifically
    protected $cacheMinutes = 90;

    protected $cacheOnly = ['all', ...];
    //or
    protected $cacheExcept = ['find', ...];

    use CacheableRepository;

    ...
}
```

The cacheable methods are: all, paginate, find, findByField, findWhere.
Lifetime can also been set using: minutes, hours, days prior to one of the select methods in the controller.

```
<?php
namespace App\Http\Controllers;

use Eilander\Repository\Listeners\EloquentClearCache;
use App\Http\Controllers\Controller;
use App\Http\Requests\FruitRequest;
use App\Repositories\FruitRepository;

class FruitController extends Controller {

    /**
     * @var FruitRepository
     */
    protected $repository;

    public function __construct(FruitRepository $repository){
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // cache for 15 minutes
        $fruits = $this->repository->minutes(15)->paginate(50);
        or
        // cache for 6 hours
        $fruits = $this->repository->hours(6)->paginate(50);
        or
        // cache for 4 days
        $fruits = $this->repository->days(4)->paginate(50);


        return view('fruits.index', compact('fruits'));
    }
```