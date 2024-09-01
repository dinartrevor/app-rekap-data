<?php

namespace App\Services\UserManagement;

use App\Http\Requests\UserManagement\RoleRequest;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function save(RoleRequest $request, ?Role $roles = null): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            if ($roles) {
                $roles->update($data);
            } else {
                $roles = Role::create($data);
            }
            if(!empty($roles['id'])){
                $roles->syncPermissions($data['permission_id']);
                DB::commit();
                Log::channel('log-transaction')->info(($roles->wasRecentlyCreated ? 'Role Created!' : 'Role Updated!'), ['User' =>  Auth::user()->name]);
                return redirect()->route('role.index')->with('success', 'Data berhasil ' . ($roles->wasRecentlyCreated ? 'ditambahkan!' : 'diubah!'));
            }

        } catch (\Exception $e) {
            DB::rollback();
            Log::channel('log-transaction')->info($e->getMessage(), ['User' =>  Auth::user()->name]);
            return back()->with('error', $e->getMessage());
        }
    }

    public function delete(Role $role): bool
    {
        DB::beginTransaction();
        try {
            $role->delete();
            DB::commit();
            Log::channel('log-transaction')->info('Role Delete Success!', ['User' =>  Auth::user()->name]);
            return TRUE;
        } catch (\Exception $e) {
            DB::rollback();
            Log::channel('log-transaction')->info($e->getMessage(), ['User' =>  Auth::user()->name]);
            return FALSE;
        }
    }
}
