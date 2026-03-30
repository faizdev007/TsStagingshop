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
                            <p>Dear Admin,</p>
                            <p>You have received a new property alert.</p>

                            <h2>Property Alert details:</h2>

                            @php
                                $fields = [
                                  'url_from' => 'From',
                                  'fullname' => 'Full Name',
                                  'email' => 'Email Address',
                                  'contact' => 'Telephone Number',
                                  'for' => 'For',
                                  'in' => 'Location',
                                  'property_type' => 'Property Type',
                                  'beds' => 'Beds',
                                  'price_range' => 'Price Range',

                                ];
                            @endphp

                            <ul>
                                @foreach($fields as $field_key => $label)
                                    @php
                                        $val = (!empty($propertyalert->$field_key)) ? $propertyalert->$field_key : false;
                                        if(!empty($val)){
                                    @endphp
                                    <li><strong>{{$label}}:</strong> {{ urldecode($val) }}</li>
                                    @php
                                        }
                                    @endphp
                                @endforeach
                            </ul>
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
