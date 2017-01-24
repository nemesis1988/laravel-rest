<?php
namespace Nemesis\Rest\Traits;

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
     * Получение коллекций
     *
     * @param Request $request
     * @param array $params параметры для фильтра
     * @return
     */
    public function getItems(Request $request = null, $params = [])
    {
        $model = $this->modelClass;

        $query = $model::setFilterAndRelationsAndSort($request, $params);

        if ($this->transformer) {
            $transformer = new $this->transformer;
            $items = $transformer->transformCollection($query->get(), $this->getRelations($request));
        } else {
            $items = $query->get();
        }

        return $items;
    }

    /**
     * Получение сущностей
     *
     * @param $id
     * @return bool
     */
    public function getItem($id, Request $request = null, $params = [], $needTransform = true)
    {
        $model = $this->modelClass;

        // небольшой костыль
        try {
            $model = $model::setFilterAndRelationsAndSort($request, $params)->findOrFail($id);
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
        return ($request && $request->has('limit')) ? $request->get('limit') : ApiController::DEFAULT_PAGINATE;
    }
}
