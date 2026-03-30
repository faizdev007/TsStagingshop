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
            
              <p style="text-align: center;font-weight:bold;margin-bottom:20px;">New PDF download request received. Please check the details below:</p>

              <div style="text-align: left;margin-left: 20px;">
                <p><strong>Full Name:</strong> {{ $fullname }}</p>
                <p><strong>Email Address:</strong> {{ $email }}</p>
                @if(isset($telephone) && $telephone)
                <p><strong>Phone Number:</strong> {{ $telephone }}</p>
                @endif
                <p><strong>Property Details:</strong></p>
                <div style="margin-left: 20px;">
                  <p><strong>Reference:</strong> {{ $property_ref }}</p>
                  <p><strong>Title:</strong> {{ $property_title }}</p>
                  <p><strong>Complex:</strong> {{ $property_area }}</p>
                  <p><strong>Price:</strong> {{ $property_price }}</p>
                </div>
                @if(isset($newsletter) && $newsletter == 'yes')
                <p><strong>Newsletter:</strong> Subscribed</p>
                @endif
              </div>

            </div>
          </td>
        </tr>
        <tr>
          <td height="25" style="line-height:1px;font-size:1px;" class="fix_height">&nbsp;</td>
        </tr>
      </table>
    </td>
    <td width="30" class="hide">&nbsp;</td>
  </tr>
</table>
@include('emails.parts.footer')
