<?php

use Illuminate\Support\Facades\DB;

$medicines =  DB::table('medicines')->get();
?>
<!DOCTYPE html>
<html>
<?php include('E:\laravel\medicine_storage\resources\views\header.blade.php') ?>

<h4 class="center grey-text">Medicines!</h4>
<div class="container">
    <form action="{{route('medicines.destroy',-1)}}" method="post" class="center">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn center "> Delete All </button>
    </form>
    <div class="row">
        <?php foreach ($medicines as $med) : ?>

            <div class="col s6 md3">
                <div class="card z-depth-0 ">
                    <div class="card-content center align-content-start">
                        <h6>trade : <?php echo htmlspecialchars($med->trade_en); ?></h6>

                        <div>sentific : <?php echo htmlspecialchars($med->scientific_en); ?></div>
                        <div>quantitiy : <?php echo htmlspecialchars($med->quantity); ?></div>
                        <div>price : <?php echo htmlspecialchars($med->price); ?></div>
                        <?php $com = DB::table('companies')->where('id', $med->company_id)->get();
                        $cat = DB::table('categories')->where('id', $med->category_id)->get();
                        ?>
                        <div>company name : <?php echo htmlspecialchars($com[0]->name_en); ?></div>
                        <div>category name : <?php echo htmlspecialchars($cat[0]->name_en); ?></div>

                    </div>
                    <div class="card-action right-align">

                        <form style="display: inline-block;" action="{{route('medicines.destroy',$med->id)}}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"> DELETE </button>
                        </form>
                        <a href="{{route('medicines.edit',$med->id)}}" class="">Edit</a>
                        <a href="details.php?id=<?php echo $med->id; ?>" class="brand-text">more info</a>

                    </div>





                </div>
            </div>

        <?php endforeach; ?>
    </div>
    <?php include('E:\laravel\medicine_storage\resources\views\footer.php') ?>

</html>
