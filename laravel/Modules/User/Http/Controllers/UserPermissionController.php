<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Rest\BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\AssignPermissionsRequest; 
// Repositories
use Modules\User\Services\PermissionService;

use Exception;

class UserPermissionController extends BaseController
{

    /**
     * @var object $permissionService
     */
    protected $permissionService;

    /**
     * Creates a new controller instance
     */
    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Retrieves a listing of all permission items set
     * @return Response
     * 
     * @OA\Get(
     *  path="/api/v1/users/permissions/list",
     *  tags={"User/Permissions"},
     *  summary="General permission items list",
     *  operationId="user-permissions-list",
     *  description="Returns a list of all permissions segmented on every module on where it's usage is needed",
     *  security={{"bearerAuth": {} }},
     *  @OA\Response(
     *      response=200,
     *      description="List of all permission items",
     *      @OA\JsonContent(
     *          ref="#/components/schemas/PermissionListResponse"
     *      )
     *  ),
     *   @OA\Response(
     *       response="401",
     *       ref="#/components/responses/notAuthenticated"
     *   )
     * )
    */
    public function index()
    {
        try {
            $permissions = $this->permissionService->listPermissions();

            return $this->sendResponse($permissions, 'Permission Item List');
        } catch (Exception $exception) {
            return $this->sendError('Error', $exception->getMessage());
        }
    }

    /**
     * Retrieves a list of all permisions related to the player
     * @return Response
     * 
     * @OA\Get(
     *  path="/api/v1/users/permissions/assigned",
     *  tags={"User/Permissions"},
     *  summary="General permission items list",
     *  operationId="user-assigned-permissions-list",
     *  description="Returns a list of all permissions assigned to the logged user",
     *  security={{"bearerAuth": {} }},
     *  @OA\Response(
     *      response=200,
     *      description="List of all permission items",
     *      @OA\JsonContent(
     *          ref="#/components/schemas/UserPermissionListResponse"
     *      )
     *  ),
     *  @OA\Response(
     *      response="401",
     *      ref="#/components/responses/notAuthenticated"
     *  )
     * )
    */
    public function userPermissions(Request $request)
    {
        try {
            $userId = Auth::user()->id;
            $permissions = $this->permissionService->listUserPermissions($userId, $request->entityId, $request->entityType);
            return $this->sendResponse($permissions, 'User Permissions List');
        } catch (Exception $exception) {
            return $this->sendError('Error', $exception->getMessage());
        }
    }

    /**
 * Asigna permisos a múltiples usuarios
 * 
 * @OA\Post(
 *  path="/api/v1/users/permissions/assignPermissions",
 *  tags={"User/Permissions"},
 *  summary="Assign permissions to multiple users",
 *  operationId="assign-permissions",
 *  security={{"bearerAuth": {}}},
 *  @OA\RequestBody(
 *      required=true,
 *      @OA\JsonContent(
 *          @OA\Property(property="userIds", type="array", @OA\Items(type="integer")),
 *          @OA\Property(property="permissions", type="array", @OA\Items(type="string")),
 *          @OA\Property(property="entityId", type="integer", description="ID of the entity"),
 *          @OA\Property(property="entityType", type="string", description="Type of the entity")
 *      )
 *  ),
 *  @OA\Response(
 *      response=200,
 *      description="Permissions assigned successfully",
 *      @OA\JsonContent(
 *          @OA\Property(property="message", type="string", example="Permissions assigned successfully"),
 *      )
 *  ),
 *  @OA\Response(
 *      response="401",
 *      ref="#/components/responses/notAuthenticated"
 *  ),
 *  @OA\Response(
 *      response="400",
 *      @OA\JsonContent(
 *          @OA\Property(property="error", type="string")
 *      )
 *  )
 * )
 */

public function assignPermissions(AssignPermissionsRequest $request)
{
    $permissions = $request->input('permissions');
    $userIds = $request->input('user_ids');
    $entityId = $request->input('entity_id');
    $entityType = $request->input('entity_type');

    // Llama al método para asignar permisos
    User::bulkAssignToUsers($permissions, $userIds, $entityId, $entityType);

    return response()->json(['message' => 'Permissions assigned successfully.']);
}


    // DEPRECATED: Function deprecated by update staff.
    // public function getPermissionsByEntity(Request $request)
    // {
    //     try {
    //         $entityType = $request->type ?? 'club';
    //         $entityId = $request->id;

    //         $permissions = $this->permissionService->listPermissionsByEntity($entityType, $entityId);

    //         return $this->sendResponse($permissions, 'User Permissions List By Entity');
    //     } catch (Exception $exception) {
    //         return $this->sendError('Error', $exception->getMessage());
    //     }
    // }
}
