<div class="footer">
    <table border="0" width="100%">
        <tr>
            <td class="f-logo" width="50%">
                <img src="{{ themeAsset('images/logos/logo.png') }}" width="300px">
            </td>
            <td class="f-info" width="50%">
                <p>Mobile: <a href="tel:{{ settings('telephone') }}" class="text-red">{{ settings('telephone') }}</a></p>
                <p>Email: <a href="mailto:{{ settings('email') }}">{{ settings('email') }}</a></p>
                <p>{!! settings('footer_address') !!} <br/>  {{ url('/') }} </p>
            </td>
            
        </tr>
    </table>
</div>
