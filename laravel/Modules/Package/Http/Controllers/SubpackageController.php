<?php

namespace Modules\Package\Http\Controllers;

use Exception;
use Illuminate\Support\Str;
use App\Traits\ResourceTrait;
use Illuminate\Http\Response;
use App\Traits\TranslationTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Rest\BaseController;
use Modules\Package\Services\SubpackageService;
use Modules\Package\Http\Requests\StoreSubpackageRequest;
use Modules\Generality\Http\Controllers\CustomUploadedFileController;
use Modules\Package\Repositories\Interfaces\SubpackageRepositoryInterface;
use Modules\Generality\Repositories\Interfaces\ResourceRepositoryInterface;
use Modules\Package\Repositories\Interfaces\AttributePackRepositoryInterface;

class SubpackageController extends BaseController
{
    use ResourceTrait, TranslationTrait;

    /**
     * @var object
     */
    protected $subpackageService;

    /**
     * @var $subpackageRepository
     */
    protected $subpackageRepository;

    /**
     * @var $resourceRepository
     */
    protected $resourceRepository;

    /**
     * @var object
     */
    protected $attributesPack;

    public function __construct(
        SubpackageService $subpackageService, 
        AttributePackRepositoryInterface $attributePackRepository,
        SubpackageRepositoryInterface $subpackageRepository,
        ResourceRepositoryInterface $resourceRepository,
    )        

    {
        $this->subpackageService = $subpackageService;
        $this->attributesPack = $attributePackRepository;
        $this->subpackageRepository = $subpackageRepository;
        $this->resourceRepository = $resourceRepository;
    }

    /**
    * @OA\Get(
    *       path="/api/v1/subpackages/{subpackage_id}",
    *       tags={"Packages"},
    *       summary="Get show detail subpackage - Detalle de un subpaquete",
    *       operationId="sub-subpackage",
    *       description="Return show detail subpackage - Retorna detalle de un subpaquete",
    *       security={{"bearerAuth": {} }},
    *       @OA\Parameter( ref="#/components/parameters/_locale" ),
    *       @OA\Parameter( ref="#/components/parameters/subpackage_id" ),
    *       @OA\Response(
    *           response=200,
    *           ref="#/components/responses/reponseSuccess"
    *       )
    * )
    */
    /**
     * Show the specified resource.
     * @param int $id
     */
    public function show($id)
    {
        $subpackage = $this->subpackageService->getSubpackage($id);

        return $this->sendResponse($subpackage, 'Detail subpackage');
    }

    /**
    * @OA\Post(
    *       path="/api/v1/subpackages/store-subpackage",
    *       tags={"Packages"},
    *       summary="Create new subpackage - Crear nuevo subpaquete",
    *       operationId="create-subpackage",
    *       description="Create new subpackage - Crear nuevo subpaquete",
    *       security={{"bearerAuth": {} }},
    *       @OA\Parameter( ref="#/components/parameters/_locale" ),
    *       @OA\RequestBody(
    *           required=true,
    *           description="Subpackage details",
    *           @OA\JsonContent(
    *              ref="#/components/schemas/SubpackageRequest"
    *          )
    *       ),
    *       @OA\Response(
    *           response=200,
    *           ref="#/components/responses/reponseSuccess"
    *       ),
    *       @OA\Response(
    *            response=422,
    *            ref="#/components/responses/unprocessableEntity"
    *       ),
    *       @OA\Response(
    *           response="401",
    *           ref="#/components/responses/notAuthenticated"
    *       )  
    * )
    * Store a new Subpackage.
    * @return Response
    * @param SubpackageRequest $request
    * 
    */
    public function storeSubpackage(StoreSubpackageRequest $request)
    {
        try {
            DB::beginTransaction();

            $subpackageData = $request->only('info');        
            
            if ($request->image) {
                $requestImage = $request->image;
                /* if (str_contains($requestImage, 'data:image')) { 
                    // $requestImage = Str::of($requestImage)->after(',');
                    $requestImage = explode( ',', $requestImage );
                } */
                // $decodedImage = new CustomUploadedFileController($requestImage, 'filename.jpg');
                $dataResource = $this->uploadResource('/plans', $request['image']);
                $resource = $this->resourceRepository->create($dataResource);
                if ($resource) {
                    $subpackageData['info']['image_id'] = $resource->id;
                }
            }

            $subpackage = $this->subpackageRepository->create($subpackageData['info']);

            if (isset($request['attributes'])) {
                foreach ($request['attributes'] as $key => $attributes) {
                    $subpackage->attributes()->attach($attributes['attribute_id'], ['quantity' => $attributes['quantity'], 'available' => $attributes['available']]);
                }
            }
            DB::commit();
            
        } catch (Exception $exception) {
            DB::rollback();
            return $this->sendError('Error by creating plans', $exception->getMessage());
        }        

        
    }
}


