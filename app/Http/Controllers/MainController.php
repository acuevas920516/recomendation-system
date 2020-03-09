<?php

namespace App\Http\Controllers;

use App\Movies;
use App\MovieTags;
use App\Ratings;
use App\Users;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    private $aux = 0;
    private $Wuserproduct = 0;
    public function index(Request $request) {
        $results = null;
        return view('welcome', compact('results'));
    }

    public function find(Request $request) {
        $iduser = $request->get('iduser');

        $items = DB::select("SELECT m.iditem, m.title FROM `movietitles` m WHERE m.iditem NOT IN (SELECT r.iditem FROM ratings r WHERE r.iduser = $iduser)");
        $movie_profile = array();
        $products = DB::select("SELECT COUNT(id) FROM movietitles");
        foreach ($items as $item) {
            $profile = array();
            $tf = DB::select("SELECT mt.tag, count(mt.tag) as amount FROM `movietags` mt WHERE iditem = $item->iditem  GROUP BY tag");
            foreach ($tf as $tf_item) {
                $productos_etiquetas = DB::select('SELECT COUNT(DISTINCT(iditem)) productos_etiquetas FROM `movietags` WHERE tag = :tag',['tag' => $tf_item->tag]);
                $subvalue = $products/(int) $productos_etiquetas[0]->productos_etiquetas;
                $idf = log($subvalue);
                $profile[$tf_item->tag] = $tf_item->amount*$idf;
                $this->aux += pow($profile[$tf_item->tag],2);
            }
            array_walk_recursive($profile,function (&$elem) {
               $elem = $elem/sqrt($this->aux);
            });
            $movie_profile[$item->iditem] = $profile;
            $this->aux = 0;
        }

        var_dump($movie_profile);
        die();


//        $consumed_movies_profile = array();
//        $items = DB::select("SELECT m.iditem, m.title, r.rating FROM `movietitles` m JOIN ratings r ON m.iditem = r.iditem WHERE r.iduser = $iduser");
//        $products = Movies::all()->count();
//        $user_media = DB::select("SELECT SUM(rating)/COUNT(r.iditem) AS media FROM ratings r WHERE r.iduser = $iduser");
//        foreach ($items as $item) {
//            $profile = array();
//            $this->Wuserproduct = (float) ($item->rating - (float)$user_media[0]->media);
//            $tf = DB::select("SELECT mt.tag, count(mt.tag) as amount FROM `movietags` mt WHERE iditem = $item->iditem  GROUP BY tag");
//            foreach ($tf as $tf_item) {
//                $productos_etiquetas = DB::select('SELECT COUNT(DISTINCT(iditem)) productos_etiquetas FROM `movietags` WHERE tag = :tag',['tag' => $tf_item->tag]);
//                $subvalue = $products/(int) $productos_etiquetas[0]->productos_etiquetas;
//                $idf = log($subvalue);
//                $profile[$tf_item->tag] = $tf_item->amount*$idf;
//                $this->aux += pow($profile[$tf_item->tag],2);
//            }
//            array_walk_recursive($profile,function (&$elem) {
//               $elem = ($elem/sqrt($this->aux))*$this->Wuserproduct;
//            });
//            $consumed_movies_profile[$item->iditem] = $profile;
//            $this->aux = 0;
//        }
//
//        $UserProfileByTag = array();
//
//        $tags = DB::select('SELECT DISTINCT(tag) FROM `movietags`');
//        foreach ($tags as $elem) {
//            $data = array_column($consumed_movies_profile,$elem->tag);
//            foreach ($data as $datum) {
//                isset($UserProfileByTag[$elem->tag]) ? $UserProfileByTag[$elem->tag] += $datum : $UserProfileByTag[$elem->tag] = $datum;
//            }
//        }
//
//        $movies_valorations = array();
//        foreach ($movie_profile as $key=>$arrayTags) {
//            $movies_valorations[$key] = 0;
//            $numerator = 0;
//            $denominator1 = 0;
//            $denominator2 = 0;
//            foreach ($arrayTags as $tag=>$value) {
//                if (isset($UserProfileByTag[$tag])) {
//                    $numerator += $value * $UserProfileByTag[$tag];
//                    $denominator1 += pow($UserProfileByTag[$tag], 2);
//                    $denominator2 += pow($value, 2);
//                }
//            }
//            $movies_valorations[$key] = $numerator/ (sqrt($denominator1)*sqrt($denominator2));
//        }
//
//        arsort($movies_valorations);
//        $items = array_slice($movies_valorations,0,10,true);
        var_dump($items);
        die();

        $results = "beautifull system";
        return view('welcome',compact('results', 'items', 'amount'));
    }
}
