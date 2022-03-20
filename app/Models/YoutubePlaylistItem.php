<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YoutubePlaylistItem extends Model
{
    use HasFactory;

    protected $fillable = ['video_id', 'channel_id', 'playlist_id', 'title', 'description', 'published_at', 'created_at', 'updated_at'];
    
    public function index()
    {
        
    }
}
