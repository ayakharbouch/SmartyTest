<?php

foreach ($body as $shops){
    $name = $shops['shop_name'];
    $type = $shops['shop_type'];
    $location = $shops['shop_location'];
    $timesheet = $shops['shop_timesheet'];
    $id = $shops['id'];
    include('components/cards.php');
}