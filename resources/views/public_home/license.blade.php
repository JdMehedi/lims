@extends('public_home.front')
@section('body')
    <style>
        .input_disabled {
            pointer-events: none;
        }
        .radio_label {
            /*cursor: pointer;*/
        }

        .small-box {
            margin-bottom: 0;
            /*cursor: pointer;*/
        }
        .d-grid {
            display: grid;
        }
        .grid-col-12 {
            grid-template-columns: repeat(8, 1fr);
            gap: 10px;
        }
        .h-150 {
            height: 150px;
        }
        .h-125 {
            height: 125px;
        }

        @media (max-width: 1200px) {
            .grid-col-12 {
                grid-template-columns: repeat(6, 1fr);
            }
        }

        @media (max-width: 1000px) {
            .grid-col-12 {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 500px) {
            .grid-col-12 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 481px) {
            .g_name {
                font-size: 32px;
            }
        }

        @media (max-width: 480px) {
            .g_name {
                font-size: 18px;
            }

            span {
                font-size: 14px;
            }

            label {
                font-size: 14px;
            }
        }

        @media (min-width: 767px) {
            .has_border {
                border-left: 1px solid lightgrey;
            }

            .has_border_right {
                border-right: 1px solid lightgrey;
            }
        }
    </style>
    <div style="max-width: 1140px; margin: 16px auto 0 auto;">
        <div class="d-grid grid-col-12">
            @foreach ($licenseData as $service)
                <div>
                    <div class="small-box bg-{{ !empty($service->panel) ? $service->panel : 'info' }} h-150">
                        <div class="inner h-125">
                            <h3> {{ !empty($service->totalApplication) ? $service->totalApplication : '0' }}</h3>
                            <p>{{ !empty($service->name_bn) ? (strpos($service->name_bn, "Issue") ? substr($service->name_bn, 0, strlen($service->name_bn)-5) : $service->name_bn) : 'N/A' }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas {{ $service->icon }}"></i>
                        </div>
                        <a class="small-box-footer"
                           href="{{ !empty($service->form_url) && $service->form_url == '/#' ? 'javascript:void(0)' : url($service->form_url . '/list/' . \App\Libraries\Encryption::encodeId($service->id)) }}"
                            {{ !empty($service->form_url) && $service->form_url != '/#' ? 'target="_blank"' : '' }}>
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
