@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold"> @lang('Site Title') </label>
                                    <input class="form-control form-control-lg" type="text" name="sitename"
                                        value="{{ $general->sitename }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Currency')</label>
                                    <input class="form-control  form-control-lg" type="text" name="cur_text"
                                        value="{{ $general->cur_text }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Currency Symbol')</label>
                                    <input class="form-control  form-control-lg" type="text" name="cur_sym"
                                        value="{{ $general->cur_sym }}">
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="form-control-label font-weight-bold">@lang('Site Base Color')</label>
                                <div class="input-group">
                                    <span class="input-group-addon ">
                                        <input type='text' class="form-control  form-control-lg colorPicker"
                                            value="{{ $general->base_color }}" />
                                    </span>
                                    <input type="text" class="form-control form-control-lg colorCode" name="base_color"
                                        value="{{ $general->base_color }}" />
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Daily Day')</label>
                                    <input class="form-control  form-control-lg" type="text" name="bal_trans_per_charge"
                                        value="{{ getAmount($general->bal_trans_per_charge) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Balance transfer charges')</label>
                                    <input class="form-control  form-control-lg" type="text"
                                        name="bal_trans_fixed_charge"
                                        value="{{ getAmount($general->bal_trans_fixed_charge) }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold"> @lang('User1 Detail') </label>
                                    <input class="form-control form-control-lg" type="text" name="user1_detail"
                                        value="{{ $general->user1_detail }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('User2 Detail')</label>
                                    <input class="form-control  form-control-lg" type="text" name="user2_detail"
                                        value="{{ $general->user2_detail }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('User3 Detail')</label>
                                    <input class="form-control  form-control-lg" type="text" name="user3_detail"
                                        value="{{ $general->user3_detail }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold"> @lang('User1 Direct') </label>
                                    <input class="form-control form-control-lg" type="text" name="user1_direct"
                                        value="{{ $general->user1_direct }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('User2 Direct')</label>
                                    <input class="form-control  form-control-lg" type="text" name="user2_direct"
                                        value="{{ $general->user2_direct }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('User3 Direct')</label>
                                    <input class="form-control  form-control-lg" type="text" name="user3_direct"
                                        value="{{ $general->user3_direct }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('User Registration')</label>
                                    <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                        data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')"
                                        data-off="@lang('Disabled')" name="registration"
                                        @if ($general->registration) checked @endif>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('Promo Account')</label>
                                    <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                        data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')"
                                        data-off="@lang('Disabled')" name="promo_account"
                                        @if ($general->promo_account) checked @endif>
                                </div>
                            </div>

                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold">@lang('Force Secure Password')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                    data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')"
                                    data-off="@lang('Disabled')" name="secure_password"
                                    @if ($general->secure_password) checked @endif>
                            </div>

                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold">@lang('Force SSL')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                    data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')"
                                    data-off="@lang('Disabled')" name="force_ssl"
                                    @if ($general->force_ssl) checked @endif>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-lg-3 col-sm-6 col-md-4">
                                <label class="form-control-label font-weight-bold">@lang('Email Verification')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                    data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')"
                                    data-off="Disable" name="ev" @if ($general->ev) checked @endif>
                            </div>
                            <div class="form-group col-lg-3 col-sm-6 col-md-4">
                                <label class="form-control-label font-weight-bold">@lang('Email Notification')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                    data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')"
                                    data-off="Disable" name="en" @if ($general->en) checked @endif>
                            </div>
                            <div class="form-group col-lg-3 col-sm-6 col-md-4">
                                <label class="form-control-label font-weight-bold">@lang('SMS Verification')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                    data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')"
                                    data-off="Disable" name="sv" @if ($general->sv) checked @endif>
                            </div>
                            <div class="form-group col-lg-3 col-sm-6 col-md-4">
                                <label class="form-control-label font-weight-bold">@lang('SMS Notification')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                    data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')"
                                    data-off="Disable" name="sn" @if ($general->sn) checked @endif>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Update')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/spectrum.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/spectrum.css') }}">
@endpush
@push('style')
    <style>
        .sp-replacer {
            padding: 0;
            border: 1px solid rgba(0, 0, 0, .125);
            border-radius: 5px 0 0 5px;
            border-right: none;
        }

        .sp-preview {
            width: 100px;
            height: 46px;
            border: 0;
        }

        .sp-preview-inner {
            width: 110px;
        }

        .sp-dd {
            display: none;
        }
    </style>
@endpush

@push('script')
    <script>
        'use strict';
        (function($) {
            $('.colorPicker').spectrum({
                color: $(this).data('color'),
                change: function(color) {
                    $(this).parent().siblings('.colorCode').val(color.toHexString().replace(/^#?/, ''));
                }
            });

            $('.colorCode').on('input', function() {
                var clr = $(this).val();
                $(this).parents('.input-group').find('.colorPicker').spectrum({
                    color: clr,
                });
            });

            $("select[name=cary_flash]").val("{{ $general->cary_flash }}");
            $("select[name=matching_bonus_time]").val("{{ $general->matching_bonus_time }}");
            $("select[name=weekly_time]").val("{{ $general->matching_when }}");
            $("select[name=monthly_time]").val("{{ $general->matching_when }}");
            $("select[name=daily_time]").val("{{ $general->matching_when }}");

            $('select[name=matching_bonus_time]').on('change', function() {
                matchingBonus($(this).val());
            });

            matchingBonus($('select[name=matching_bonus_time]').val());

            function matchingBonus(matching_bonus_time) {
                if (matching_bonus_time == 'daily') {
                    document.getElementById('weekly_time').style.display = 'none';
                    document.getElementById('monthly_time').style.display = 'none'
                    document.getElementById('daily_time').style.display = 'block'

                } else if (matching_bonus_time == 'weekly') {
                    document.getElementById('weekly_time').style.display = 'block';
                    document.getElementById('monthly_time').style.display = 'none'
                    document.getElementById('daily_time').style.display = 'none'
                } else if (matching_bonus_time == 'monthly') {
                    document.getElementById('weekly_time').style.display = 'none';
                    document.getElementById('monthly_time').style.display = 'block'
                    document.getElementById('daily_time').style.display = 'none'
                }
            }
        })(jQuery)
    </script>
@endpush
