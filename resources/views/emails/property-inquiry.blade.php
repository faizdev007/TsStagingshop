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
              <p>New property inquiry received. Please check the details below:</p>
              
              <table width="100%" border="0" cellspacing="0" cellpadding="10" style="border-collapse: collapse; margin: 20px 0;">
                <tr style="background-color: #f8f8f8;">
                  <th style="border: 1px solid #ddd; text-align: center;">Details</th>
                  <th style="border: 1px solid #ddd; text-align: center;">Information</th>
                </tr>
                
                <tr>
                  <td style="border: 1px solid #ddd; font-weight: bold;">First Name</td>
                  <td style="border: 1px solid #ddd;">{{ $enquiry->fullname ?? 'Not provided' }}</td>
                </tr>
                
                <tr style="background-color: #f8f8f8;">
                  <td style="border: 1px solid #ddd; font-weight: bold;">Last Name</td>
                  <td style="border: 1px solid #ddd;">{{ $enquiry->lastname ?? 'Not provided' }}</td>
                </tr>
                
                <tr>
                  <td style="border: 1px solid #ddd; font-weight: bold;">Country</td>
                  <td style="border: 1px solid #ddd;">{{ $enquiry->country ?? 'Not provided' }}</td>
                </tr>
                
                <tr style="background-color: #f8f8f8;">
                  <td style="border: 1px solid #ddd; font-weight: bold;">Area</td>
                  <td style="border: 1px solid #ddd;">{{ $enquiry->area ?? 'Not provided' }}</td>
                </tr>
                
                <tr>
                  <td style="border: 1px solid #ddd; font-weight: bold;">Email</td>
                  <td style="border: 1px solid #ddd;">{{ $enquiry->email ?? 'Not provided' }}</td>
                </tr>
                
                <tr style="background-color: #f8f8f8;">
                  <td style="border: 1px solid #ddd; font-weight: bold;">Telephone</td>
                  <td style="border: 1px solid #ddd;">{{ $enquiry->telephone ?? 'Not provided' }}</td>
                </tr>
                
                <tr style="background-color: #f8f8f8;">
                  <td style="border: 1px solid #ddd; font-weight: bold;">Message</td>
                  <td style="border: 1px solid #ddd;">{{ $enquiry->message ?? 'Not provided' }}</td>
                </tr>
                
                <!-- <tr>
                  <td style="border: 1px solid #ddd; font-weight: bold;">Newsletter Subscription</td>
                  <td style="border: 1px solid #ddd;">{{ $enquiry->newsletter }}</td>
                </tr> -->
              </table>
              
              <!-- <p>Please respond to this inquiry as soon as possible.</p>
              <p>Thank you,<br>{{ config('app.name') }} Team</p> -->
            </div>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>
    </td>
    <td width="30" class="hide">&nbsp;</td>
  </tr>
</table>
@include('emails.parts.footer')
