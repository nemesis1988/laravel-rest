<?php
namespace Nemesis\LaravelRest\Traits\Actions;

use Illuminate\Http\Request;

/**
 * Get entity
 *
 * Class ShowAction
 * @package Nemesis\LaravelRest\Traits\Actions
 *
 * @version 1.0.0
 * @author Bondarenko Kirill <bondarenko.kirill@gmail.com>
 */
trait ShowAction
{
    /**
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function show($id, Request $request)
    {
        if(isset($id['primaryKey'])) {
            $id = $id->$id['primaryKey'];
        }

        $item = $this->getItem($id, $request);
        if ($item) {
            return $this->respond($item);
        } else {
            return $this->respondNotFound();
        }
    }
}