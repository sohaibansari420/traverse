@push('script-lib')
    <script src="{{ asset($activeTemplateTrue.'frontend/js/jquery.inputLettering.js') }}"></script>
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue.'js/jquery.inputLettering.js') }}"></script>
@endpush

@push('script')
    <script>
        (function ($) {
            "use strict";
            $('#phoneInput').letteringInput({
                    inputClass: 'letter',
                    onLetterKeyup: function ($item, event) {
                        console.log('$item:', $item);
                        console.log('event:', event);
                    },
                    onSet: function ($el, event, value) {
                        console.log('element:', $el);
                        console.log('event:', event);
                        console.log('value:', value);
                    }
                });

        })(jQuery);
    </script>
@endpush



@push('script')
    <script>
        (function ($) {
            "use strict";
            $('#phoneInput').letteringInput({
                inputClass: 'letter',
            });
        })(jQuery);
    </script>
@endpush

