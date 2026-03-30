@include('emails.parts.header')

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif;">

  <tr>

    <td width="30" class="hide">&nbsp;</td>

    <td><img 

      <table width="100%" cellpadding="0" cellspacing="0" border="0">

        <!-- <tr>

          <td style="padding: 20px 0; text-align: center;">

            <img src="{{ url('assets/demo1/images/logo.svg') }}" alt="{{ config('app.name') }} Logo" style="max-width: 200px; height: auto;">

          </td>

        </tr> -->

        <tr>

          <td style="background-color: #ffffff; padding: 20px; border-radius: 5px;">

            <div style="color:rgb(40, 40, 40); font-size: 16px; line-height: 1.5;">

              <h2 style="color:rgb(40, 40, 40); margin-top: 0;">Login Verification</h2>

              

              <p>You have requested to log in to your {{ config('app.name') }} account. Please use the One-Time Password (OTP) below:</p>

              

              <table width="100%" cellpadding="10" cellspacing="0" style="margin: 20px 0; border-collapse: collapse;">

                <tr style="background-color: #f8f8f8;">

                  <td style="border: 1px solid #ddd; font-weight: bold; text-align: center; font-size: 24px; color:rgb(40, 40, 40);">

                    {{ $otp }}

                  </td>

                </tr>

              </table>

              

              <p>This OTP is valid for 5 minutes. Do not share this code with anyone.</p>

              

              <p style="margin-top: 20px; font-size: 14px; color: #7f8c8d;">

                If you did not request this login, please ignore this email or contact our support team.

              </p>

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

