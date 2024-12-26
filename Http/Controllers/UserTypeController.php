<?php

namespace Modules\UserType\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\RolePermission\Entities\Role;
use Modules\UserType\Entities\UserRole;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Exception;

class UserTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        try {
            $query = Role::where('id', '!=', 1);
            if (isModuleActive('Org')) {
                $query->whereIn('id', [2, 3]);
            }
            $data = [
                'roles' => $query->pluck('name', 'id'),
                'branches' => isModuleActive('Org') ? OrgBranch::where('parent_id', 0)->get() : [],
            ];
            return view('usertype::index', $data);
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function settingSubmit(Request $request)
    {
        DB::beginTransaction();
        try {
            if (!Auth::user()->role->permissions->contains('route' => 'usertype.setting')) {
                throw new Exception('Permission Denied');
            }

            $user = User::with('userRoles')->findOrFail($request->user);
            
            if ($request->boolean('status')) {
                $user->userRoles()->attach($request->role);
                if ($user->role_id == 0) {
                    $user->role_id = $request->role;
                    $user->save();
                }
            } else {
                $user->userRoles()->detach($request->role);
                if ($user->role_id == $request->role) {
                    $user->role_id = 0;
                    $user->save();
                }
            }

            DB::commit();
            Toastr::success(__('common.operation_successful'));
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            DB::rollBack();
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function assignOrg(Request $request)
    {
        DB::beginTransaction();
        try {
            if (!Auth::user()->role->permissions->contains('route' => 'usertype.assignOrg')) {
                throw new Exception('Permission Denied');
            }

            $user = User::findOrFail($request->user);
            $user->org_chart_code = $request->org;
            $user->save();

            DB::commit();
            Toastr::success(__('common.operation_successful'));
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            DB::rollBack();
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function data(Request $request)
    {
        try {
            $roles = Role::pluck('name', 'id');
            $query = User::with('userRoles')->where('id', '!=', 1);

            if (!empty($request->roles)) {
                $query->whereHas('userRoles', function ($q) {
                    $q->whereIn('role_id', request('roles'));
                });
            }

            if (isModuleActive('LmsSaas')) {
                $query->where('lms_id', app('institute')->id);
            } else {
                $query->where('lms_id', 1);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('name', function ($query) {
                    return $query->name;
                })
                ->addColumn('actions', function ($query) {
                    return view('usertype::partials._action_buttons', compact('query'));
                })
                ->rawColumns(['actions'])
                ->make(true);
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function changePanel($role_id)
    {
        try {
            $user = Auth::user();
            $user->role_id = $role_id;
            $user->save();
            
            Toastr::success(__('common.operation_successful'));
            return redirect()->back();
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
            Toastr::error(__('common.error_message'));
            return redirect()->back();
        }
    }
}
