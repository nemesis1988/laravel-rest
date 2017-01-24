## Laravel REST actions

Package included CRUD action traits, service trait, ApiController and transformer

How to install:

```bash
composer require nemesis/laravel-rest
```

or add in **composer.json** record to require block **require**:

```
For Laravel >=5.3
```json
"nemesis/laravel-rest": "*"
```

## Using

Extend You controllers from Nemesis\Rest\Controllers\ApiController and use action traits for him/

In Nemesis\Rest\Traits\Actions\* isset CRUD operations for controller

You need to set $modelClass variable in controller and using action traits

```php
    class SomeController
    {
        use IndexAction, ShowAction, StoreAction, UpdateAction, DestroyAction;
    
        protected $modelClass = SomeModel::class;    
    }
```

If any of the methods that do not need it, just remove it


## Filter using

Package include [filter](https://github.com/nemesis1988/FilterAndSorting|filter).
