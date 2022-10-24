@extends('layouts.default')
@section('title')
    Comments
@endsection
@section('content')
    <div class="container pb-5">
        <div class="row">
            <div class="col-sm-12">
                <table class="itemlist" cellpadding="0" cellspacing="0" border="0">
                    <tbody>
                    <tr class="spacer-top"></tr>
                    @foreach($story_comments as $key=>$chuck)
                        <tr id="{{$chuck['comment_id']}}" class="athing">
                            <td class="title" valign="top" align="right">
                                <span class="rank">{{$key+1}}.&nbsp;</span>
                            </td>
                            <td class="title">
                                @if($chuck['text'])
                                    <span class="titleline"><a>{{$chuck['text'][0]}}</a></span>
                                @endif

                            </td>
                        </tr>
                        <tr>
                            <td colspan="1"></td>
                            <td class="subtext">
                            <span class="subline">
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

