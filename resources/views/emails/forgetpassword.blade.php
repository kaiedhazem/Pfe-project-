<html>
    <head>
        <title>Forget Password Email</title>
    </head>
    <body>
        <table>
            <tr><td>Dear {{ $name }}</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>Your account has been successfully updated .<br> Your account information is as below with new password : </td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td> Email :{{ $email }}</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td> Password :{{ $password }}</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td> Thanks,for using our app.<br> {{ config('app.name') }} </td></tr>
        </table>
    </body>
</html>