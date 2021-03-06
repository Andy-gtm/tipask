<?php

namespace App\Http\Controllers\Account;

use App\Models\Question;
use App\Models\XsSearch;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class SearchController extends Controller
{


    public function show(Request $request){
        $word = trim($request->input('word'));
        $filter = trim($request->input('filter'));
        return view('theme::search.show')->with(compact('word','filter'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$filter='all')
    {

        $validator = Validator::make($request->all(), [
            'word' => 'required|max:128',
        ]);

        if ($validator->fails())
        {
            return $this->error(route('auth.search.show'),'搜索关键词不能为空');
        }

        $word = trim($request->input('word'));

        if( Setting()->get('xunsearch_open',0) == 1 ){
            $pageSize = 15;
            $page = $request->query('page',1);
            $startIndex = ($page - 1) * $pageSize;
            $xsSearch = XsSearch::getSearch();
            if($filter !== 'all'){
                $model =  App::make('App\Models\\'.ucfirst(str_singular($filter)));
                if($filter !== 'tags' ){
                    $docs = $xsSearch->model($model)->addQuery($word)->setLimit($pageSize,$startIndex)->search();
                }else{
                    $docs = $xsSearch->model($model)->addQuery($word)->addRange('status',0,null)->setLimit($pageSize,$startIndex)->search();
                }
            }else{
                $docs = $xsSearch->setFuzzy()->addQuery($word)->setLimit($pageSize,$startIndex)->search();
            }
            $dataList = [];

            foreach($docs as $doc){
                $data = [];
                $data['class_uid'] = $doc->class_uid;
                $data['id'] = $doc->id;
                $data['status'] = $doc->status;
                $data['subject'] = XsSearch::getSearch()->highlight($doc->subject);
                $data['content'] = XsSearch::getSearch()->highlight($doc->content);
                $dataList[] = $data;
            }
            $total = $xsSearch->count();

            $list = new Paginator($dataList, $total, $pageSize, $page,[
                'path'  => $request->url(),
                'query' => $request->query()
            ]);

        }else{
            if($filter === 'all'){
                $filter = 'questions';
            }
            $model =  App::make('App\Models\\'.ucfirst(str_singular($filter)));
            $list = $model::search($word);
            if($filter === 'questions'){
                $list->map(function($item) use ($word) {
                    foreach (explode(" ", $word) as $k) {
                        $item->title = $this->_highlight($k, $item->title);
                        $item->description = $this->_highlight($k, $item->description);
                    }
                });
            }
        }


        return view('theme::search.index')->with('word',$word)->with('filter',$filter)->with('list',$list);
    }


    private function _highlight($word,$subject){
        return str_ireplace("$word","<em>$word</em>",$subject);
    }


}
