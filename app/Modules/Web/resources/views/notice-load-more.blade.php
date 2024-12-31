@foreach($notice as $latest_news)
    <div class="news_item">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <img alt='...' src="{{ asset($latest_news->photo) }}" class="img-responsive"
                         alt="news title"/>
                </div>
                <?php
                $noticeUrl = str_replace(" ", "-",App::isLocale('bn') ? $latest_news->heading : $latest_news->heading_en)
                ?>
                <div class="col-md-8 col-sm-12 col-xs-12">
                    <div class="news_right_side">
                        <h4 class="news_title">{{ App::isLocale('bn') ? $latest_news->heading : $latest_news->heading_en }}</h4>
                        <p class="publish_date {{ App::isLocale('bn') ? 'input_ban' : '' }}">{{ date('h:i | d/m/Y', strtotime($latest_news->updated_at)) }}</p>
                        <p class="news_content">{{ str_limit(strip_tags(App::isLocale('bn') ? $latest_news->details : $latest_news->details_en), $limit = 450, $end = '...') }}</p>
                        <a href="{{ url('viewNotice/'.$latest_news->id."/".$noticeUrl) }}">
                            <button class="help_div">{!! trans('messages.see_more_btn') !!}</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach