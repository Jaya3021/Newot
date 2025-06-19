<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Video Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 border border-green-400 p-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-6">
                        <a href="{{ route('videos.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Upload New Video') }}
                        </a>
                    </div>
                    
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __("Your Uploaded Videos") }}</h3>

                    @if(isset($videos) && $videos->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($videos as $video)
                                <div class="bg-gray-50 p-4 rounded-lg shadow">
                                    <h4 class="text-md font-semibold text-gray-800 mb-2">{{ $video->title }}</h4>
                                    
                                    @if($video->embed_html) 
                                        <div class="aspect-w-16 aspect-h-9 mb-2"> {{-- Responsive aspect ratio for video player --}}
                                            {!! $video->embed_html !!} {{-- Make sure embed_html is trusted or sanitized --}}
                                        </div>
                                    @elseif($video->vimeo_link)
                                        <p class="mb-2"><a href="{{ $video->vimeo_link }}" target="_blank" class="text-indigo-600 hover:text-indigo-800">Watch on Vimeo</a></p>
                                    @else
                                        <p class="mb-2 text-sm text-gray-500">Video player not available.</p>
                                    @endif

                                    @if($video->description)
                                        <p class="text-sm text-gray-600 mb-1">{{ Str::limit($video->description, 100) }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400">Uploaded: {{ $video->created_at->format('M d, Y') }}</p>
                                </div>
                            @endforeach
                        </div>
                        {{-- Add pagination if needed: $videos->links() --}}
                    @else
                        <p>No videos uploaded yet. <a href="{{ route('videos.create') }}" class="text-indigo-600 hover:text-indigo-800">Upload your first video!</a></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
