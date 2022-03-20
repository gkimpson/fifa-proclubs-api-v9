<?php

namespace App\Services;

use App\Models\Result;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Alaouy\Youtube\Facades\Youtube;
use App\Models\YoutubePlaylistItem;
use App\Services\ProClubsApiService;
use Carbon\Carbon;

class YoutubePlaylistItemsService 
{
    // public $youtubePlaylistId = 'PLMOqwK_eRmiqekWBHzK9sWCli12wZYPt4';

    public function index()
    {

    }

    static public function getPlayerMedia($platform, $clubId)
    {
        $players = ProClubsApiService::memberstats($platform, $clubId);
        dd($players);
        // club players
        // memberstats($platform, $clubId);
    }

    static public function collateYoutubeData($pageToken = null)
    {
        $playlistId = 'PLMOqwK_eRmiqekWBHzK9sWCli12wZYPt4';
        $youtubeApiResponse = Youtube::getPlaylistItemsByPlaylistId($playlistId, $pageToken);
        $results = collect($youtubeApiResponse['results']);
        $info = $youtubeApiResponse['info'];
        // dump($info, $results[0]->snippet->position);

        collect($results)->each(function (object $result) {
            if (empty($result)) return null;
            $playlistItem = [
                'video_id' => $result->contentDetails->videoId,
                'playlist_id' => $result->snippet->playlistId,
                'title' => $result->snippet->title,
                'description' => $result->snippet->description,
                'published_at' => Carbon::parse($result->snippet->publishedAt),
            ];

            if (!empty($playlistItem)) {
                YoutubePlaylistItem::updateOrCreate(
                    [
                        'video_id' => $result->contentDetails->videoId
                    ],
                    [
                        'playlist_id' => $result->snippet->playlistId,
                        'title' => $result->snippet->title,
                        'description' => $result->snippet->description,
                        'published_at' => Carbon::parse($result->snippet->publishedAt),
                    ]
                );
            }
        });

        if (!empty($info['nextPageToken'])) {
            self::collateYoutubeData($info['nextPageToken']);
        }

        $data = [
            'playlistItems' => $results,
            'pager' => [
                'prev' => $info['prevPageToken'],
                'next' => $info['nextPageToken']
            ],
            'total' => $info['totalResults']
        ];  

        dd($data['total']);
        return view('debug', $data);
    }

}