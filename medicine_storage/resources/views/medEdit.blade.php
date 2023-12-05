<?php

use Illuminate\Support\Facades\DB;

$companies = DB::table('companies')->get();
$com_count = count($companies);
$categories = DB::table("categories")->get();
$cat_count = count($categories);
?>
<!DOCTYPE html>
<html lang="en">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

<?php include('E:\laravel\medicine_storage\resources\views\header.blade.php') ?>

<body>


    <h2 class="text-center brand-logo brand-text"">Edit Post : {{$med->trade_en}}</h2>
    <div style=" margin: 70px 50px; background:gray;">
        <form action="{{route('medicines.update',$med->id)}}" method="post" style="padding: 100px 100px;">
            @csrf
            @method('PUT')
            <input type="text" required value="{{$med->scientific_en}}" name="scientific_en" placeholder="Enter scientific_en">
            <br> <br>
            <input type="text" required value="{{$med->scientific_ar}}" name="scientific_ar" placeholder="Enter scientific_ar">
            <br> <br>
            <input type="text" required value="{{$med->trade_en}}" name="trade_en" placeholder="Enter trade_en">
            <br> <br>
            <input type="text" required value="{{$med->trade_ar}}" name="trade_ar" placeholder="Enter trade_ar">
            <br> <br>
            <input type="number" required value="{{$med->quantity}}" name="quantity" placeholder="Enter quantity">
            <br> <br>
            <input type="number" required value="{{$med->price}}" name="price" placeholder="Enter price">
            <br> <br>
            <input type="text"  value="{{$med->endDate}}" name="endDate" placeholder="Enter endDate">
            <br> <br>
            <input type="text"  value="{{$med->image}}" name="image" placeholder="Enter image">
            <br> <br>
            <select required name="company_name_en" Size="Number_of_options" class="card z-depth-0 ">
                @foreach($companies as $company)
                <option> {{$company->name_en}}</option>
                @endforeach
            </select>
            <br> <br>
            <select required name="category_name_en" Size="Number_of_options" class="card z-depth-0 ">
                @foreach($categories as $category)
                <option> {{$category->name_en}}</option>
                @endforeach
            </select>
            <br> <br>
            <input type="submit" class="btn btn-primary" value="Submit"></input>


        </form>
        </div>

        <?php include('E:\laravel\medicine_storage\resources\views\footer.php') ?>

</body>

</html>
