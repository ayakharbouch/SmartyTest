<div class="card <?= $type ?>">
    
    <div class="title d-flex">
      
        <div class="w-70">
            <h1><?php echo $name; ?></h1>
            <h2><?= $type ?></h2>
        </div>
        <div class="w-30 text-right modifications-buttons">
            <a class="editShop" data-id="<?= $id ?>"><i class="fa-solid fa-pen-to-square"></i></a>
            <a class="deleteShop" data-id="<?= $id ?>"><i class="fa-solid fa-circle-minus"></i></a>
        </div>
        
    </div>

    <div class="content">
        <div class="social location-text">
            <!-- <i class="fa-solid fa-location-dot"></i> -->
            <i class="fa-solid fa-location-arrow"></i>
            <strong><?= $location ?></strong>
        </div>
            
        <div class="social timesheet-text">
            <i class="fa-regular fa-calendar-days"></i>
            <strong ><?= $timesheet ?></strong>
        </div>

    </div>
    <div class="circle"></div>
  </div>



