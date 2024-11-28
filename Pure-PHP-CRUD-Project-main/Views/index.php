<?php
require "_partials/header.php";

echo '<div class="container">';

echo '<div class="w-100 d-flex mt-2">

    <div class="w-30 w-100-m text-left">
        <div class="search-box">
            <input type="text" placeholder="Search anything" class="search-input">
            <a href="#" class="search-btn">
                <i class="fa-solid fa-magnifying-glass"></i>
            </a>
        </div>
    </div>

    <div class="w-30 w-100-m text-left">
        <div class="filter-type">
            <div class="dropdown">
                <a href="#" class="js-link">Filter by : <i class="fa fa-chevron-down"></i></a>
                <ul class="js-dropdown-list">
                    <li>Femme</li>
                    <li>Homme</li>
                    <li>Famille</li>
                    <li>Enfant</li>
                    <li>* Reset</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="w-30 w-100-m text-right fixAddNewBtn">
        <a class="addNewShop">
            Add new shop
        </a>
    </div>
</div>';

    echo '<div class="w-100 d-flex flex-wrap mt-2 justify-content-center-m cards-container">';
        include ('shopList.php');
    echo '</div>';
echo '</div>';

include ('components/modal.php');

include ('_partials/footer.php');