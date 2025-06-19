<?php

namespace App\Http\Controllers;
use App\Models\RoleMaster;
use Illuminate\Http\Request;

class RoleMasterController extends Controller {
    public function index(Request $request) {
        $search = $request->input('search');
        $roles = RoleMaster::when($search, function ($query, $search) {
            return $query->where('role_name', 'like', "%{$search}%");
        })->paginate(10);
        return view('admin.roles.index', compact('roles', 'search'));
    }

    public function create() {
        return view('admin.roles.create');
    }

    public function store(Request $request) {
        $request->validate([
            'role_name' => 'required|string|max:100|unique:role_master',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);
        RoleMaster::create($request->all());
        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(RoleMaster $role) {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, RoleMaster $role) {
        $request->validate([
            'role_name' => 'required|string|max:100|unique:role_master,role_name,' . $role->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);
        $role->update($request->all());
        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(RoleMaster $role) {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
