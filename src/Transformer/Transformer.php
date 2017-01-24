<?php
namespace Nemesis\Rest\Transformers;

use Illuminate\Database\Eloquent\Model;

/**
 * Преобразование полей модели к красивому виду для клиента
 *
 * Class Transformer
 * @package App\Transformers
 *
 * @author Bondarenko Kirill <bondarenko.kirill@gmail.com>
 */
class Transformer
{
    /**
     * Преобразование данных коллекций
     *
     * @param array $items
     * @param array $relations
     * @return array
     */
    public function transformCollection($items, $relations = [])
    {
        $transformCollections = [];
        foreach($items as $item) {
            $transformCollections[] = $this->transform($item, $relations);
        }

        return $transformCollections;
    }

    /**
     * Преобразование одной записи из коллекции
     *
     * @param array $item
     * @param array $relations
     * @return array
     */
    public function transform($item, $relations)
    {
        if ( ! is_array($item)) {
            $data = $item->toArray();
            $this->appendRelations($data, $item, $relations);
        } else {
            $data = $item;
        }

        return $data;
    }

    /**
     * Добавление связей к результатам
     *
     * @param array $data преобразованные в массив данные модели
     * @param Model $item объект модели
     * @param array $relations
     */
    public function appendRelations(&$data, $item, $relations)
    {
        foreach ($relations as $relation) {
            $data[$relation] = $item->$relation ? $item->$relation->toArray() : null;
        }
    }
}