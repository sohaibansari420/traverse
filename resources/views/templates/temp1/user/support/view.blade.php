@extends($activeTemplate . 'user.layouts.app')

@section('panel')
    @include($activeTemplate . 'user.partials.breadcrumb')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h5 class="card-title mt-0">
                            @if ($my_ticket->status == 0)
                                <span class="badge badge-success">@lang('Open')</span>
                            @elseif($my_ticket->status == 1)
                                <span class="badge badge-primary ">@lang('Answered')</span>
                            @elseif($my_ticket->status == 2)
                                <span class="badge badge-warning">@lang('Replied')</span>
                            @elseif($my_ticket->status == 3)
                                <span class="badge badge-danger">@lang('Closed')</span>
                            @endif
                            [@lang('Ticket')#{{ $my_ticket->ticket }}] {{ $my_ticket->subject }}
                        </h5>
                    </div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                                class="fe fe-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($my_ticket->status != 4)
                        <form method="post" action="{{ route('ticket.reply', $my_ticket->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-between">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea name="message" class="form-control form-control-lg" id="inputMessage" placeholder="@lang('Your Reply') ..."
                                            rows="4" cols="10" required></textarea>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group mb-0">
                                <span for="inputAttachments text-white">@lang('Attachments')</span>
                                <input name="attachments[]" id="customFile" type="file" class="dropify custom-file-input"
                                    data-height="180" accept=".jpg,.jpeg,.png,.pdf" />
                            </div>

                            <p class="text-muted m-2">
                                <i class="la la-info-circle"></i> @lang('Allowed File Extensions: .jpg, .jpeg, .png, .pdf')
                            </p>

                            <div class="row">
                                <button type="submit" class="btn btn-primary btn-block" name="replayTicket"
                                    value="1"><i class="fa fa-reply"></i> @lang('Reply')</button>
                            </div>
                        </form>
                    @endif
                </div>
                <!-- table-wrapper -->
            </div>
            <!-- section-wrapper -->

        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title text-white">Ticket Information</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                                class="fe fe-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    @foreach ($messages as $message)
                        @if ($message->admin_id == 0)
                            <div class="row border border-primary border-radius-3 my-3 py-3 mx-2">
                                <div class="col-md-3 border-right text-right">
                                    <h5 class="my-3">{{ $message->ticket->name }}</h5>
                                </div>
                                <div class="col-md-9">
                                    <p class="text-muted font-weight-bold my-3">
                                        @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                    <p>{{ $message->message }}</p>
                                    @if ($message->attachments()->count() > 0)
                                        <div class="mt-2">
                                            @foreach ($message->attachments as $k => $image)
                                                <a href="{{ route('ticket.download', encrypt($image->id)) }}"
                                                    class="mr-3"><i class="fa fa-file"></i> @lang('Attachment')
                                                    {{ ++$k }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="row border border-warning border-radius-3 my-3 py-3 mx-2"
                                style="background-color: #ffd96729">
                                <div class="col-md-3 border-right text-right">
                                    <h5 class="my-3">{{ $message->admin->name }}</h5>
                                    <p class="lead text-muted">@lang('Staff')</p>
                                </div>
                                <div class="col-md-9">
                                    <p class="text-muted font-weight-bold my-3">
                                        @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                    <p>{{ $message->message }}</p>
                                    @if ($message->attachments()->count() > 0)
                                        <div class="mt-2">
                                            @foreach ($message->attachments as $k => $image)
                                                <a href="{{ route('ticket.download', encrypt($image->id)) }}"
                                                    class="mr-3"><i class="fa fa-file"></i> @lang('Attachment')
                                                    {{ ++$k }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <!-- File uploads js -->
    <script src="{{ asset($activeTemplateTrue . 'dashboard/plugins/fileuploads/js/dropify.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'dashboard/js/filupload.js') }}"></script>
@endpush

@push('style-lib')
    <!-- File Uploads css-->
    <link href="{{ asset($activeTemplateTrue . 'dashboard/plugins/fileuploads/css/dropify.css') }}" rel="stylesheet"
        type="text/css" />
@endpush
