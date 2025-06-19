<?php


namespace App\Http\Controllers;
use App\Models\GenreMaster;
use Illuminate\Http\Request;

class GenreMasterController extends Controller {
    public function index(Request $request) {
        $search = $request->input('search');
        $genres = GenreMaster::when($search, function ($query, $search) {
            return $query->where('genre_name', 'like', "%{$search}%");
        })->paginate(10);
        return view('admin.genres.index', compact('genres', 'search'));
    }

    public function create() {
        return view('admin.genres.create');
    }

    public function store(Request $request) {
        $request->validate([
            'genre_name' => 'required|string|max:100|unique:genre_master',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('genres', 'public');
        }
        GenreMaster::create($data);
        return redirect()->route('genres.index')->with('success', 'Genre created successfully.');
    }

    public function edit(GenreMaster $genre) {
        return view('admin.genres.edit', compact('genre'));
    }

    public function update(Request $request, GenreMaster $genre) {
        $request->validate([
            'genre_name' => 'required|string|max:100|unique:genre_master,genre_name,' . $genre->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('genres', 'public');
        }
        $genre->update($data);
        return redirect()->route('genres.index')->with('success', 'Genre updated successfully.');
    }

    public function destroy(GenreMaster $genre) {
        $genre->delete();
        return redirect()->route('genres.index')->with('success', 'Genre deleted successfully.');
    }
}
