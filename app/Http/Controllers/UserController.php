<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['role', 'warehouses'])->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $warehouses = Warehouse::all();
        return view('users.create', compact('roles', 'warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'personnel_code' => 'required|string|max:50|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'warehouse_ids' => 'nullable|array',
            'warehouse_ids.*' => 'exists:warehouses,id',
        ], [
            'name.required' => 'نام و نام خانوادگی الزامی است.',
            'email.required' => 'ایمیل الزامی است.',
            'email.unique' => 'این ایمیل قبلاً در سیستم ثبت شده است.',
            'personnel_code.required' => 'کد پرسنلی الزامی است.',
            'personnel_code.unique' => 'این کد پرسنلی تکراری است.',
            'password.required' => 'رمز عبور الزامی است.',
            'password.min' => 'رمز عبور باید حداقل ۶ کاراکتر باشد.',
            'role_id.required' => 'انتخاب نقش کاربر الزامی است.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'personnel_code' => $validated['personnel_code'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
        ]);

        if (!empty($request->warehouse_ids)) {
            $user->warehouses()->sync($request->warehouse_ids);
        }

        return redirect()->route('users.index')->with('success', 'کاربر جدید با موفقیت ایجاد شد.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $warehouses = Warehouse::all();
        $assignedWarehouses = $user->warehouses->pluck('id')->toArray();

        return view('users.edit', compact('user', 'roles', 'warehouses', 'assignedWarehouses'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'personnel_code' => 'required|string|max:50|unique:users,personnel_code,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'warehouse_ids' => 'nullable|array',
            'warehouse_ids.*' => 'exists:warehouses,id',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'personnel_code' => $validated['personnel_code'],
            'role_id' => $validated['role_id'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);
        $user->warehouses()->sync($request->warehouse_ids ?? []);

        return redirect()->route('users.index')->with('success', 'اطلاعات کاربر با موفقیت بروزرسانی شد.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'شما نمی‌توانید حساب کاربری خودتان را حذف کنید!');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'کاربر مورد نظر با موفقیت حذف شد.');
    }
}
