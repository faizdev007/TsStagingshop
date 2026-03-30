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
            <div style="text-align: center;font-family:Arial, sans-serif;font-size:16px;line-height:18px;color:#333333;">

              <p>Hi Admin,</p>
              <p>New enquiry received. Please check the details below:</p>
              @php
                $feilds = [
                  'name' => 'Full Name',
                  'email' => 'Email Address',
                  'telephone' => 'Telephone Number',
                  'country' => 'Country',
                  'message' => 'Message',
                  'ref' => 'Ref.',
                  'url' => 'URL',
                  'category' => 'Category',
                ];
              @endphp

              <ul>
                @foreach($feilds as $feild_key => $label)
                  @php
                    $val = (!empty($enquiry->$feild_key)) ? $enquiry->$feild_key : false;
                    if(!empty($val)){
                  @endphp
                  <li><strong>{{$label}}:</strong> {{$val}}</li>
                  @php
                    }
                  @endphp
                @endforeach

                @php
                  $data = (!empty($enquiry->data)) ? json_decode($enquiry->data) : false;
                  if(!empty($data)){
                  foreach($data as $data_key => $data_val){
                      $data_key = str_replace('_',' ',$data_key);
                          if(!empty($data_val)){
                @endphp
                <li><strong>{{ucwords($data_key)}}:</strong> {{ucwords($data_val)}}</li>
                @php
                  }
              }
          }
                @endphp
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
