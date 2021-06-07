<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Tags;
use Faker\Core\Number;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Models\Articles;

class ArticlesController extends Controller
{
    public function getArticles( $articleIdes , Request $request): \Illuminate\Http\JsonResponse
    {

        $sortingData = $request->validate([
            'sort' => 'String',
            'order' => 'String',
            'limit' => 'Integer',
            'paginate' => 'Integer',
            'page' => 'Integer'
        ]);
        //aq artiklebs vigeb შემოსული დატის მიხედვით
        $articles= Articles::getArticles($sortingData,$articleIdes);
        // აქ ყველა ტეგს
        $tags = Tags::getAllTags();

        $sendData = [];
        $arrayTags = [];
        // აქ ტეგებს ასოციაციურ მასივად ვაკეთებ რო მერე ყველა ჯერზე ფორით არ ვარბენინო
        // და მნიშვნელობა პირდაპირ ამოვიღო ისეც ბევრი ფორია
        foreach ($tags as $tag) {
            $arrayTags[$tag->id] = $tag;
        }
        // აქ არტიკლებს მის ტეგებს ვუსვამ
        foreach ($articles as $article ) {
            $TmpData = $article;
            // აქ კონკატით შეერთებულ აიდებს ვამასივებ
            $TmpData->tags_id = explode(',',$TmpData->tags_id);
            $TmpData->tags = [];
            // აქ ამ ტეგების აიდებით ვსვამ შესაბამის ტეგს
            foreach ($TmpData->tags_id as $tagId)
                $TmpData->tags[] = $arrayTags[$tagId];
            //აქ რაც ზედმეტია ვშლი
            unset($TmpData->tags_id);
            $sendData[] = $TmpData;
        }
        return response()->json( $sendData,200);
    }
    public function getCommentsById( $id, Request $request) {
        if(!isset($id) || !is_numeric($id)) {
            response()->json(['errorMessage' => 'id is not correct'],400);
        }
        $sortingData = $request->validate([
            'sort' => 'String',
            'order' => 'String',
        ]);
        $data = Comments::getComments($sortingData,$id);
        response()->json($data,200);
    }

    public function getArticleByTag($id, Request $request): \Illuminate\Http\JsonResponse
    {
        if(!is_numeric($id)) {
            response()->json(['errorMessage' => 'id is not correct'], 400);
        }
        $ides = Tags::getArticlesIdesByTags($id);
        $idesArray = [];
        foreach ($ides as $id) {
            $idesArray[] = $id->article_id;
        }
        print_r((get_object_vars($ides)));
        if(count($ides)!==0) {
            return $this->getArticles($idesArray, $request);
        }
        response()->json([], 200);

    }
}
