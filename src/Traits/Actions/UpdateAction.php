<?php
namespace Nemesis\LaravelRest\Traits\Actions;

use Illuminate\Http\Response;

/**
 * Update entity
 *
 * Class UpdateAction
 * @package Nemesis\LaravelRest\Traits\Actions
 *
 * @version 1.0.0
 * @author Bondarenko Kirill <bondarenko.kirill@gmail.com>
 */
trait UpdateAction
{
    /**
     * @param $id
     * @return Response
     */
    public function update($id)
    {
        $request = $this->getRequestObject();

        if(isset($id['primaryKey'])) {
            $id = $id->$id['primaryKey'];
        }

        $model = $this->getItem($id, null, null, false);
        if (!$model) {
            return $this->respondNotFound();
        }

        $model->fill($request->all());
        $model->save();

        return $this->setStatusCode(Response::HTTP_CREATED)->respond($model);
    }
}