@include('emails.parts.header')
<table width="650" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper" style="table-layout:fixed;">
    <tr>
        <td width="30" class="hide">&nbsp;</td>
        <td class="pad_side">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper">
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="black" valign="top">
                        <div style="font-family:Arial, sans-serif;font-size:16px;line-height:18px;color:#333333;">
                            <?php $datetime = date('M d, Y h:i:s a'); ?>
                            <?=$subscriber->email?> has just signed up to newsletters on the website on <?=$datetime?>.
                        </div>
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
