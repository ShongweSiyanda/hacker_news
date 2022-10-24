<?php

namespace App\Http\Controllers;

use App\Models\Stories;
use App\Http\Requests\StoreStoriesRequest;
use App\Http\Requests\UpdateStoriesRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Exception;

ini_set('max_execution_time', 10000);

/**
 * @author siya-dev
 */
class StoriesController extends Controller
{
    //define constant variables
    const BASE_URL = "https://hacker-news.firebaseio.com/v0";
    const TOP_STORIES_URL = "/topstories.json";
    const NEW_STORIES_URL = "/newstories.json";
    const BEST_STORIES_URL = "/beststories.json";
    const ITEM_URL = "/item/";


    public function createTopStories()
    {
        $this->store(self::TOP_STORIES_URL);
    }

    public function createNewStories(){
        $this->store(self::NEW_STORIES_URL);
    }

    public function showTopStories(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $results = Stories::orderBy('time','desc')->limit(500)->simplePaginate(30);
        } catch (Exception $exception) {
            dd($exception);
        }
        return view('top', compact('results'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
     * DB query results of the best stories, sorted by score in descending order
     */
    public function showBestStories(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $best_stories_feed = Stories::orderBy('score', 'desc')->limit(500)->simplePaginate(30);
        } catch (Exception $ex) {
            dd($ex);
        }
        return view('best', compact('best_stories_feed'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
     * DB query results of new stories in a view, sorted by date in descending order
     */
    public function showNewStories(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $results = Stories::orderBy('time', 'desc')->limit(500)->simplePaginate(30);
        } catch (Exception $e) {
            dd($e);
        }
        return view('new', compact('results'));
    }

    /**
     * @param $url
     * @return void
     * Throws an exception upon failure to push data to DB table
     */
    public function store($url): void
    {
        $data = $this->getStories($url);

        foreach ($data as $list) :
            $obj_ts = new Stories();
            try {
                //check if record exists in DB (if true update the values that might've changed, else create new record)
                if (DB::table('stories')->where('story_id', $list['id'])->exists()) {
                    //check if this story have comments before updating the fields
                    if (array_key_exists("kids", $list)) {
                        DB::table('stories')
                            ->where('story_id', $list['id'])
                            ->update([
                                'score' => $list['score'],
                                'comments' => (array)$list['kids']
                            ]);
                    } else {
                        DB::table('stories')
                            ->where('story_id', $list['id'])
                            ->update([
                                'score' => $list['score'],
                            ]);
                    }

                } else {
                    //create new record
                    $obj_ts->story_id = $list['id'];
                    $obj_ts->type = $list['type'];
                    $obj_ts->by = $list['by'];
                    $obj_ts->time = $this->convertUnixTime($list['time']);
                    $obj_ts->score = $list['score'];
                    $obj_ts->title = $list['title'];

                    //some records don't have urls, first check before populating the value
                    if (array_key_exists('url', $list)) {
                        $obj_ts->url = (array)$list['url'];
                    }
                    //new stories may not have comments as yet
                    if (array_key_exists('kids', $list)) {
                        $obj_ts->comments = (array)$list['kids'];
                    }

                    $obj_ts->save();
                }
            } catch (Exception $exception) {
                dd($exception);
            }
        endforeach;

    }

    /**
     * @param $url
     * @return array
     * All top/new stories
     */
    public function getStories($url): array
    {
        $all_stories = array();
        $stories_id = $this->getIDs($url);

        for ($i = 0; $i < 499; $i++) {
            try {
                $stories_list = Http::get(self::BASE_URL . self::ITEM_URL . $stories_id[$i] . ".json");
                $stories_list = json_decode($stories_list);
                $all_stories[] = (array)$stories_list;
            } catch (Exception $exception) {
                dd($exception);
            }
        }

        return $all_stories;
    }

    /**
     * @param $time
     * @return false|string
     * Time in Datetime format
     */
    private function convertUnixTime($time): bool|string
    {
        return date("Y-m-d H:i:s", $time);
    }

    /**
     * @param $url
     * @return array
     * Stories ID's
     */
    private function getIDs($url): array
    {
        $storiesID = Http::get(self::BASE_URL . $url);
        $storiesID = json_decode($storiesID);

        return (array)$storiesID;
    }


}
