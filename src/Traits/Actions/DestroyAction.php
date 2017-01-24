<?php
namespace Nemesis\Rest\Traits\Actions;

use Illuminate\Http\Response;

/**
 * Delete entity
 *
 * Class DestroyAction
 * @package Nemesis\Rest\Traits\Actions
 *
 * @version 1.0.0
 * @author Bondarenko Kirill <bondarenko.kirill@gmail.com>
 */
trait DestroyAction
{
    /**
     * @param $id
     * @return Response
     */
    public function destroy($id)
    {
        if(isset($id['primaryKey'])) {
            $id = $id->$id['primaryKey'];
        }

        $model = $this->getItem($id, null, null, false);
        if ( ! $model) {
            return $this->respondNotFound();
        }

        $model->delete();

        return $this->setStatusCode(Response::HTTP_NO_CONTENT)->respond([]);
    }
}