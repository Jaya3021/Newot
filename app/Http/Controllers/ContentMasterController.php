<?php

namespace App\Http\Controllers;

use App\Models\ContentMaster;
use App\Models\GenreMaster;
use App\Models\CastMaster;
use App\Models\RoleMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Vimeo\Vimeo;
use Illuminate\Support\Facades\Log;

class ContentMasterController extends Controller
{

    public function deleteUsingDB($id)
{
    try {
        // Optional: Check if the record exists
        $content = DB::table('content_master')->where('id', $id)->first();
        
        if (!$content) {
            return redirect()->back()->with('error', 'Content not found.');
        }

        DB::beginTransaction();

        // Delete content
        DB::table('content_master')->where('id', $id)->delete();

        DB::commit();
        return redirect()->route('contents.index')->with('success', 'Content deleted using DB facade.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error deleting content using DB facade: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Failed to delete content.');
    }
}
    public function index(Request $request)
    {
        $search = $request->input('search');
        $contents = ContentMaster::with('genre', 'castMembers')
            ->when($search, function ($query, $search) {
                return $query->where('movie_name', 'like', "%{$search}%");
            })->paginate(10);
        $genres = GenreMaster::where('status', 'active')->get();
        $casts = CastMaster::where('status', 'active')->get();
        $roles = RoleMaster::where('status', 'active')->get();

        return view('admin.contents.index', compact('contents', 'search', 'genres', 'casts', 'roles'));
    }

    public function create()
    {
        $genres = GenreMaster::where('status', 'active')->get();
        $casts = CastMaster::where('status', 'active')->get();
        return view('admin.contents.create', compact('genres', 'casts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'movie_name' => 'required|string|max:255',
            'genre_id' => 'required|exists:genre_master,id',
            'cast_id' => 'required|array',
            'cast_id.*' => 'exists:cast_master,id',
            'role_id' => 'required|array',
            'role_id.*' => 'exists:role_master,id',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'trailer_url' => 'nullable|url',
            'full_video' => 'required|file|mimes:mp4,mov,avi|max:102400',
            'release_year' => 'required|integer|min:1900|max:' . now()->year,
            'description' => 'required|string',
            'language' => 'required|string|max:100',
            'duration' => 'required|integer|min:1',
            'content_rating' => 'required|in:G,PG,PG-13,R,NC-17',
            'status' => 'required|in:1,0',
        ]);

        try {
            DB::beginTransaction();

            $thumbnailPath = $request->hasFile('thumbnail') ? $request->file('thumbnail')->store('thumbnails', 'public') : null;

            $videoUrl = null;
            if ($request->hasFile('full_video')) {
                $client = new Vimeo(
                    config('services.vimeo.client_id'),
                    config('services.vimeo.client_secret'),
                    config('services.vimeo.access_token')
                );

                $filePath = $request->file('full_video')->getRealPath();
                Log::info('Attempting to upload video to Vimeo: ' . $request->input('movie_name'));

                $uri = $client->upload($filePath, [
                    'name' => $request->input('movie_name'),
                    'description' => $request->input('description'),
                    'privacy' => ['view' => 'anybody', 'embed' => 'public']
                ]);

                if (!$uri) {
                    throw new \Exception('Vimeo upload failed: No URI returned.');
                }

                $videoDataResponse = $client->request($uri . '?fields=uri,name,description,link,player_embed_url,pictures.sizes');
                if ($videoDataResponse['status'] !== 200) {
                    throw new \Exception('Failed to fetch video data from Vimeo after upload.');
                }

                $videoDetails = $videoDataResponse['body'];
                $videoUrl = $videoDetails['player_embed_url'];
            }

            $contentId = DB::table('content_master')->insertGetId([
                'movie_name' => $request->movie_name,
                'genre_id' => $request->genre_id,
                'thumbnail' => $thumbnailPath,
                'trailer_url' => $request->trailer_url,
                'release_year' => $request->release_year,
                'description' => $request->description,
                'language' => $request->language,
                'duration' => $request->duration,
                'content_rating' => $request->content_rating,
                'full_video_url' => $videoUrl,
                'status' => $request->status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($request->cast_id && $request->role_id) {
                $castData = [];
                foreach ($request->cast_id as $index => $castId) {
                    if (isset($request->role_id[$index])) {
                        $castData[] = [
                            'content_id' => $contentId,
                            'cast_id' => $castId,
                            'role_id' => $request->role_id[$index],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
                DB::table('content_cast')->insert($castData);
            }

            DB::commit();
            return redirect()->route('contents.index')->with('success', 'Content added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Content creation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to create content: ' . $e->getMessage())->withInput();
        }
    }

    /*
    public function edit(ContentMaster $contentMaster)
    {
        $genres = GenreMaster::where('status', 'active')->get();
        $casts = CastMaster::where('status', 'active')->get();
        $roles = RoleMaster::where('status', 'active')->get();
        $contentMaster->load('castMembers');
        return view('admin.contents.edit', compact('contentMaster', 'genres', 'casts','roles'));
    }
    public function update(Request $request, ContentMaster $contentMaster)
    {
        $request->validate([
            'movie_name' => 'required|string|max:255',
            'genre_id' => 'required|exists:genre_master,id',
            'cast_id' => 'nullable|array',
            'cast_id.*' => 'exists:cast_master,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'trailer_url' => 'nullable|url',
            'release_year' => 'required|integer|min:1900|max:' . date('Y'),
            'description' => 'nullable|string',
            'language' => 'nullable|string|max:100',
            'duration' => 'nullable|integer|min:1',
            'content_rating' => 'nullable|string|max:50',
            'full_video' => 'nullable|mimes:mp4,avi,mov|max:102400',
            'status' => 'required|in:active,inactive', 
        ]);

        try {
            DB::beginTransaction();
            $content = DB::table('content_master')->where('id', $contentMaster->id)->first();

            if (!$content) {
                return back()->with('error', 'Content not found.');
            }

            $data = [
                'movie_name' => $request->movie_name,
                'genre_id' => $request->genre_id,
                'trailer_url' => $request->trailer_url,
                'release_year' => $request->release_year,
                'description' => $request->description,
                'language' => $request->language,
                'duration' => $request->duration,
                'content_rating' => $request->content_rating,
                'status' => $request->status,
                'updated_at' => now(),
            ];

            // Thumbnail
            if ($request->hasFile('thumbnail')) {
                if ($content->thumbnail && Storage::disk('public')->exists($content->thumbnail)) {
                    Storage::disk('public')->delete($content->thumbnail);
                }
                $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
            }

            // Full Video

            if ($request->hasFile('full_video')) {
    // Only delete the old video if it's a local file, not a Vimeo URL
    if ($content->full_video_url && 
        !str_contains($content->full_video_url, 'vimeo.com') && 
        Storage::disk('public')->exists($content->full_video_url)) {
        Storage::disk('public')->delete($content->full_video_url);
    }

    // Store the new video locally
    $data['full_video_url'] = $request->file('full_video')->store('videos', 'public');
}

          DB::table('content_master')->where('id', $contentMaster->id)->update($data);

            // Update Cast + Role
            DB::table('content_cast')->where('content_id', $contentMaster->id)->delete();


            if ($request->cast_id && $request->role_id) {
                foreach ($request->cast_id as $index => $castId) {
                    if (isset($request->role_id[$index])) {
                        DB::table('content_cast')->insert([
                            'content_id' => $contentMaster->id,
                            'cast_id' => $castId,
                            'role_id' => $request->role_id[$index],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('contents.index')->with('success', 'Content updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Content update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update content.')->withInput();
        }
    }
*/


/*
    public function destroy(ContentMaster $contentMaster)
{
    try {
        DB::beginTransaction();
        dd($contentMaster);
        // Delete thumbnail
        if ($contentMaster->thumbnail && Storage::disk('public')->exists($contentMaster->thumbnail)) {
            Storage::disk('public')->delete($contentMaster->thumbnail);
        }
        
        // Delete Vimeo video if exists
        if ($contentMaster->full_video_url) {
            $client = new Vimeo(
                config('services.vimeo.client_id'),
                config('services.vimeo.client_secret'),
                config('services.vimeo.access_token')
            );
            
            // Extract video ID from URL
            $videoId = basename(parse_url($contentMaster->full_video_url, PHP_URL_PATH));
            $client->request('/videos/' . $videoId, [], 'DELETE');
        }
        
        $contentMaster->castMembers()->detach();
        $contentMaster->delete();
        
        DB::commit();
        return redirect()->route('contents.index')->with('success', 'Content deleted successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Content deletion failed: ' . $e->getMessage());
        return back()->with('error', 'Failed to delete content: ' . $e->getMessage());
    }
}
}
  
  public function destroy(ContentMaster $contentMaster)
{
    try {
        DB::beginTransaction();

        // Delete thumbnail from storage
        if ($contentMaster->thumbnail && Storage::disk('public')->exists($contentMaster->thumbnail)) {
            Storage::disk('public')->delete($contentMaster->thumbnail);
        }

        // Delete Vimeo video
        if ($contentMaster->full_video_url && strpos($contentMaster->full_video_url, 'vimeo.com') !== false) {
            $client = new Vimeo(
                config('services.vimeo.client_id'),
                config('services.vimeo.client_secret'),
                config('services.vimeo.access_token')
            );

            $path = parse_url($contentMaster->full_video_url, PHP_URL_PATH);
            $segments = explode('/', trim($path, '/'));
            $videoId = end($segments);

            try {
                $client->request('/videos/' . $videoId, [], 'DELETE');
            } catch (\Exception $vimeoEx) {
                Log::warning("Failed to delete Vimeo video for ID $videoId: " . $vimeoEx->getMessage());
            }
        }

        // Detach relationships and delete content
        $contentMaster->castMembers()->detach();
        $contentMaster->delete();

        DB::commit();
        return redirect()->route('contents.index')->with('success', 'Content deleted successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Content deletion failed: ' . $e->getMessage());
        return back()->with('error', 'Something went wrong while deleting the content. Please try again.');
    }
}
*/

public function destroy($id)
{
    try {
       $contentMaster = ContentMaster::findOrFail($id);
       $contentMaster->delete();
        return redirect()->route('contents.index')->with('success', 'Content deleted successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Content deletion failed: ' . $e->getMessage());
        return back()->with('error', 'Something went wrong while deleting the content. Please try again.');
    }
}

    public function edit(ContentMaster $contentMaster)
    {
        $genres = GenreMaster::where('status', 'active')->get();
        $casts = CastMaster::where('status', 'active')->get();
        $roles = RoleMaster::where('status', 'active')->get();

        $castRoles = DB::table('content_cast')
            ->where('content_id', $contentMaster->id)
            ->get()
            ->map(function ($item) {
                return [
                    'cast_id' => $item->cast_id,
                    'role_id' => $item->role_id
                ];
            });

        return view('admin.contents.edit', compact('contentMaster', 'genres', 'casts', 'roles', 'castRoles'));
    }
/*
    public function update(Request $request)
    {

        // dd($contentMaster->id);
        $request->validate([
            'movie_name' => 'required|string|max:255',
            'genre_id' => 'required|exists:genre_master,id',
            'cast_id' => 'nullable|array',
            'cast_id.*' => 'exists:cast_master,id',
            'role_id' => 'nullable|array',
            'role_id.*' => 'exists:role_master,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'trailer_url' => 'nullable|url',
            'release_year' => 'required|integer|min:1900|max:' . date('Y'),
            'description' => 'nullable|string',
            'language' => 'nullable|string|max:100',
            'duration' => 'nullable|integer|min:1',
            'content_rating' => 'nullable|string|max:50',
            'full_video' => 'nullable|mimes:mp4,avi,mov|max:102400',
            // 'status' => 'nullable|in:active,inactive',
        ]);

        try {
            DB::beginTransaction();

            $content = DB::table('content_master')->where('id', $request->id)->first();

            if (!$content) {
                return back()->with('error', 'Content not found.');
            }

            $data = [
                'movie_name' => $request->movie_name,
                'genre_id' => $request->genre_id,
                'trailer_url' => $request->trailer_url,
                'release_year' => $request->release_year,
                'description' => $request->description,
                'language' => $request->language,
                'duration' => $request->duration,
                'content_rating' => $request->content_rating,
                'status' => $request->status ?? 'active',
                //'updated_at' => now(),
            ];


            if ($request->hasFile('thumbnail')) {
                if ($content->thumbnail && Storage::disk('public')->exists($content->thumbnail)) {
                    Storage::disk('public')->delete($content->thumbnail);
                }
                $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
            }

            if ($request->hasFile('full_video')) {
                if ($content->full_video_url && !str_contains($content->full_video_url, 'vimeo.com') && Storage::disk('public')->exists($content->full_video_url)) {
                    Storage::disk('public')->delete($content->full_video_url);
                }
                $data['full_video_url'] = $request->file('full_video')->store('videos', 'public');
            }

            DB::table('content_master')->where('id', $content->id)->update($data);

            DB::table('content_cast')->where('id', $content->id->id)->delete();

            if ($request->cast_id && $request->role_id && count($request->cast_id) == count($request->role_id)) {
                foreach ($request->cast_id as $index => $castId) {
                    DB::table('content_cast')->insert([
                        'content_id' => $content->id,
                        'cast_id' => $castId,
                        'role_id' => $request->role_id[$index],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('contents.index')->with('success', 'Content updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Content update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update content.')->withInput();
        }
    }
}
    */
    public function update(Request $request, $id)
{
    $request->validate([
        // all validations...
    ]);

    try {
        DB::beginTransaction();

        $content = DB::table('content_master')->where('id', $id)->first();

        if (!$content) {
            return back()->with('error', 'Content not found.');
        }

        $data = [
            'movie_name' => $request->movie_name,
            'genre_id' => $request->genre_id,
            'trailer_url' => $request->trailer_url,
            'release_year' => $request->release_year,
            'description' => $request->description,
            'language' => $request->language,
            'duration' => $request->duration,
            'content_rating' => $request->content_rating,
            'status' => $request->status ?? 'active',
            'updated_at' => now(),
        ];

        // Handle file uploads (same as before)
        if ($request->hasFile('thumbnail')) {
            if ($content->thumbnail && Storage::disk('public')->exists($content->thumbnail)) {
                Storage::disk('public')->delete($content->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        if ($request->hasFile('full_video')) {
            if ($content->full_video_url && !str_contains($content->full_video_url, 'vimeo.com') && Storage::disk('public')->exists($content->full_video_url)) {
                Storage::disk('public')->delete($content->full_video_url);
            }
            $data['full_video_url'] = $request->file('full_video')->store('videos', 'public');
        }

        DB::table('content_master')->where('id', $id)->update($data);

        DB::table('content_cast')->where('content_id', $id)->delete();

        if ($request->cast_id && $request->role_id && count($request->cast_id) == count($request->role_id)) {
            foreach ($request->cast_id as $index => $castId) {
                DB::table('content_cast')->insert([
                    'content_id' => $id,
                    'cast_id' => $castId,
                    'role_id' => $request->role_id[$index],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        DB::commit();
        return redirect()->route('contents.index')->with('success', 'Content updated successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Content update failed: ' . $e->getMessage());
        return back()->with('error', 'Failed to update content.')->withInput();
    }
}

}
