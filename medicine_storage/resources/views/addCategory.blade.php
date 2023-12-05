<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ducument</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<?php include('E:\laravel\medicine_storage\resources\views\header.blade.php') ?>

<body>

    <div style="margin: 70px 50px; background:gray;">
        <form action="{{route('categories.store')}}" method="post" style="padding: 100px 100px;">

            @csrf
            <input type="text" value="" name="name_en" placeholder="Enter category name_en">
            <br> <br>
            <input type="text" value="" name="name_ar" placeholder="Enter category name_ar">
            <br> <br>
            <input type="text" value="" name="image" placeholder="Enter image">
            <br> <br>
            <button type="submit">submit</button>

            <!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->

        </form>
    </div>

<?php include('E:\laravel\medicine_storage\resources\views\footer.php') ?>

</body>

</html>
