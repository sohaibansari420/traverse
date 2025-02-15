@if(\App\Models\Extension::where('act', 'custom-captcha')->where('status', 1)->first())
    <fieldset>
        <div class="input-group">
            @php echo  getCustomCaptcha($height = 46, $width = '100%', $bgcolor = '#171F29', $textcolor = '#798DA3') @endphp
        </div>
    </fieldset> 
    <fieldset>
        <label>
            @lang('Captcha Code') *
        </label>
        <input type="text" name="captcha" maxlength="6" placeholder="@lang('Enter Code')" required>
    </fieldset>
@endif
