@include('emails.parts.header')
<table width="650" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper" style="table-layout:fixed;">
    <tr>
        <td width="30" class="hide">&nbsp;</td>
        <td class="pad_side">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper">
                <tr>
                    <td class="black" align="left" valign="top" style="font-family:Arial, sans-serif;font-size:16px;line-height:17px;color:#333333;">
                        <strong>Dear {{ $valuation->client->client_name }}</strong>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="black" valign="top">
                        <p style="font-family:Arial, sans-serif;font-size:16px;line-height:18px;color:#333333;">Thank you for giving us the opportunity to give you a full market appraisal on {{ $valuation->client_valuation_street }}, {{ $valuation->client_valuation_city }}, {{ $valuation->client_valuation_postcode }}</p>
                        <p style="font-family:Arial, sans-serif;font-size:16px;line-height:18px;color:#333333;">We have now completed our market appraisal and you can view the full details or your specific property report by clicking the button below</p>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <div><!--[if mso]>
                            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ url('valuation-report/'.$valuation->uuid.'') }}" style="height:40px;v-text-anchor:middle;width:220px;" arcsize="125%" stroke="f" fillcolor="#EB523D">
                                <w:anchorlock/>
                                <center>
                            <![endif]-->
                            <a href="{{ url('valuation-report/'.$valuation->uuid.'') }}"
                               style="background-color:#EB523D;border-radius:50px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:13px;font-weight:bold;line-height:40px;text-align:center;text-decoration:none;width:220px;-webkit-text-size-adjust:none;">SEE MY VALUATION</a>
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
                    <td>
                        <p style="font-family:Arial, sans-serif;font-size:16px;line-height:18px;color:#333333;"><strong>Once again thank you for considering us to market your property and we would love the chance to work with you further.</strong></p>
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