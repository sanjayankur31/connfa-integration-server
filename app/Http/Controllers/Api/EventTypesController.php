<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Repositories\Event\TypeRepository;
use App\Transformers\Event\TypeTransformer;

class EventTypesController extends ApiController
{
    /**
     * Get list of Event Types
     *
     * @SWG\Get(
     *     path="/{conference_alias}/getTypes",
     *     summary="Get all event types",
     *     tags={"Event"},
     *     description="Returns all event types, since 'If-Modified-Since'",
     *     operationId="index",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Conference alias",
     *         in="path",
     *         name="conference_alias",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="If-Modified-Since",
     *         in="header",
     *         required=false,
     *         type="string",
     *         description="Date, for example: Tue, 4 Apr 2017 09:50:24 +0000",
     *         default="Tue, 4 Apr 2017 09:50:24 +0000"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Schema(
     *              @SWG\Property(
     *                 property="types",
     *                 type="array",
     *                 @SWG\Items(ref="#/definitions/Type")
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *         response=304,
     *         description="No updates"
     *     )
     * )
     *
     * @param TypeRepository $repository
     * @return \Dingo\Api\Http\Response
     */
    public function index(TypeRepository $repository)
    {
        $types = $repository->getTypesWithDeleted($this->getConference()->id, $this->since);
        $this->checkModified($types);

        return $this->response->collection($types, new TypeTransformer(), ['key' => 'types']);
    }
}
