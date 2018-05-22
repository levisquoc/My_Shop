<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mail thông báo từ {$url}</title>
</head>
<body>
<p>Dear <strong> {{$name}}</strong>,</p>

<p>Chúng tôi đã nhận được yêu cầu của bạn.</p>
<p><strong>Subject:</strong> {{ $subject }}</p>
<p><strong>E-Mail:</strong> {{ $email }}</p>
<p><strong>Phone:</strong> {{ $phone }}</p>
<p><strong>Message:</strong> {{ $mes }}</p>

<p>Thanks and best regards.</p>
</body>
</html>