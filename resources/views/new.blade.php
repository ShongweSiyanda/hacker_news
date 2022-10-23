@extends('layouts.default')
@section('title')
    New Stories
@endsection
@section('content')
    <div class="container pb-5">
        <div class="row">
            <div class="col-sm-12">
                <table class="itemlist" cellpadding="0" cellspacing="0" border="0">
                    <tbody>
                    <tr class="spacer-top"></tr>
                    @foreach($results as $key=>$chuck)
                        <tr id="{{$chuck['story_id']}}" class="athing">
                            <td class="title" valign="top" align="right">
                                <span class="rank">{{$key+1}}.&nbsp;</span>
                            </td>
                            <td class="title">
                                <span class="titleline">{{$chuck['title']}}</span>
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
                            </span>
                            </td>
                        </tr>
                        <tr class="spacer"></tr>
                    @endforeach
                    <tr class="spacer-bottom"></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

