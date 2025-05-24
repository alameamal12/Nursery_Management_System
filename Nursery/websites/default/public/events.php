<?php
require 'public_header.php';
require 'image_slider.php';
?>
<main>
<section class="about-us">
<h2>Little Learners Nursery School Upcoming Events</h2>
<div class="container-fluid">
<div class="row">
<?php 
include("db.php");
$result_events=$pdo->query("select * from events where status=1 order by eventdate asc");
$row_events=$result_events->fetchObject();
$count_events=$result_events->rowCount();
if($count_events>0){ do{
$image = (!empty($row_events->event_image)) ? $row_events->event_image : 'uploads/noimage.jpg';
echo "
<div class='col-lg-4'>
<div class='card card-info'>
<div class='card-header'><h4 class='card-title text-bold text-center' style='text-transform:uppercase;'>".$row_events->eventname."</h4></div>
<div class='card-body' style='height:350px;overflow-y:scroll;'>
<table class='table table-striped'>
<tr><td>Date:</td><td>".$row_events->eventdate."</td></tr>
<tr><td>Category:</td><td>".$row_events->category."</td></tr>
<tr><td colspan='2'><img src='uploads/".$image."' alt='Add Images' style='width:50px;max-height:50px;height: expression(this.height > 50 ? 50: true);min-height:50px;height: expression(this.height < 50 ? 50: true);' class='img-circle'></td></tr>
<tr><td colspan='2'>".$row_events->details."</td></tr>
</table>
</div>
</div>
</div>";
}while($row_events=$result_events->fetchObject()); } ?>
</div>
</div>
</section>
<?php
require 'footer.php';
?>
