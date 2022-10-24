<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Http\Requests\StoreCommentsRequest;
use App\Http\Requests\UpdateCommentsRequest;
use App\Models\Stories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Exception;
use Illuminate\Support\Facades\Http;

ini_set('max_execution_time', 10000);

class CommentsController extends Controller
{
    //define constant variables
    const BASE_URL = "https://hacker-news.firebaseio.com/v0/";
    const ITEM_URL = "item/";


    /**
     * Pulls a collection of comments IDs from the stories db table
     * @return array|Collection
     * Collection of comment IDs
     */
    public function getCommentsID(): array|Collection
    {
        $all_comments_ids = array();
        $res = DB::table('stories')->select('comments')->get();

        foreach ($res as $key => $col) {
            foreach ($col as $field) {
                foreach ((array)$field as $set) {
                    $each = str_replace(array('[', ']'), '', $set);
                    $eachList = explode(',', $each);
                    foreach ($eachList as $unit) :
                        $all_comments_ids[] = $unit;
                    endforeach;
                }
            }
        }

        return $all_comments_ids;
    }

    /**
     * Gets all the comments using they array of comments ID's returned by the getComments() method
     * @return array
     * Array of comments
     */
    public function getAllComments(): array
    {

        $comments_id = $this->getCommentsID();
        $all_comments = array();

        foreach ($comments_id as $id) {
            try {
                $comments_list = Http::get(self::BASE_URL . self::ITEM_URL . $id . ".json");
                $comments_list = json_decode($comments_list);
                $all_comments[] = (array)$comments_list;
            } catch (Exception $exception) {
                dd($exception);
            }
        }

        return $all_comments;
    }

    /**
     * Saves all the comments in a db table using story_id as foreign key
     * @return void
     * Throws an expection upon failure
     */
    public function storeComments(): void
    {
        $data = $this->getAllComments();

        foreach ($data as $comment) :
            $obj_comm = new Comments();
            try {
                if (DB::table('comments')->where('comment_id', $comment['id'])->exists() !== true){
                    $obj_comm->comment_id = $comment['id'];
                    $obj_comm->story_id = $comment['parent'];

                    if (array_key_exists('by', $comment)):
                        $obj_comm->by = $comment['by'];
                    endif;

                    $obj_comm->time = (new StoriesController)->convertUnixTime($comment['time']);
                    $obj_comm->type = $comment['type'];
                    $obj_comm->text = (array)$comment['text'];

                    $obj_comm->save();
                }
            } catch (Exception $exception) {
                dd($exception);
            }
        endforeach;
    }

    /**
     * Query all comments of a particular story using their story ID passed when clicking stories comments
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
     * Comments in a view
     */
    public function showComments(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $id = $request->id;
        try {
            $story_comments = Comments::where('story_id',$id)->get();
        } catch (Exception $exception) {
            dd($exception);
        }
        return view('comments', compact('story_comments'));

        //dd($story_comments);
    }

}
