<?php
namespace Nemesis\LaravelRest\Transformers;

use Illuminate\Database\Eloquent\Model;

/**
 * Example
 *
 * Class ExampleTransformer
 * @package App\Transformers
 *
 * @author Bondarenko Kirill <bondarenko.kirill@gmail.com
 */
class ExampleTransformer extends Transformer
{

    /**
     * Преобразование одной записи из коллекции
     *
     * @param Model $item
     * @param array $relations
     * @return array
     */
    public function transform($item, $relations = [])
    {
        $data = [
            'id' => $item->id,
            'name' => $item->name,
            'description' => $item->description,
        ];

        $this->appendRelations($data, $item, $relations);

        return $data;
    }
}
