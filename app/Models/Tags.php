<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tags extends Model
{
    use HasFactory;

    public static function getAllTags(): \Illuminate\Support\Collection
    {
        return DB::table('tags')->get();
    }

    public static function getSortedTags($data)
    {
        return DB::table('tags')
            ->selectRaw("tags.id,
            tags.title,
            count('article_tag.*') as article_count
            ")
            ->leftJoin('article_tag', 'tags.id', '=', 'article_tag.tag_id')
            ->groupBy('tags.id','tags.title')
            ->orderBy((isset($data['sort']) ? $data['sort'] : 'article_count'),(isset($data['order']) ? $data['order'] : 'desc'))
            ->get();
    }
    public static function getArticlesIdesByTags($id): \Illuminate\Support\Collection
    {
        return DB::table('article_tag')
            ->select('article_id')
            ->where('tag_id', '=',$id)
            ->get();
    }
}
