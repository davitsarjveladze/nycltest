<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Comments extends Model
{
    use HasFactory;
    public static function getComments(Array $data ,  $id): \Illuminate\Support\Collection
    {
        return $query = DB::table('article_comment')
            ->selectRaw(
                "article_comment.comment_id as id,
            comments.content,
            comments.created_at
           ")
            ->rightJoin('comments', 'article_comment.comment_id', '=', 'comments.id')
            ->where('article_comment.article_id' ,'=' ,$id)
            ->orderBy((isset($data['sort']) ? $data['sort'] : 'created_at'),(isset($data['order']) ? $data['order'] : 'desc'))
            ->get();

    }
}
