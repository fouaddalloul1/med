<?php

use Illuminate\Support\Facades\DB;

$com_count = count(DB::table('companies')->get());
$cat_count = count(DB::table("category")->get())
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ducument</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>


<body>
    <div style="margin: 70px 50px; background:gray;">
        <form action="{{route('medicines.store')}}" method="post" style="padding: 100px 100px;">

            @csrf
            <input type="text" required value="" name="sentific" placeholder="Enter sentific">
            <br> <br>
            <input type="text" required value="" name="trade" placeholder="Enter trade">
            <br> <br>
            <input type="number" required value="" name="quantity" placeholder="Enter quantity">
            <br> <br>
            <input type="number" required value="" name="price" placeholder="Enter price">
            <br> <br>
            <input type="number" required min="1" max="{{$com_count}}" name="company_id" value="1" placeholder="Enter company_id" style="width: 190px;">
            <br> <br>
            <input type="number" required min="1" max="{{$cat_count}}" name="category_id" value="1" placeholder="Enter category_id" style="width: 190px;">
            <br> <br>
            <input type="submit" class="btn btn-primary" value="Submit"></input>

            <!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->

        </form>
    </div>


</body>

</html>
