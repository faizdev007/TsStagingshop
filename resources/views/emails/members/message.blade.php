@include('emails.parts.header')

<table width="650" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper" style="table-layout:fixed;">
    <tr>
        <td width="30" class="hide">&nbsp;</td>
        <td class="pad_side">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper">
                <tr>
                    <div style="font-family:Arial, sans-serif;font-size:20px;line-height:24px;color:#333333; text-align: center; font-weight: bold;">
                        <p>You have a new message</p>
                    </div>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="black" valign="top">
                        <p style="font-family:Arial, sans-serif;font-size:16px;line-height:18px;color:#333333;">Hi, we wanted to let you know that you have received a new message. To see your new message, click on the link below.</p>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td align="center">
                        <div><!--[if mso]>
                            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ url('account/messages') }}" style="height:40px;v-text-anchor:middle;width:220px;" arcsize="125%" stroke="f" fillcolor="{{ settings('primary_colour') }}">
                                <w:anchorlock/>
                                <center>
                            <![endif]-->
                            <a href="{{ url('account/messages') }}"
                               style="background-color:{{ settings('primary_colour') }};border-radius:50px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:13px;font-weight:bold;line-height:40px;text-align:center;text-decoration:none;width:220px;-webkit-text-size-adjust:none;">VIEW MY MESSAGES</a>
                            <!--[if mso]>
                            </center>
                            </v:roundrect>
                            <![endif]--></div>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td height="25" style="line-height:1px;font-size:1px;"  class="fix_height">&nbsp;</td>
                </tr>
            </table>
        </td>
        <td width="30" class="hide">&nbsp;</td>
    </tr>
</table>

@include('emails.parts.footer')
