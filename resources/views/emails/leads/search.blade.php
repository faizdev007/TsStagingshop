@include('emails.parts.header')
<table width="650" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper" style="table-layout:fixed;">
    <tr>
        <td width="30" class="hide">&nbsp;</td>
        <td class="pad_side">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper">
                <tr>
                    <td class="black" align="center" valign="top" style="font-family:Arial, sans-serif;font-size:22px;line-height:17px;color:#333333;">
                        Hi, {{ $lead->lead->name }}
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="black" valign="top">
                        <p style="font-family:Arial, sans-serif;font-size:16px;line-height:18px;color:#333333;">Hope you are having an amazing day!</p>
                        <p style="font-family:Arial, sans-serif;font-size:16px;line-height:18px;color:#333333;">The following properties have been added to our site that match your saved search criteria :</p>
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

            <?php foreach($properties as $property){ ?>

            <table width="300" border="0" cellspacing="0" cellpadding="0" align="left" class="wrapper" style="height: 380px;">
                <tr>
                    <td align="center" valign="top">
                        <table width="310" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper">
                            <tr>
                                <td width="35" class="hide">&nbsp;</td>
                                <td   valign="top">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                        <tr>
                                            <td align="center">
                                                <strong style="font-family:Arial, sans-serif;font-size:16px;line-height:18px;color:#333333;">{{ $property->property_status }}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" valign="top"><a target="_blank" href="<?=$property->url;?>"><img src="{{ $property->primary_photo }}" width="265" height="199" alt="img_265X130" style="display:block;border:none; max-width:265px;" /></a></td>
                                        </tr>
                                        <tr>
                                            <td height="25" class="fix_height">&nbsp;</td>
                                        </tr>
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
                                            <td class="red" align="left" valign="top" style="font-family:Arial, sans-serif;font-size:13px;line-height:20px;color:#dc0c15;"><a href="<?=$property->url;?>" target="_blank" style="text-decoration:underline;color:#e9921c;">View Property &gt;</a></td>
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

            <?php } ?>

        </td>
    </tr>
</table>

<table width="650" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper" style="table-layout:fixed;">
    <tr>
        <td width="30" class="hide">&nbsp;</td>
        <td class="pad_side">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper">
                <tr>
                    <td class="black" valign="top">
                        <p style="font-family:Arial, sans-serif;font-size:16px;line-height:18px;color:#333333;"><strong>We'd love to help you with your property search!</strong></p>
                        <p style="font-family:Arial, sans-serif;font-size:16px;line-height:18px;color:#333333;">If you can’t find exactly what you are looking for then give us a call and we will research the market to find you your perfect property. Let us do the hard work.</p>
                        @if( settings('telephone') )
                            <p style="font-family:Arial, sans-serif;font-size:16px;line-height:18px;color:#333333;">Call us on: <a href="tel:{{ settings('telephone') }}">{{ settings('telephone') }}</a></p>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <p style="font-family:Arial, sans-serif;font-size:16px;line-height:18px;color:#333333;">Thanks and we hope to catch you again soon.</p>
                        <p style="font-family:Arial, sans-serif;font-size:16px;line-height:18px;color:#333333;">{{ settings('site_name') }}</p>
                    </td>
                </tr>

                <tr>
                    <td>
                        <p style="font-family:Arial, sans-serif;font-size:16px;line-height:18px;color:#333333;"><strong>Web:</strong> <a target="_blank" href="{{ env('APP_URL') }}">{{ env('APP_URL') }}</a></p>
                        @if( settings('email') )
                            <p style="font-family:Arial, sans-serif;font-size:16px;line-height:18px;color:#333333;"><strong>E-Mail:</strong> <a href="mailto:{{ settings('email') }}">{{ settings('email') }}</a></p>
                        @endif
                        @if( settings('telephone') )
                            <p style="font-family:Arial, sans-serif;font-size:16px;line-height:18px;color:#333333;"><strong>Tel:</strong> {{ settings('telephone') }}</p>
                        @endif
                        <p style="font-family:Arial, sans-serif;font-size:16px;line-height:18px;color:#333333;"><strong>Address:</strong> {!! settings('contact_address') !!}</p>
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
<table width="100%" border="0" align="center" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
    <tr>
        <td align="center">
            <table width="650" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper">
                <tr>
                    <td><table width="650" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper">
                            <tr>
                                <td>
                                    <table width="650" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper">
                                        <tr>
                                            <td width="50">&nbsp;</td>
                                            <td class="black" align="center" style="font-family:Arial, sans-serif; font-size:12px; line-height:20px; color:#333333;"><p>&nbsp;</p>
                                                <p>Kind Regards,<br />
                                                    <strong>{{ settings('site_name') }}</strong></p>
                                                <p>This email was sent from the {{ settings('site_name') }} website and is intended for the recipient {{ $lead->lead->name }} <br />
                                                <p data-email="propertyalertemail">Click here to <a href="{{ url('leads/unsubscribe?id='.$lead->lead_automation_id) }}">unsubscribe</a>.</p>
                                                </p>
                                            </td>
                                            <td width="50">&nbsp;</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="20">&nbsp;</td>
                            </tr>
                        </table></td>
                </tr>
            </table>
        </td>
    </tr>
    <!-- Emailer Ends Here //-->
</table>
</body>
</html>
