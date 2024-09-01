<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserManagement\RoleRequest;
use Auth;
use App\Services\UserManagement\RoleService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    private Permission $permissions;
    private Role $roles;
    private RoleService $roleService;

    public function __construct(Permission $permissions, Role $roles, RoleService $roleService)
    {
        $this->permissions = $permissions;
        $this->roles = $roles;
        $this->roleService = $roleService;
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
        $this->middleware('permission:role-create', ['only' => ['create','store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:role-delete', ['onlhttp://localhost:8000/user_management/roley' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View|Factory
    {

        $roles = $this->roles->get();
        $permissions = $this->permissions->get();
        return view('backEnd.userManagement.role.index', compact('roles','permissions'));

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\RoleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request): RedirectResponse
    {
        return $this->roleService->save($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role): JsonResponse
    {
        if(!empty($role)){
            $idPermission = [];
            if(!empty($role->permissions)){
                foreach($role->permissions as $role_permission){
                    $idPermission[] = $role_permission->id;
                }
            }
            $permissions = $this->permissions->get();

            if(!empty($permissions)){
                foreach($permissions as $key => $permission){
                    $permissions[$key]->checked = '-';
                    if(in_array($permission->id, $idPermission)){
                        $permissions[$key]->checked = 'checked';
                    }
                }
            }
            return response()->json([
                'status'  => true,
                'data'    => $role,
                'permissions' => $permissions,
                'message' => 'Data berhasil diambil.',
            ], JsonResponse::HTTP_OK);
        }else{
            return response()->json([
                'message' => 'Data Tidak Ada.',
                'data'    => [],
                'roles'   => [],
                'permissions' => [],
                'status' => false,
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        return $this->roleService->save($request, $role);
    }

    public function destroy(Role $role)
    {
        $service = $this->roleService->delete($role);
        if($service){
            return response()->json([
                'message' => 'Data berhasil dihapus.',
                'status' => true,
            ], JsonResponse::HTTP_OK);
        }else{
            return response()->json([
                'message' => 'Data Gagal Di hapus.',
                'status' => false,
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}
