<?php

namespace App\Http\Controllers;
use App\Models\CastMaster;
use Illuminate\Http\Request;

class CastMasterController extends Controller {
    public function index(Request $request) {
        $search = $request->input('search');
        $casts = CastMaster::when($search, function ($query, $search) {
            return $query->where('cast_name', 'like', "%{$search}%");
        })->paginate(10);
        return view('admin.casts.index', compact('casts', 'search'));
    }

    public function create() {
        return view('admin.casts.create');
    }

    public function store(Request $request) {
        $request->validate([
            'cast_name' => 'required|string|max:100|unique:cast_master',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('casts', 'public');
        }
        CastMaster::create($data);
        return redirect()->route('casts.index')->with('success', 'Cast created successfully.');
    }

    public function edit(CastMaster $cast) {
        return view('admin.casts.edit', compact('cast'));
    }

    public function update(Request $request, CastMaster $cast) {
        $request->validate([
            'cast_name' => 'required|string|max:100|unique:cast_master,cast_name,' . $cast->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('casts', 'public');
        }
        $cast->update($data);
        return redirect()->route('casts.index')->with('success', 'Cast updated successfully.');
    }

    public function destroy(CastMaster $cast) {
        $cast->delete();
        return redirect()->route('casts.index')->with('success', 'Cast deleted successfully.');
    }
}

