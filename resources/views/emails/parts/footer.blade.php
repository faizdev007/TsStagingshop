<table width="650" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper" style="table-layout:fixed;">
    <tr>
        <td colspan="3" align="left" valign="top" width="100%" height="2" style="background-color: {{ settings('primary_colour') }}; border-collapse:collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; mso-line-height-rule: exactly; line-height: 1px;"><!--[if gte mso 15]>&nbsp;<![endif]--></td>
    </tr>
    <tr>
        
        <td class="pad_side" colspan="3">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper">
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
         <td align="center" style="font-family:Arial, sans-serif; font-size:12px; line-height:20px; color:#333333; text-align:center;">
               
                @if( !empty(settings('telephone')) )
                <div class="footer-sn--item" style="display: inline-block; margin: 0 5px;">
                    <a href="tel:{{ settings('telephone') }}" class="u-hover-opacity-70" target="_blank" aria-label="Call {{ strip_tags(settings('telephone')) }}"><img src="{{ themeAsset('images/svg/phone.png') }}" alt="{{ settings('site_name') }}" ></a>
                </div>
                @endif
                @if( !empty(settings('instagram_url')) )
                <div class="footer-sn--item" style="display: inline-block; margin: 0 5px;">
                <a href="{{settings('instagram_url')}}" class="u-hover-opacity-70" target="_blank" aria-label="Follow us on Instagram"><img src="{{ themeAsset('images/svg/instagram.png') }}" alt="{{ settings('site_name') }}" ></a>
                </div>
                @endif
                @if( !empty(settings('facebook_url')) )
                <div class="footer-sn--item" style="display: inline-block; margin: 0 5px;">
                <a href="{{settings('facebook_url')}}" class="u-hover-opacity-70" target="_blank" aria-label="Follow us on Facebook">
                    <img src="{{ themeAsset('images/svg/facebook.png') }}" alt="{{ settings('site_name') }}" ></a>
                </div>
                @endif

                 @if( !empty(settings('tiktok_url')) )
                <div class="footer-sn--item" style="display: inline-block; margin: 0 5px;">
                <a href="{{settings('tiktok_url')}}" class="u-hover-opacity-70" target="_blank" aria-label="Follow us on TikTok">
                    <img src="{{ themeAsset('images/svg/tiktok.png') }}" alt="{{ settings('site_name') }}" >
                    </a>
                </div>
                @endif

                @if( !empty(settings('youtube_url')) )
                <div class="footer-sn--item" style="display: inline-block; margin: 0 5px;">
                    <a href="{{settings('youtube_url')}}" class="u-hover-opacity-70" target="_blank" aria-label="Follow us on YouTube">
                        <img src="{{ themeAsset('images/svg/youtube.png') }}" alt="{{ settings('site_name') }}" >
                    </a>
                </div>
                @endif
                
                @if( !empty(settings('twitter_url')) )
                <div class="footer-sn--item" style="display: inline-block; margin: 0 5px;">
                    <a href="{{settings('twitter_url')}}" class="u-hover-opacity-70" target="_blank" aria-label="Follow us on Twitter"> <img src="{{ themeAsset('images/svg/twitter.png') }}" alt="{{ settings('site_name') }}" >
                        </a>
                </div>
                @endif

                @if( !empty(settings('linkedin_url')) )
                <div class="footer-sn--item" style="display: inline-block; margin: 0 5px;">
                    <a href="{{settings('linkedin_url')}}" class="u-hover-opacity-70" target="_blank" aria-label="Follow us on LinkedIn"><img src="{{ themeAsset('images/svg/linkedin.png') }}" alt="{{ settings('site_name') }}" >
                        </a></div>@endif

                @if( !empty(settings('pinterest_url')) )
                <div class="footer-sn--item" style="display: inline-block; margin: 0 5px;">
                    <a href="{{settings('pinterest_url')}}" class="u-hover-opacity-70" target="_blank" aria-label="Follow us on Pinterest">
                    <img src="{{ themeAsset('images/svg/pinterest.png') }}" alt="{{ settings('site_name') }}" >
                    </a>
                </div>
                @endif

                @if( !empty(settings('email')) )
                    <div class="footer-sn--item" style="display: inline-block; margin: 0 5px;">
                        <a href="mailto:{{ settings('email') }}" target="_blank" aria-label="Email {{ settings('email') }}">
                            <img src="{{ themeAsset('images/svg/email.png') }}" alt="{{ settings('site_name') }}" >
                        </a>
                    </div>
                @endif

            </td>
                </tr>
                <tr>
                    <td height="25" style="line-height:1px;font-size:1px;"  class="fix_height">&nbsp;</td>
                </tr>
            </table>
        </td>
        
    </tr>
</table>
</body>
</html>