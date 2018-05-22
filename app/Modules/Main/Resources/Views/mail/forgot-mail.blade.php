<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recovery Password</title>
</head>
<body>
<h3>Hi {{$name}}</h3>
<p>Someone requested a new password for your account</p>
<p>Please use the following link to <a href="{{route('admin.password.token', ['token' => $token, 'email' => $email])}}"><strong>reset
            your password</strong></a></p>
<p>This link is good until 2 hour and can only be used once</p>
</body>
</html>