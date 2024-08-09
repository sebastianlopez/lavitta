@extends('email.layout')
@section('content')

<table align="center" border="0" cellpadding="0" cellspacing="0" style="background-color: #F5F7FA" width="100%">
    <tr>
        <td valign="top" width="100%">
            <table align="center" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="100%">
                        <table border="0" cellpadding="0" cellspacing="0" class="table_scale"
                               style="background-color: #35B6C8; background-size:cover"
                               width="600" background="{{asset('upload/mail/featured-background.png')}}">
                            <tr>
                                <td align="center" width="80%">

                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="500">
                                        <tr>
                                            <td style="background-color: #FFFFFF" height="40">&nbsp;
                                            </td>
                                        </tr>
                                       
                                        <tr>
                                            <td style="background-color: #FFFFFF" height="40">The File has finished importing <br/><br/><strong>{{ $filename }}</strong></td>
                                        </tr>
                                        <!--[if gte mso 9]> </v:textbox> </v:rect> <![endif]-->
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
@endsection