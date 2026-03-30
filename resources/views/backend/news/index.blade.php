@extends('backend.layouts.master')

@section('admin-content')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel pw">
            <div class="x_title">
                <div class="search-form-style-1">
                    @include('backend.news.search-form')
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="table-responsive pw-table">
                    {{$news->links('pagination::bootstrap-4')}}

                    @if(!empty($news->count()))
                    <table class="table table-striped jambo_table bulk_action table-bordered-">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Content</th>
                                <th>Status</th>
                                <th>Date Published</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($news as $article)
                                <tr id="item-{{$article->id}}">
                                    <td>
                                        @php
                                            $thumb = !empty($article->photo) ? asset('storage/posts/'.basename($article->photo)) : url('assets/admin/build/images/pw-theme/placeholder/small.png');
                                        @endphp
                                        <img src="{{$thumb}}" alt="" width="70" style="width:75px;max-height:75px;object-fit:cover;">
                                    </td>
                                    <td width="35%">{{strip_tags($article->title)}}</td>
                                    <td width="35%">{{Str::limit(strip_tags($article->content), 70)}}</td>
                                    <td>{{ ($article->status == 'deleted') ? "Archived" :  ucwords($article->status) }}</td>
                                    <td>{{($article->date_published) ? admin_date($article->date_published) : ''}}</td>
                                    <td class="text-center table-active-btn">
                                        <a href="{{admin_url('news/'.$article->id.'/edit')}}" class="btn btn-small btn-primary">Edit</a>

                                        @if($article->status == 'published')
                                            | <a href="{{ $article->url }}" target="_blank" class="btn btn-small btn-info">View</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                        <div class="no-data">
                            No data found.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
