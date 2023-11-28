<?php

use Illuminate\Support\Facades\DB;

$medicines =  DB::table('medicines')->get();
?>
<!DOCTYPE html>
<html>
<?php include('E:\laravel\medicine_storage\resources\views\header.blade.php') ?>
<h4 class="center grey-text">Medicines!</h4>
<div class="container">
    <div class="row">
        <?php foreach ($medicines as $med) : ?>

            <div class="col s6 md3">
                <div class="card z-depth-0 ">
                    <div class="card-content center">
                        <h6><?php echo htmlspecialchars($med->trade); ?></h6>

                        <div><?php echo htmlspecialchars($med->sentific); ?></div>
                        <div><?php echo htmlspecialchars($med->quantity); ?></div>
                        <div><?php echo htmlspecialchars($med->price); ?></div>

                        <?php $com = DB::table('companies')->where('id', $med->company_id)->get();
                        $cat = DB::table('category')->where('id', $med->category_id)->get();
                        ?>
                        <div><?php echo htmlspecialchars($com[0]->name); ?></div>
                        <div><?php echo htmlspecialchars($cat[0]->name); ?></div>


                    </div>
                    <div class="card-action right-align">
                            <a href="details.php?id=<?php echo $med->id; ?>" class="brand-text">more info</a>
                        </div>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
<?php include('E:\laravel\medicine_storage\resources\views\footer.php') ?>

</html>
