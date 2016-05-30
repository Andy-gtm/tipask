@extends('theme::layout.public')

@section('seo')
    <title>{{ Setting()->get('website_name') }} - 做最好的中文问答系统</title>
    <meta name="description" content="tipask问答系统交流平台" />
    <meta name="keywords" content="问答系统,PHP问答系统,Tipask问答系统 " />
@endsection

@section('jumbotron')
    @if(Auth()->guest())
    <div class="jumbotron text-center">
        <h4>现在加入Tipask问答网，一起记录站长的世界 <a class="btn btn-primary ml-10" href="{{ route('auth.user.register') }}" role="button">立即注册</a> <a class="btn btn-default ml-5" href="{{ route('auth.user.login') }}" role="button">用户登陆</a></h4>
    </div>
    @endif
@endsection

@section('content')
    <div class="row mt-10">
        <div class="col-xs-12 col-md-9 main">
            <div class="widget-box mb-10">
                <h4 class="widget-box-title">最新推荐</h4>
                <div class="job-list-item row">
                    <div class="col-md-6">
                        <div id="carousel-recommendation" class="carousel slide" data-ride="carousel">
                            <!-- Indicators -->
                            <ol class="carousel-indicators">
                                <li data-target="#carousel-recommendation" data-slide-to="0" class="active"></li>
                                <li data-target="#carousel-recommendation" data-slide-to="1"></li>
                                <li data-target="#carousel-recommendation" data-slide-to="2"></li>
                            </ol>
                            <!-- Wrapper for slides -->
                            <div class="carousel-inner" role="leftmodbox">
                                @foreach($recommendItems as $key=> $recommendItem)
                                @if($key<3)
                                <div @if($key===0) class="item active" @else class="item" @endif>
                                    <a href="{{ $recommendItem->url }}" target="_blank"><img src="{{ route('website.image.show',['image_name'=>$recommendItem->logo]) }}" alt="{{ $recommendItem->subject }}"></a>
                                    <div class="carousel-caption">
                                        <h4>{{ $recommendItem->subject }}</h4>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <ul class="widget-links list-unstyled">
                            @foreach($recommendItems as $key=> $recommendItem)
                            @if($key>2)
                            <li class="widget-links-item">
                                <a href="{{ $recommendItem->url }}" target="_blank" >{{ $recommendItem->subject }}</a>
                            </li>
                            @endif
                            @endforeach

                        </ul>
                    </div>
                </div>
            </div>

            <div class="widget-box clearfix">
                <h4 class="widget-box-title">推荐行家 <a href="{{ route('website.user') }}" title="更多">»</a> </h4>
                @foreach($hotExperts as $expert)
                    <section class="col-sm-6 col-md-3">
                        <div class="thumbnail">
                            <a href="{{ route('auth.space.index',['user_id'=>$expert->id]) }}"><img class="avatar-128" src="{{ route('website.image.avatar',['avatar_name'=>$expert->id.'_big'])}}" alt="{{ $expert->name }}"></a>

                            <div class="caption">
                                <h4 class="text-center"><a href="{{ route('auth.space.index',['user_id'=>$expert->id]) }}">{{ $expert->name }}</a></h4>
                                <p class="text-muted text-center">{{ $expert->title }}&nbsp;</p>
                                <p class="text-center"><a class="btn btn-primary btn-sm" href="{{ route('ask.question.create') }}?to_user_id={{ $expert->id }}">向TA提问</a></p>
                            </div>
                        </div>
                    </section>
                @endforeach
            </div>


            <div class="widget-box">
                <div class="job-list-item row">
                    <div class="col-md-6">
                        <h4 class="widget-box-title">热门问答</h4>
                        <ul class="widget-links list-unstyled">
                            @foreach($hotQuestions as $hotQuestion)
                            <li class="widget-links-item">
                                <a title="{{ $hotQuestion->title }}"  href="{{ route('ask.question.detail',['id'=>$hotQuestion->id]) }}">{{ $hotQuestion->title }}</a>
                                <small class="text-muted">{{ $hotQuestion->answers }} 回答</small>
                            </li>
                            @endforeach

                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h4 class="widget-box-title">悬赏问答</h4>

                        <ul class="widget-links list-unstyled">
                            @foreach($rewardQuestions as $rewardQuestion)
                                <li class="widget-links-item">
                                    <span class="text-gold"><i class="fa fa-database"></i> {{ $rewardQuestion->price }}</span>
                                     <a title="{{ $rewardQuestion->title }}" href="{{ route('ask.question.detail',['id'=>$rewardQuestion->id]) }}">{{ $rewardQuestion->title }}</a>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="widget-box">
                <div class="job-list-item row">
                    <div class="col-md-6">
                        <h4 class="widget-box-title">热门文章</h4>
                        <ul class="widget-links list-unstyled">
                            @foreach($hotArticles as $hotArticle)
                                <li class="widget-links-item">
                                    <a title="{{ $hotArticle->title }}"  href="{{ route('blog.article.detail',['id'=>$hotArticle->id]) }}">{{ $hotArticle->title }}</a>
                                    <small class="text-muted">{{ $hotArticle->views }} 浏览</small>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h4 class="widget-box-title">最新文章</h4>
                        <ul class="widget-links list-unstyled">
                            @foreach($newestArticles as $newestArticle)
                                <li class="widget-links-item">
                                    <a title="{{ $newestArticle->title }}"  href="{{ route('blog.article.detail',['id'=>$newestArticle->id]) }}">{{ $newestArticle->title }}</a>
                                    <small class="text-muted">{{ $newestArticle->views }} 浏览</small>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-xs-12 col-md-3 side">
            <div class="side-alert alert alert-link">
                <a href="{{ route('ask.question.create') }}" class="btn btn-warning btn-block">我要提问</a>
                <a href="{{ route('blog.article.create') }}" class="btn btn-primary btn-block">分享经验</a>
            </div>
            <div class="widget-box">
                <h4 class="widget-box-title">最新公告</h4>
                <ul class="widget-links list-unstyled">
                    @foreach($newestNotices as $newestNotice)
                    <li class="widget-links-item">
                        <a title="{{ $newestNotice->subject }}" href="{{ $newestNotice->url }}" target="_blank">{{ $newestNotice->subject }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="widget-box">
                <h2 class="h4 widget-box-title">热议话题 <a href="{{ route('website.topic') }}" title="更多">»</a></h2>
                <ul class="taglist-inline multi">
                    @foreach($hotTags as $hotTag)
                    <li class="tagPopup"><a class="tag" data-toggle="popover"  href="{{ route('ask.tag.index',['name'=>$hotTag->name]) }}">{{ $hotTag->name }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="widget-box mt30">
                <h2 class="widget-box-title">
                    财富榜
                    <a href="{{ route('auth.top.coins') }}" title="更多">»</a>
                </h2>
                <ol class="widget-top10">
                    @foreach($topCoinUsers as $index => $topCoinUser)
                    <li class="text-muted">
                        <img class="avatar-32" src="{{ route('website.image.avatar',['avatar_name'=>$topCoinUser->user_id.'_middle'])}}">
                        <a href="{{ route('auth.space.index',['user_id'=>$topCoinUser->user_id]) }}" class="ellipsis">{{ $topCoinUser->user->name }}</a>
                        <span class="text-muted pull-right">{{ $topCoinUser->coins }} 金币</span>
                    </li>
                    @endforeach

                </ol>
            </div>
        </div>
    </div>
@endsection