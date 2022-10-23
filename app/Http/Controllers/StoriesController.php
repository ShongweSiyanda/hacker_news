<?php

namespace App\Http\Controllers;

use App\Models\Stories;
use App\Http\Requests\StoreStoriesRequest;
use App\Http\Requests\UpdateStoriesRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/**
 * @author siya-dev
 */
ini_set('max_execution_time', 1800);
class StoriesController extends Controller
{
    //define constant variables
    const BASE_URL = "https://hacker-news.firebaseio.com/v0";
    const TOP_STORIES_URL = "/topstories.json";
    const NEW_STORIES_URL = "/newstories.json";
    const BEST_STORIES_URL = "/beststories.json";
    const ITEM_URL = "/item/";

    public function displayStories($category)
    {
        return Stories::orderBy('time', 'desc')->where('category',$category)->get();
    }

    public function displayAllStories(){
        $all_stories_feed =  Stories::orderBy('time', 'desc')->get();
        return view('index',compact('all_stories_feed'));
    }

    public function createNewStories(){
        $this->store(self::NEW_STORIES_URL,"new");
    }

    public function createBestStories(){
        $this->store(self::BEST_STORIES_URL,"best");
    }

    public function createTopStories(){
        $this->store(self::TOP_STORIES_URL,"top");
    }

    public function showTopStories(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $results = $this->displayStories("top");
        return view('top',compact('results'));
    }

    public function showNewStories(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $results = $this->displayStories("new");
        return view('new',compact('results'));
    }

    public function showBestStories(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $results = $this->displayStories("best");
        return view('best',compact('results'));
    }


    public function store($url,$category): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $data = $this->getStories($url);

        foreach ($data as $list) {
            $obj_ts = new Stories();
            //check if record exists in DB (if true update the values that might've changed, else create new record)
            if(DB::table('stories')->where('story_id',$list['id'])->exists()){

                DB::table('stories')
                    ->where('story_id',$list['id'])
                    ->update([
                        'score'=>$list['score'], #score def changes over time
                        'category'=>$category
                    ]);
            }
            else{
                //create new record
                $obj_ts->story_id = $list['id'];
                $obj_ts->type = $list['type'];
                $obj_ts->by = $list['by'];
                $obj_ts->time = $this->convertUnixTime($list['time']);
                $obj_ts->score = $list['score'];
                $obj_ts->title = $list['title'];
                $obj_ts->category = $category;
                $obj_ts->save();
            }
        }
        return view('code',compact('category'));
    }

    public function getStories($url): array
    {
        $all_stories = array();
        $stories_id = $this->getIDs($url);

        for ($i = 0; $i < 100; $i++) {
            $stories_list = Http::get(self::BASE_URL . self::ITEM_URL . $stories_id[$i] . ".json");
            $stories_list = json_decode($stories_list);
            $stories_list = (array)$stories_list;
            array_push($all_stories, $stories_list);
        }

        return $all_stories;
    }

    /**
     * @param $time
     * @return false|string
     * Converts Unix Time to Datetime
     */
    private function convertUnixTime($time): bool|string
    {
        return date("Y-m-d H:i:s", $time);
    }

    /**
     * @param $url
     * @return array
     * Method can be used to get best, new or top stories) ID's
     */
    private function getIDs($url): array
    {
        $storiesID = Http::get(self::BASE_URL . $url);
        $storiesID = json_decode($storiesID);

        return (array)$storiesID;
    }


}
