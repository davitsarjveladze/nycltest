<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class Articles extends Model
{
    use HasFactory;


    public static function getArticles(Array $data, $articleIdes = null) {
            $query = DB::table('articles')
                ->selectRaw("   articles.id,
                                        articles.title,
                                        articles.created_at,
                                        count('article_comment.*') as comment_count,
                                        GROUP_CONCAT(DISTINCT article_tag.tag_id
                                        ORDER BY id ASC
                                        SEPARATOR ',') as tags_id
            ")
                ->leftJoin('article_comment', 'articles.id', '=', 'article_comment.article_id')
                ->leftJoin('article_tag', 'articles.id', '=', 'article_tag.article_id')
                ->groupBy('articles.id','articles.title','articles.created_at');

                if(isset($articleIdes))
                    $query->whereIn('articles.id',$articleIdes);
                $query->orderBy((isset($data['sort']) ? $data['sort'] : 'created_at'),(isset($data['order']) ? $data['order'] : 'desc'))
                ->limit((isset($data['limit']) ? $data['limit'] : 10));
            if (isset($data['paginate']))
                // აქ ანუ ისეც page ით ნახულობს გეტერში როგორც ვიცი მაგრამ რადგან ეწერა რო ვაგზავნითო ბარემ ჩემით ჩავწერე
                return $query->paginate($data['paginate'], ['*'],'page', ($data['page'] ? $data['page'] : 1));

            return $query->get();
    }
}
