<?php

use Illuminate\Support\Facades\DB;

$companies = DB::table('companies')->get();
$com_count = count($companies);
$categories = DB::table("category")->get();
$cat_count = count($categories);

$com_id = DB::table('companies')->where('name', "wissam")->get();
?>
<!DOCTYPE html>
<html lang="en">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

<?php include('E:\laravel\medicine_storage\resources\views\header.blade.php') ?>

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
            <!-- <input type="number" required min="1" max="{{$com_count}}" name="company_id" value="1" placeholder="Enter company_id" style="width: 190px;">
            <br> <br>
            <input type="number" required min="1" max="{{$cat_count}}" name="category_id" value="1" placeholder="Enter category_id" style="width: 190px;">
            <br> <br> -->
            <select required name="company_name" Size="Number_of_options" class="card z-depth-0 ">
                @foreach($companies as $company)
                <option> {{$company->name}}</option>
                @endforeach
            </select>
            <br> <br>
            <select required name="category_name" Size="Number_of_options" class="card z-depth-0 ">
                @foreach($categories as $category)
                <option> {{$category->name}}</option>
                @endforeach
            </select>
            <br> <br>
            <input type="submit" class="btn btn-primary" value="Submit"></input>
            <!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->

        </form>
    </div>

    <?php include('E:\laravel\medicine_storage\resources\views\footer.php') ?>

</body>

</html>