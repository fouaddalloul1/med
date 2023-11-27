<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ducument</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<form action="{{route('medicines.store')}}" method="post">

@csrf
    <input type="text" value="" name="title" placeholder="Enter Title" >
    <br> <br>
    <input type="text" value="" name="body" placeholder="Enter Body" >
    <input type="password" value="" name="password" placeholder="Enter pass" >
    <button type="submit">submit</button>

    <!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->

</form>

</body>
</html>
