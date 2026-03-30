@include('emails.parts.header')
<table width="650" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper" style="table-layout:fixed;">
    <tr>
        <td width="30" class="hide">&nbsp;</td>
        <td class="pad_side">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper">
                <tr>
                    <td class="black" align="left" valign="top" style="font-family:Arial, sans-serif;font-size:22px;line-height:17px;color:#333333;">
                        Hi,
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="black" valign="top">
                        <p style="font-family:Arial, sans-serif;font-size:16px;line-height:18px;color:#333333;">Just to let you know, that {{ $valuation->client->client_name }} has accepted your market valuation.</p>
                    </td>
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
