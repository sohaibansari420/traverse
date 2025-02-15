@extends($activeTemplate . 'user.layouts.app')

@section('panel')
    @include($activeTemplate . 'user.partials.breadcrumb')

    <div class="row">
        <div class="col-xl-12 wow fadeInUp" data-wow-delay="1.2s">
            <div class="card">
                <div class="card-header">
                    <div class="card-title text-white">{{ $page_title }}</div>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                                class="fe fe-chevron-up"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{ route('user.other.tree.search') }}" method="GET"
                                class="form-inline float-right bg--white">
                                <div class="input-group has_append">
                                    <input type="text" name="username" class="form-control"
                                        placeholder="@lang('Search by username')">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-12 mt-4">
                            <section class="management-tree">
                                <div class="mgt-container">
                                    <div class="mgt-wrapper">
                                        <div class="mgt-item">

                                            <div class="mgt-item-parent">
                                                @php echo showSingleUserinTreeUser($tree['a']); @endphp
                                            </div>

                                            <div class="mgt-item-children">

                                                <div class="mgt-item-child">
                                                    <div class="mgt-item">

                                                        <div class="mgt-item-parent">
                                                            @php echo showSingleUserinTreeUser($tree['b']); @endphp
                                                        </div>

                                                        <div class="mgt-item-children">
                                                            <div class="mgt-item-child">
                                                                <div class="mgt-item">

                                                                    <div class="mgt-item-parent">
                                                                        @php echo showSingleUserinTreeUser($tree['d']); @endphp
                                                                    </div>

                                                                    <div class="mgt-item-children">

                                                                        <div class="mgt-item-child">
                                                                            @php echo showSingleUserinTreeUser($tree['h']); @endphp
                                                                        </div>

                                                                        <div class="mgt-item-child">
                                                                            @php echo showSingleUserinTreeUser($tree['i']); @endphp
                                                                        </div>

                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <div class="mgt-item-child">
                                                                <div class="mgt-item">

                                                                    <div class="mgt-item-parent">
                                                                        @php echo showSingleUserinTreeUser($tree['e']); @endphp
                                                                    </div>

                                                                    <div class="mgt-item-children">

                                                                        <div class="mgt-item-child">
                                                                            @php echo showSingleUserinTreeUser($tree['j']); @endphp
                                                                        </div>

                                                                        <div class="mgt-item-child">
                                                                            @php echo showSingleUserinTreeUser($tree['k']); @endphp
                                                                        </div>

                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>


                                                <div class="mgt-item-child">
                                                    <div class="mgt-item">

                                                        <div class="mgt-item-parent">
                                                            @php echo showSingleUserinTreeUser($tree['c']); @endphp
                                                        </div>

                                                        <div class="mgt-item-children">

                                                            <div class="mgt-item-child">
                                                                <div class="mgt-item">

                                                                    <div class="mgt-item-parent">
                                                                        @php echo showSingleUserinTreeUser($tree['f']); @endphp
                                                                    </div>

                                                                    <div class="mgt-item-children">

                                                                        <div class="mgt-item-child">
                                                                            @php echo showSingleUserinTreeUser($tree['l']); @endphp
                                                                        </div>

                                                                        <div class="mgt-item-child">
                                                                            @php echo showSingleUserinTreeUser($tree['m']); @endphp
                                                                        </div>

                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <div class="mgt-item-child">
                                                                <div class="mgt-item">

                                                                    <div class="mgt-item-parent">
                                                                        @php echo showSingleUserinTreeUser($tree['g']); @endphp
                                                                    </div>

                                                                    <div class="mgt-item-children">

                                                                        <div class="mgt-item-child">
                                                                            @php echo showSingleUserinTreeUser($tree['n']); @endphp
                                                                        </div>

                                                                        <div class="mgt-item-child">
                                                                            @php echo showSingleUserinTreeUser($tree['o']); @endphp
                                                                        </div>

                                                                    </div>

                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
                <!-- table-wrapper -->
            </div>
            <!-- section-wrapper -->

        </div>
    </div>

    <!-- Modal -->
    <div class="modal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenter">@lang('User Details')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="user-details-modal">
                        <div class="text-center">

                            <a class="user-name tree_url btn btn-primary" href="">View Tree</a>
                            <br/>
                            <span class="user-status tree_status"></span>
                            <span class="user-status tree_plan"></span>
                            <br/>
                            <span class="user-status tree_stormplan"></span>

                            <h6 class="my-3">@lang('Referred By'): <span class="tree_ref"></span></h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>@lang('LEFT')</th>
                                    <th>@lang('RIGHT')</th>
                                </tr>

                                <tr>
                                    <td>@lang('Current BV')</td>
                                    <td><span class="lbv"></span></td>
                                    <td><span class="rbv"></span></td>
                                </tr>
                                <tr>
                                    <td>@lang('Free Member')</td>
                                    <td><span class="lfree"></span></td>
                                    <td><span class="rfree"></span></td>
                                </tr>

                                <tr>
                                    <td>@lang('Paid Member')</td>
                                    <td><span class="lpaid"></span></td>
                                    <td><span class="rpaid"></span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.showDetails').on('click', function() {
                var modal = $('#exampleModalCenter');

                $('.tree_name').text($(this).data('name'));
                $('.tree_url').attr({
                    "href": $(this).data('treeurl')
                });
                $('.tree_status').text($(this).data('status'));
                $('.tree_plan').text($(this).data('plan'));
                $('.tree_stormplan').text($(this).data('stormplan'));
                $('.tree_image').attr({
                    "src": $(this).data('image')
                });
                $('.user-details-header').removeClass('Paid');
                $('.user-details-header').removeClass('Free');
                $('.user-details-header').addClass($(this).data('status'));
                $('.tree_ref').text($(this).data('refby'));
                $('.lbv').text($(this).data('lbv'));
                $('.rbv').text($(this).data('rbv'));
                $('.lpaid').text($(this).data('lpaid'));
                $('.rpaid').text($(this).data('rpaid'));
                $('.lfree').text($(this).data('lfree'));
                $('.rfree').text($(this).data('rfree'));
                $('#exampleModalCenter').modal('show');
            });
        })(jQuery);
    </script>
@endpush

@push('style-lib')
    <!-- Tree css -->
    <link href="{{ asset($activeTemplateTrue . 'dashboard/css/tree.css') }}" rel="stylesheet" />
@endpush
