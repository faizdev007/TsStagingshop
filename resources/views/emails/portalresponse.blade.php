@include('emails.parts.header')
<table width="650" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper" style="table-layout:fixed;">
    <tr>
        <td width="30" class="hide">&nbsp;</td>
        <td class="pad_side">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper">
                <tr>
                    <td class="black" align="center" valign="top" style="font-family:Arial, sans-serif;font-size:22px;line-height:17px;color:#333333;">
                        <div style="font-family:Arial, sans-serif;font-size:20px;line-height:24px;color:#333333; text-align: center; font-weight: bold;">
                            Thank you for your Property Enquiry!
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="black" valign="top">
                        <p style="font-family:Arial, sans-serif;font-size:16px;line-height:18px;color:#333333;">Hello {{ $name }}, thank you for your enquiry from {{ $source }}.</p>
                        <p style="font-family:Arial, sans-serif;font-size:16px;line-height:18px;color:#333333;">You enquired about a property in {{ $property->town ? $property->town : $property->city }}. The details of the property can be seen below:</p>
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
<table width="650" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper" style="table-layout:fixed;" bgcolor="#ffffff">
    <tr>
        <td class="pad_side">
            <table width="310" border="0" cellspacing="0" cellpadding="0" align="left" class="wrapper">
                <tr>
                    <td align="center" valign="top">
                        <table width="310" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper">
                            <tr>
                                <td width="35" class="hide">&nbsp;</td>
                                <td valign="top">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                        <tr>
                                            <td align="left" valign="top"><a target="_blank" href="<?=$property->url;?>"><img src="{{ $property->primary_photo }}" width="265" height="199" alt="img_265X130" style="display:block;border:none; max-width:265px;" /></a></td>
                                        </tr>
                                        <tr>
                                            <td height="25" class="fix_height">&nbsp;</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table width="310" border="0" cellspacing="0" cellpadding="0" align="left" class="wrapper">
                <tr>
                    <td align="center" valign="top">
                        <table width="310" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper">
                            <tr>
                                <td width="35" class="hide">&nbsp;</td>
                                <td valign="top">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                        <tr>
                                            <td class="black" align="left" valign="top" style="font-family:Arial, sans-serif;font-size:14px;line-height:18px;color:#000000; height: 17px;">
                                                <?php if($property->beds){ ?>
                                                <?=$property->beds;?> Beds
                                                <?php } ?>
                                                <?php if($property->bathrooms){ ?>
                                                <?=$property->baths;?> Baths
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="8" style="line-height:1px;font-size:1px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td class="grey" align="left" valign="top" style="font-family:Arial, sans-serif;font-size:18px;line-height:20px;color:#000000; font-weight: bold;"><?=$property->display_price?></td>
                                        </tr>
                                        <tr>
                                            <td height="8" style="line-height:1px;font-size:1px;">&nbsp;</td>
                                        </tr>
                                        <tr>

                                            <?php
                                            $tenure_url = ($property->is_rent) ? 'rent' : 'sale';
                                            ?>

                                            <td class="grey" align="left" valign="top" style="font-family:Arial, sans-serif;font-size:13px;line-height:20px;color:#000000;">
                                                <?=$property->property_type?> for <?=$tenure_url?>
                                                <?php
                                                $city = trim($property->city);
                                                $city = urlencode($property->city);
                                                $city = strtolower($property->city);
                                                ?>
                                                <?php if ( !empty($city) ) { ?> near <br />
                                                <a target="_blank" style="color: #000000; text-decoration: underline"
                                                   href="{{ url('property-for-'.$tenure_url.'/in/'.$city) }}"><?=$property->city?></a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="15" style="line-height:1px;font-size:1px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td height="40" style="line-height:1px;font-size:1px;" class="hide">&nbsp;</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table width="650" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper" style="table-layout:fixed;">
    <tr>
        <td width="30" class="hide">&nbsp;</td>
        <td class="pad_side">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper">
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="black" align="center" valign="top">
                        <p style="font-family:Arial, sans-serif;font-size:16px;line-height:18px;color:#333333;">To view this property in more detail, please click on the link below.</p>
                        <p>
                        <div><!--[if mso]>
                            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $property->url }}" style="height:40px;v-text-anchor:middle;width:220px;" arcsize="125%" stroke="f" fillcolor="{{ settings('primary_colour')}}">
                                <w:anchorlock/>
                                <center>
                            <![endif]-->
                            <a target="_blank" href="{{ url($property->url) }}"
                               style="background-color:{{ settings('primary_colour') }};border-radius:50px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:13px;font-weight:bold;line-height:40px;text-align:center;text-decoration:none;width:220px;-webkit-text-size-adjust:none;">VIEW PROPERTY</a>
                            <!--[if mso]>
                            </center>
                            </v:roundrect>
                            <![endif]--></div>
                        </p>
                        <p style="font-family:Arial, sans-serif;font-size:16px;line-height:18px;color:#333333;">If you'd like to get in contact with us directly, you can call us on {{ settings('telephone') }} where we'll be happy to speak with you.</p>
                        <p style="font-family:Arial, sans-serif;font-size:16px;line-height:18px;color:#333333;">We look forward to hearing from you!</p>
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
