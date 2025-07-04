<!doctype html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Reset Password Code</title>
    <style type="text/css">
        a:hover { text-decoration: underline !important; }
    </style>
</head>
<body style="margin: 0; background-color: #f2f3f8;">
<table width="100%" bgcolor="#f2f3f8" cellpadding="0" cellspacing="0" style="font-family: 'Open Sans', sans-serif;">
    <tr>
        <td>
            <table style="max-width:670px; margin:0 auto; background:#fff; border-radius:3px; text-align:center; box-shadow:0 6px 18px 0 rgba(0,0,0,.06);" width="100%" cellpadding="0" cellspacing="0">
                <tr><td style="height:40px;">&nbsp;</td></tr>
                <tr>
                    <td style="padding:0 35px;">
                        <h1 style="color:#1e1e2d; font-weight:500; font-size:32px;">Password Reset Code</h1>
                        <span style="display:inline-block; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>

                        <p style="color:#455056; font-size:15px; line-height:24px;">
                            You have requested to reset your password.
                        </p>

                        <p style="color:#455056; font-size:15px; line-height:24px; margin-top:15px;">
                            Please use the following code to reset your password:
                        </p>

                        <h2 style="color:#20e277; font-size:40px; margin:20px 0;">{{ $code }}</h2>

                        <p style="color:#455056; font-size:14px; line-height:24px;">
                            If you did not request this, please ignore this email.
                        </p>
                    </td>
                </tr>
                <tr><td style="height:40px;">&nbsp;</td></tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
