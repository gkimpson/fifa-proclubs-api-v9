@isset($playlistItems['info']['totalResults'])
<div>Total items in playlist : {{ $playlistItems['info']['totalResults'] }}</div>
@endisset

@isset($playlistItems['info']['prevPageToken'])
    <span><a href="/videos/playlist/prev/{{ $playlistItems['info']['prevPageToken'] }}">Prev</a></span>
@endisset
@isset($playlistItems['info']['nextPageToken'])
    <span><a href="/videos/playlist/next/{{ $playlistItems['info']['nextPageToken'] }}">Next</a></span>
@endisset
{{ dd($playlistItems) }}