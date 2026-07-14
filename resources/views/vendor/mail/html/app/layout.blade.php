<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>{{ config('app.name') }}</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="color-scheme" content="light">
        <meta name="supported-color-schemes" content="light">
        <style>
            html, body {
                padding: 0;
                margin: 0;
                font-family: Inter, Helvetica, "sans-serif";
            }
            a:hover {
                color: #009ef7;
            }
        </style>
    </head>
    <body id="kt_body" class="app-blank">
        <div id="kt_app_root">
            <div style="background-color:#D5D9E2; --kt-scrollbar-color: #d9d0cc; --kt-scrollbar-hover-color: #d9d0cc">
                <div id="#kt_app_body_content" style="background-color:#D5D9E2; font-family:Arial,Helvetica,sans-serif; line-height: 1.5; min-height: 100%; font-weight: normal; font-size: 15px; color: #2F3044; margin:0; padding:1px; width:100%;">
                    <div style="background-color:#ffffff; padding: 45px 0 34px 0; border-radius: 24px; margin:40px auto; max-width: 600px;">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" height="auto" style="border-collapse:collapse">
                            <tbody>
                                {!! $header ?? '' !!}
                                <tr>
                                    <td style="">
                                        <div class="text-center mx-5 mb-5">
                                            <div class="mb-4 fs-6 fw-medium font-sans">
                                                <p style="text-align:center; margin-bottom:9px; color:#181C32; font-size: 22px; font-weight:700">
                                                    {!! Illuminate\Mail\Markdown::parse($slot) !!}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="">
                                        {!! $subcopy ?? '' !!}
                                    </td>
                                </tr>
                                {!! $footer ?? '' !!}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
