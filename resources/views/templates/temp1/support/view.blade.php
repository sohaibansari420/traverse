@extends($activeTemplate.'layouts.master')

@section('content')
    @include($activeTemplate.'layouts.breadcrumb')


    <section class="blog-section padding-bottom padding-top">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 mb-lg-0">
                    <div class="comment-area mb-5">
                        <div class="row justify-content-center justify-content-lg-between">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header   justify-content-between">
                                        <h5 class="card-title mt-0 ">
                                            @if($my_ticket->status == 0)
                                                <span class="badge badge-success py-2 px-3">@lang('Open')</span>
                                            @elseif($my_ticket->status == 1)
                                                <span class="badge badge-primary py-2 px-3">@lang('Answered')</span>
                                            @elseif($my_ticket->status == 2)
                                                <span class="badge badge-warning py-2 px-3">@lang('Replied')</span>
                                            @elseif($my_ticket->status == 3)
                                                <span class="badge badge-dark py-2 px-3">@lang('Closed')</span>
                                            @endif
                                            <br>
                                            [@lang('Ticket')#{{ $my_ticket->ticket }}] {{ $my_ticket->subject }}
                                        </h5>
                                        @if($my_ticket->status !== 3)
                                            <span class="btn-danger close" type="button" title="@lang('Close Ticket')"
                                                  data-toggle="modal" data-target="#DelModal"><i
                                                    class="fa fa-lg fa-times-circle"></i></span>
                                        @endif
                                    </div>


                                </div>
                            </div>
                        </div>
                        @if($my_ticket->status != 3)
                            <form class="comment-form" method="post"
                                  action="{{ route('ticket.reply', $my_ticket->id) }}" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <textarea name="message" placeholder="@lang('Your Reply') ..." required></textarea>
                                </div>
                                <div class="row form-group justify-content-between">
                                    <div class="col-md-8 ">
                                        <div class="row justify-content-between">
                                            <div class="col-md-11">
                                                <div class="form-group">
                                                    <span for="inputAttachments text-white">@lang('Attachments')</span>
                                                    <input type="file"
                                                           name="attachments[]"
                                                           id="inputAttachments"
                                                           class="form-control"/>
                                                    <div id="fileUploadsContainer"></div>
                                                    <p class="my-2 ticket-attachments-message text-muted">
                                                        @lang("Allowed File Extensions: .jpg, .jpeg, .png, .pdf")
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <a href="javascript:void(0)"
                                                       class="btn btn-danger btn-round mt-4"
                                                       onclick="extraTicketAttachment()">
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn--success  mt-4" name="replayTicket"
                                                value="1">
                                            <i class="fa fa-reply"></i> @lang('Reply')
                                        </button>
                                    </div>
                                </div>

                            </form>
                        @endif
                    </div>

                    <div class="comment-area">
                        <ul class="comment-wrapper">
                            <li>
                                @foreach($messages as $message)
                                    @if($message->admin_id == 0)
                                        <div class="comment-item d-flex flex-wrap">
                                            <div class="comment-thumb">
                                                <a class="h-100">
                                                    <img class="h-100" src="{{getImage('')}}" alt="*">
                                                </a>
                                            </div>
                                            <div class="comment-content">
                                                <div
                                                    class="comment-header align-items-center d-flex flex-wrap justify-content-between">
                                                    <div class="comment-header-left ">
                                                        <h6 class="sub-title"><a>{{ $message->ticket->name }}</a></h6>
                                                        <span>  @lang('Posted on') {{ diffForHumans($message->created_at) }}</span>
                                                    </div>
                                                </div>
                                                <p class="comment-custom-style">{{$message->message}}</p>
                                                @if($message->attachments()->count() > 0)
                                                    <div class="mt-2">
                                                        @foreach($message->attachments as $k=> $image)
                                                            <a href="{{route('ticket.download',encrypt($image->id))}}"
                                                               class="mr-3 Attachment"><i
                                                                    class="fa fa-file"></i> @lang('Attachment') {{++$k}}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <ul>
                                            <li>
                                                <div class="comment-item d-flex flex-wrap">
                                                    <div class="comment-thumb">
                                                        <a class="h-100">
                                                            <img class="h-100"
                                                                 src="{{getImage('assets/images/logoIcon/favicon.png')}}"
                                                                 alt="*">
                                                        </a>
                                                    </div>
                                                    <div class="comment-content">
                                                        <div
                                                            class="comment-header align-items-center d-flex flex-wrap justify-content-between">
                                                            <div class="comment-header-left ">
                                                                <h6 class="sub-title">
                                                                    <a>{{ $message->admin->name }}</a>
                                                                </h6>
                                                                <p class="text-small text-muted my-1">@lang('Staff') (
                                                                    <span>  @lang('Posted on') {{ diffForHumans($message->created_at) }} </span>
                                                                    ) </p>

                                                            </div>
                                                        </div>
                                                        <p class="comment-custom-style">{{$message->message}}</p>
                                                        @if($message->attachments()->count() > 0)
                                                            <div class="mt-2">
                                                                @foreach($message->attachments as $k=> $image)
                                                                    <a href="{{route('ticket.download',encrypt($image->id))}}"
                                                                       class="mr-3"><i
                                                                            class="fa fa-file"></i> @lang('Attachment') {{++$k}}
                                                                    </a>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>

                                    @endif
                                @endforeach

                            </li>
                        </ul>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <div class="modal fade" id="DelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ route('ticket.reply', $my_ticket->id) }}">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title"> @lang('Confirmation')!</h5>
                        <button type="button" class="close close-button" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <strong class="text-dark">@lang('Are you sure you want to Close This Support Ticket')?</strong>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i
                                class="fa fa-times"></i>
                            @lang('Close')
                        </button>
                        <button type="submit" class="btn btn--success btn-sm" name="replayTicket"
                                value="2"><i class="fa fa-check"></i> @lang("Confirm")
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('css')
    <link rel="stylesheet" href="{{asset($activeTemplateTrue . 'frontend/css/ticket.css')}}">
@endpush

@push('script')
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                $('.delete-message').on('click', function (e) {
                    $('.message_id').val($(this).data('id'));
                });
            });
        })(jQuery);

        function extraTicketAttachment() {
            $("#fileUploadsContainer").append('<input type="file" name="attachments[]" class="form-control mt-1" required />')
        }
    </script>
@endpush
