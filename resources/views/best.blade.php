@extends('layouts.default')
@section('title')
    Best Stories
@endsection
@section('content')
    <div class="container pb-5">
        <div class="row">
            <div class="col-sm-12">
                <table class="itemlist" cellpadding="0" cellspacing="0" border="0">
                    <tbody>
                    <tr class="spacer-top"></tr>
                    @foreach($best_stories_feed as $key=>$chuck)
                        <tr id="{{$chuck['story_id']}}" class="athing">
                            <td class="title" valign="top" align="right">
                                <span class="rank">{{$key+1}}.&nbsp;</span>
                            </td>
                            <td class="title">
                                @if(array_key_exists('url',(array)$chuck))
                                    <span class="titleline"><a href="{{$chuck['url'][0]}}">{{$chuck['title']}}</a></span>
                                @else
                                    <span class="titleline"><a href="">{{$chuck['title']}}</a></span>
                                @endif

                            </td>
                        </tr>
                        <tr>
                            <td colspan="1"></td>
                            <td class="subtext">
                            <span class="subline">
                                @if($chuck['score'] === 1)
                                    <span class="score" id="score_{{$chuck['story_id']}}">{{$chuck['score']}} point</span>
                                @else
                                    <span class="score" id="score_{{$chuck['story_id']}}">{{$chuck['score']}} points</span>
                                @endif

                                by <a class="hnuser">{{$chuck['by']}}</a>
                                <span class="age" title="{{$chuck['time']}}"><a>{{$chuck['time']}}</a></span>
                                @if($chuck['comments'])
                                        @if(count($chuck['comments']) === 1)
                                            &nbsp;|&nbsp;<span class="comments_{{$chuck['story_id']}}" title="comments"><a
                                                    href="">{{count($chuck['comments'])}} comment</a></span>
                                        @else
                                            &nbsp;|&nbsp;<span class="comments_{{$chuck['story_id']}}" title="comments"><a
                                                    href="">{{count($chuck['comments'])}} comments</a></span>
                                        @endif
                                    @endif
                            </span>
                            </td>
                        </tr>
                        <tr class="spacer" style="height:10px"></tr>
                    @endforeach
                    <tr class="spacer-bottom"></tr>
                    </tbody>
                </table>
                {{$best_stories_feed->links()}}
            </div>
        </div>
    </div>
@endsection

