@if(\App\Models\Extension::where('act', 'custom-captcha')->where('status', 1)->first())
    <fieldset>
        <div class="input-group">
            @php echo  getCustomCaptcha($height = 46, $width = '100%', $bgcolor = 'white', $textcolor = 'black') @endphp
        </div>
    </fieldset> 
    <fieldset>
        <label>
            @lang('Captcha Code') *
        </label>
        <input type="text" class="form-control b-radius--capsule" name="captcha" maxlength="6" placeholder="@lang('Enter Code')" required>
    </fieldset>
@endif
