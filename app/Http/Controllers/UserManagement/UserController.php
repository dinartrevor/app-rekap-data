<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserManagement\UserRequest;
use App\Models\User;
use App\Services\UserManagement\UserService;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    private Role $roles;
    private User $users;
    private UserService $userService;


    public function __construct(Role $roles, User $users, UserService $userService)
    {
        $this->roles = $roles;
        $this->users = $users;
        $this->userService = $userService;
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
        $this->middleware('permission:user-create', ['only' => ['create','store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View|Factory
    {
        $users = $this->users->notSuperAdmin()->get();
        $roles  = $this->roles->where('id', '!=', 1)->get();
        return view('backEnd.userManagement.user.index', compact('users','roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request): RedirectResponse
    {
        return $this->userService->save($request);
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user): JsonResponse
    {
        if(!empty($user)){
            $roles = $this->roles->where('id', '!=', 1)->get();
            $idRole = [];
            if($user->roles->isNotEmpty()){
                foreach($user->roles as $user_role){
                    $idRole[] = $user_role->id;
                }
            }
            if($roles->isNotEmpty()){
                foreach($roles as $key => $role){
                    $roles[$key]->selected = '';
                    if(in_array($role->id, $idRole)){
                        $roles[$key]->selected = 'selected';
                    }
                }
            }
            return response()->json([
                'status'  => true,
                'message' => 'Data berhasil diambil.',
                'data'    => $user,
                'roles'   => $roles,
            ], JsonResponse::HTTP_OK);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ada.',
                'data'    => [],
                'roles'   => [],
            ], JsonResponse::HTTP_NOT_FOUND);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user): RedirectResponse
    {
        return $this->userService->save($request, $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user): JsonResponse
    {
        $service = $this->userService->delete($user);
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

    public function getStruktur(): JsonResponse
    {
		$data = $this->users->notSuperAdmin()->get();
		if ($data->isNotEmpty()) {
            $new_data = [];
			foreach ($data as $key => $value) {
                $new_data[$value->id] = array();
				$new_data[$value->id]['id'] = $value->id;
				$new_data[$value->id]['name'] = $value->name;
				$new_data[$value->id]['parent_id'] = $value->parent_id;
			}

			$tree_menu = array(
                'items' => array(),
				'parents' => array(),
			);
			foreach ($new_data as $a) {
                $tree_menu['items'][$a['id']] = $a;
				$tree_menu['parents'][$a['parent_id']][] = $a['id'];
			}

			$tree =  $this->treeStruktur($tree_menu, "");
            return response()->json([
                'message' => 'Berhasil Mengambil Data',
                'status' => true,
                'data' => $tree,
            ], JsonResponse::HTTP_OK);
		} else {
            return response()->json([
                'message' => 'Gagal Mengambil Data',
                'status' => true,
                'data' => [],
            ], JsonResponse::HTTP_OK);
		}
	}

	private function treeStruktur($tree_menu, $parent)
	{
		$html = "";
		if (isset($tree_menu['parents'][$parent])) {
			foreach ($tree_menu['parents'][$parent] as $itemId) {
				$id 		= $tree_menu['items'][$itemId]['id'];
				$name 		= $tree_menu['items'][$itemId]['name'];
				$parent_id 	= $tree_menu['items'][$itemId]['parent_id'];
				$class = '';

				if (!isset($tree_menu['parents'][$itemId])) {
					$html  .= '<li>';
					$html  .= '<span>'.$name.'</span>';
					$html .= '</li>';
				}else{
					if (empty($parent)) {
						$html .= '<ul class="tree w-100">';
					}
						$html  .= '<li>';
						$html  .= '<span>'.$name.'</span>';
						if(isset($tree_menu['parents'][$id])){
							$html .= '<ul>';
							$html  .= $this->treeStruktur($tree_menu, $id);
							$html .= '</ul>';
						}
						$html .= '</li>';
					if (empty($parent)) {
						$html .= '</ul>';
					}
				}
			}
		}
		return $html;
	}

}
