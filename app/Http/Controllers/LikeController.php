<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Work $work)
    {
        $liked = $work->likes()->where('user_id', auth()->id())->exists();

        if ($liked) {
            $work->likes()->where('user_id', auth()->id())->delete();
        } else {
            $work->likes()->create(['user_id' => auth()->id()]);
        }

        return response()->json([
            'success' => true,
            'liked' => !$liked,
            'likes_count' => $work->likes()->count()
        ]);
    }
    
}