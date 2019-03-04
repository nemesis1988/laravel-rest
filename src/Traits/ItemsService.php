<?php
namespace Nemesis\LaravelRest\Traits;

use App\Http\Controllers\ApiController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Transformers\ITransformer;
use Illuminate\Support\Facades\DB;

/**
 * Базовый класс для сервисов. Если метода нет в сервисе - будет вызван метод из репозитория
 *
 * Class BaseService
 * @package App\Services
 *
 * @author Bondarenko Kirill <bondarenko.kirill@gmail.com>
 */
trait ItemsService
{
    /**
     * @var int
     */
    protected static $defaultPaginate = 10;

    /**
     * Условия фильтрации по умолчанию
     *
     * @param Builder $query
     * @return Builder
     */
    public function baseQueryFilter($query)
    {
        return $query;
    }

    /**
     * Получение коллекций
     *
     * @param Request $request
     * @param array $params параметры для фильтра
     * @param bool $paginate
     * @return
     */
    public function getItems(Request $request = null, $params = [], $paginate = true)
    {
        $model = $this->modelClass;

        $query = $this->isModelUseFilter() ? $model::setFilterAndRelationsAndSort($request, $params) : new $model;
        $query = $this->baseQueryFilter($query);

        $items = $query->paginate($this->getPaginate($request));

        $collection = $items->toArray();

        if ($this->transformer) {
            $transformer = new $this->transformer;
            $collection['data'] = $transformer->transformCollection($items->items(), $this->getRelations($request));
        } else {
            $collection['data'] = $items->items();
        }

        return $collection;
    }

    /**
     * Получение сущностей
     *
     * @param $id
     * @return bool
     */
    public function getItem($id, Request $request = null, $params = [], $needTransform = true)
    {
        $modelClass = $this->modelClass;

        // небольшой костыль
        try {
            $query = $this->isModelUseFilter() ?
                $modelClass::setFilterAndRelationsAndSort($request, $params) :
                new $modelClass;

            $query = $this->baseQueryFilter($query);

            $model = $query->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return false;
        }

        if ($needTransform && $this->transformer) {
            $transformer = new $this->transformer;
            $data = $transformer->transform($model, $this->getRelations($request));
        } else {
            $data = $model;
        }

        return $data;
    }

    /**
     * Получить список связей из expand
     *
     * @param Request $request
     * @return array
     */
    public function getRelations(Request $request = null)
    {
        $relations = [];

        if ($request && $request->get('expand')) {
            $relations = explode(',', $request->get('expand'));
        }

        return $relations;
    }

    /**
     * Получить пагинацию
     *
     * @param Request|null $request
     * @return int|mixed
     */
    public function getPaginate(Request $request = null)
    {
        return ($request && $request->has('limit')) ? $request->get('limit') : static::$defaultPaginate;
    }

    /**
     * check used filter in model
     *
     * @return bool
     */
    protected function isModelUseFilter()
    {
        return method_exists($this->modelClass, 'scopeSetFilterAndRelationsAndSort');
    }
}
