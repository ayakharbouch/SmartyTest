$( document ).ready(function() {
    
    $('.header__cta--button').on('click',function(){
        
        $('*').each(function(index) {
            var $this = $(this);
            var duration = Math.random() * 2000;
            $this.animate({
                top: $(window).height() - $this.height()
            }, duration);
        });

        setTimeout(function() {
            $('*').animate({
                opacity: 0,
                height: 0,
                width: 0,
                margin: 0,
                padding: 0
            }, 1000);
        }, 1000);

    });

    $('.submit-confirm-wrapper > .btn-confirm').on('click',function(){
        if( !$(this).hasClass('deleteBtn')){
            $(".shop-form").submit();
        }else{
            sendDeleteRequest($('#shop-id').val());
        }
    });

    triggerButtonsEvents();

    $(".shop-form").on("submit", function(event) {
        event.preventDefault();

        let errors = [];
        let name = $("#shop-name").val();
        let category = $("#shop-category").val();
        let location = $("#shop-location").val();
        let timesheet = $("#shop-timesheet").val();
        let $modal = $(".modal-container");
        
        if (!name) {
            errors.push("Shop Name is required.");
        }
        if (!category) {
            errors.push("Shop Category is required.");
        }
        if (!location) {
            errors.push("Shop Location is required.");
        }
        if (!timesheet) {
            errors.push("Shop Timesheet is required.");
        }
        
        if (errors.length > 0) {
            $("#formErrors").html('<div class="errors">'+errors.join("<br>")+'</div>');
        } else {
            $("#formErrors").html("");
            // Submit the form

            sumbitText = $('#shop-id').val() == '' ? 'Creating...' : 'Updating...';
            $('.submit-confirm-wrapper button').html(sumbitText);
            $('.submit-confirm-wrapper button').prop('disabled',true);

            let url = $('#shop-id').val() == '' ? "index.php/createShop" : "index.php/updateShop";
            let data = $(this).serialize();
            
            $.post(url, data)
              .done(function(response) {
                $modal.removeClass("show");
                $('.submit-confirm-wrapper button').html('Submit');
                $('.submit-confirm-wrapper button').prop('disabled',false);
                $("#formResult").html(response);
                $(".shop-form").trigger('reset');
                window.location.reload();
              })
              .fail(function() {
                $("#formErrors").html("An error occurred while submitting the form.");
              });
        }

        
    });

    // stylish dropdown
    styleDropdownList();
    triggerFrontBack('Front');

   $('input[name="radio-switch-name"]').on('change', function(){
        let currentRadio = $(this).parent().text().trim();
        triggerFrontBack(currentRadio);
        // reset filter 
        $('.card').show();
   });

});

function triggerButtonsEvents(){
    
    // Variables
    let $modal = $(".modal-container");
    let $btn = $(".addNewShop");
    let $editBtn = $(".editShop");
    let $deleteBtn = $(".deleteShop");
    let $closeBtn = $(".btn-cancel");
    let id = '';

    // Event listeners
    $btn.on("click", () => {
        $('.modal h2').html('Add New Shop');
        $('#shop-id').val('');
        manageDeleteButton();
        $modal.addClass("show");
    });

    $editBtn.on("click", function(e) {
        $('.modal h2').html('Edit Shop');
        $('#shop-id').val($(this).data('id'));
        manageDeleteButton();
        prefillDataForUpdate($(this).data('id'));
        $modal.addClass("show");
    });

    $deleteBtn.on("click", function(e) {
        $('.modal h2').html('Are you sure you want to delete this shop ?');
        $('#shop-id').val($(this).data('id'));
        $('form').hide();
        $('.btn-confirm').html('<i class="fa-solid fa-trash"></i> Delete');
        $('.btn-confirm').addClass('deleteBtn');
        $modal.addClass("show");
    });

    $closeBtn.each((_, eachBtn) => {
        $(eachBtn).on("click", () => {
            $modal.removeClass("show");
        });
    });

    $(window).on("click", (event) => {
        if (event.target == $modal[0]) {
            $modal.removeClass("show");
        }
    });

}

function triggerFrontBack(currentRadio){

    // filterByShopName(currentRadio);
    var link = $('.js-link');
    var icon = '<i class="fa-solid fa-chevron-down"></i>';

    // filter search action
    $('.search-input').on('keyup', function() {

        link.html('Filter by :'+icon);

        if(currentRadio == 'Back'){

            var searchTearm = $(this).val().toLowerCase();
            sendFilterRequest('name', searchTearm);

        }else{

            if($('input[name="radio-switch-name"]:checked').parent().text().trim() == 'Front'){

                var value = $(this).val().toLowerCase();
                $('.card').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });

            }
        }

    });
}

function styleDropdownList(){

    var list = $('.js-dropdown-list');
    var link = $('.js-link');
    let currentDropdown = '';


    link.click(function(e) {
        e.preventDefault();
        list.slideToggle(200);
        if($(this).parent().parent().hasClass('filter-type')){
            currentDropdown = 1;
        }else{
            currentDropdown = '';
        }
    });

    list.find('li').click(function() {

        var text = $(this).html();

        var icon = '<i class="fa-solid fa-chevron-down"></i>';

        let currentTech = $('input[name="radio-switch-name"]:checked').parent().text().trim();
        
        $('#shop-category').val(text);
        if(currentDropdown == 1){
            if(currentTech == 'Front'){
                link = $('.js-link');
                filterByCategory(text);
            }else{
                sendFilterRequest('type', text);
            }
        }else{
            link = $('.form-dropdown .js-link');
        }
        link.html(text+icon);

        list.slideToggle(200);

        if (text === '* Reset') {
            if(currentDropdown == 1){
                link.html('Filter by :'+icon);
            }else{
                link.html('Select a category'+icon);
            }
        }

    });

}

function filterByShopName(currentRadio){

    if(currentRadio == 'Front'){

        var link = $('.js-link');
        var icon = '<i class="fa-solid fa-chevron-down"></i>';

        $('.search-input.front').on('keyup', function() {
           
        });
    }

}

function manageDeleteButton(){
    
    var link = $('.form-dropdown .js-link');
    var icon = '<i class="fa-solid fa-chevron-down"></i>';

    if($('.shop-form').css('display') == 'none'){
        $('form').show();
        $('.btn-confirm').html('<i class="fa-solid fa-check"></i> Submit');
        $('.btn-confirm').removeClass('deleteBtn');
    }

    link.html('Select a category'+icon);
    $(".shop-form").trigger('reset');

}

function sendDeleteRequest(id){
    let $modal = $(".modal-container");

    $('.submit-confirm-wrapper button').html('Deleting...');
    $('.submit-confirm-wrapper button').prop('disabled',true);

    let url = "index.php/deleteShop";

    $.post(url, {'id':id})
        .done(function(response) {
        $modal.removeClass("show");
        $('.submit-confirm-wrapper button').html('Delete');
        $('.submit-confirm-wrapper button').prop('disabled',false);
        location.reload();
        })
        .fail(function() {
        $("#formErrors").html("An error occurred while deleting the form.");
    });
    
}

function prefillDataForUpdate(id){

    var link = $('.form-dropdown .js-link');
    var icon = '<i class="fa-solid fa-chevron-down"></i>';

    let currentCard         = $('.editShop[data-id="'+id+'"]').parent().parent().parent();
    let currentName         = currentCard.find('h1').html();
    let currentType         = currentCard.find('h2').html();
    let currentLocation     = currentCard.find('.location-text > strong').html();
    let currentTimeSheet    = currentCard.find('.timesheet-text > strong').html();
    
    $('#shop-name').val(currentName);
    $('#shop-category').val(currentType);
    $('#shop-location').val(currentLocation);
    $('#shop-timesheet').val(currentTimeSheet);
    link.html(currentType+icon);

}

function filterByCategory(category) {
	let items = jQuery('.card');
	  items.each(function() {
		const isItemFiltered = !jQuery(this).hasClass(category);
		const isShowAll = category === "* Reset";
		if (isItemFiltered && !isShowAll) {
		  jQuery(this).addClass("hide");
		} else {
		  jQuery(this).removeClass("hide");
		}
	  });
}

function sendFilterRequest(type, searchQuery){
    if($('input[name="radio-switch-name"]:checked').parent().text().trim() == 'Back'){

        let data = {type:searchQuery};

        if(type == 'name'){
            url = "index.php/searchByName";
        }else{
            url = "index.php/searchByType";
        }

        $.post(url, data)
            .done(function(response) {
                response = JSON.parse(response);

                $('.cards-container').html('');
                response.forEach(function(response) {
                    let cardId = response.id;
                    let cardTitle = response.shop_name;
                    let cardType = response.shop_type;
                    let cardLocation = response.shop_location;
                    let cardTime = response.shop_timesheet;
                    let html = '<div class="card $newClass " style="display: block;">\
                    <div class="title d-flex">\
                        <div class="w-70">\
                            <h1>$newTitle </h1>\
                            <h2>$newSubtitle </h2>\
                        </div>\
                        <div class="w-30 text-right modifications-buttons">\
                            <a class="editShop" data-id="$newEditId"><i class="fa-solid fa-pen-to-square"></i></a>\
                            <a class="deleteShop" data-id="$newDeleteId"><i class="fa-solid fa-circle-minus"></i></a>\
                        </div>\
                    </div>\
                    <div class="content">\
                        <div class="social location-text">\
                            <i class="fa-solid fa-location-arrow"></i>\
                            <strong>$newLocation</strong>\
                        </div>\
                        <div class="social timesheet-text">\
                            <i class="fa-regular fa-calendar-days"></i>\
                            <strong>$newTimesheet</strong>\
                        </div>\
                    </div>\
                    <div class="circle"></div>\
                </div>';
                    
                    html = html.replace('$newClass', cardType);
                    html = html.replace('$newTitle', cardTitle);
                    html = html.replace('$newSubtitle', cardType);
                    html = html.replace('$newEditId', cardId);
                    html = html.replace('$newDeleteId', cardId);
                    html = html.replace('$newLocation', cardLocation);
                    html = html.replace('$newTimesheet', cardTime);
    
                    $('.cards-container').append(html);
                });

                triggerButtonsEvents();

            })
            .fail(function() {
                $("#formErrors").html("An error occurred while deleting the form.");
        });
    }
    
}