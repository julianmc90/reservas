
/**
 * Scripts to support seats functionality 
 * @date 2019-01-16 15:14:11
 * @author Julian Andres Muñoz Cardozo 
 */


/**
 * Cart
 */
var $cart = $('#selected-seats');
/**
 * counter
 */
var $counter = $('#counter');
/**
 * Holder userId
 */
var userId = $('#users_id');
/**
 * holder of reservation date
 */
var reservationDateDropDown = $('#reservation_date');

/**
 * Holder of movies id
 */
var moviesDropDown = $('#movies_id');

/**
 * Load of reservation data for dropdowns
 */
if(reservation != null){

    reservation.positions = JSON.parse(reservation.positions);
    userId.val(reservation.users_id);
    moviesDropDown.val(reservation.movies_id);
    moviesDropDown.trigger('change');
}


/**
 * Loads the seatGrid plugin with configuration
 */
var sc = $('#seat-map').seatCharts({
    map: [
      'aaaaaaaaaa',
      'aaaaaaaaaa',
      'aaaaaaaaaa',
      'aaaaaaaaaa',
      'aaaaaaaaaa'
    ],
    naming : {
        top : true,
        rows: ['A', 'B', 'C', 'D', 'E','F'],
        columns: ['1', '2', '3', '4', '5','6','7','8','9','10']
    },
    click: function () {

        if (this.status() == 'available') {

            $('<li>Silla # '+this.settings.id+': <a href="#" class="cancel-cart-item">[Borrar]</a></li>')
								.attr('id', 'cart-item-'+this.settings.id)
								.data('seatId', this.settings.id)
								.appendTo($cart);
            $counter.text(sc.find('selected').length+1);

            return 'selected';
        
        }else if (this.status() == 'selected') {

            $counter.text(sc.find('selected').length-1);
            
            //remove the item from our cart
            $('#cart-item-'+this.settings.id).remove();

            //seat has been vacated
            return 'available';
        
        }else if (this.status() == 'unavailable') {
            //seat has been already booked
            return 'unavailable';
        }
    }   

});

$(document).on('click', '.cancel-cart-item', function () {

    sc.get($(this).parents('li:first').data('seatId')).click();

});


/**
 * Reseat seats and counters
 */
function resetSeats(){
    sc.find('unavailable').status('available');
    sc.find('selected').status('available');
    $cart.empty();
    $counter.text(0);
}

/**
 * Call setDatesDropDown qhen change movies dropdown
 */
moviesDropDown.change(function() {
    
    setDatesDropDown();
});

/**
 * Get seats information when changes date dropdown
 */
reservationDateDropDown.change(function(){

    var reservationDate = $(this).val().trim();

    if(reservationDate != 'none'){
        getSeatingInfo(reservationDate, moviesDropDown.val());
        
    }else{
        resetSeats();
    }

});  

    
/**
 * Get infomation from seats if there are unavailable
 * and place de information usin seatinggrid plugin 
 * @param {String} reservation_date date string 
 * @param {int} movies_id movi identifier
 */
function getSeatingInfo(reservation_date, movies_id){

    resetSeats();

    $.ajax({
        url: baseUrl+"/reservation/getSeatingInfo/"+reservation_date+"/"+movies_id,
        type: 'GET',
        dataType:'json',
        success: function(result){
            
            if(result.length > 0){
                for (var i = 0; i < result.length; i++) {
                    var element = result[i];
                    //format of seats for jquery plugin
                    sc.get([element.x + "_"+element.y]).status('unavailable');
                }
            }

            /**
             * if is editing reservation place the already selected seats as editable
             */
            if(isEditReservation()){

                for (var i = 0; i < reservation.positions.length; i++) {
                    var element = reservation.positions[i];
                    var seat = element.x+"_"+element.y;
                    sc.get(seat).status('available');
                    sc.get(seat).click();
                }
            }
    }});  
}

/**
 * Checks if the edition data is the same as the current dropdowns
 */
function isEditReservation(){
    if(reservation != null){

        if(reservation.users_id == userId.val() &&
        reservation.movies_id == moviesDropDown.val() &&
        reservation.reservation_date == reservationDateDropDown.val()){
            
            return true;
        }
    }

    return false;
}

/**
 * Saave reservation
 */
function saveReservation(){

    var url = baseUrl+"/reservation";
    var typeRequest = 'POST';
    var seats = sc.find('a.selected').seatIds;
    var seatsFormated = [];

    /**
     * formatting seats
     */
    for (var i = 0; i < seats.length; i++) {
        var element = seats[i];
        var parts = element.split("_");            
        seatsFormated[i] = {x:parts[0], y: parts[1]};
    }

    var people = sc.find('a.selected').length;
    var reservationDate = reservationDateDropDown.val().trim();

    /**
     * json object format
     */
    var data = {
        users_id:userId.val(),
        movies_id:moviesDropDown.val(),
        people:people,
        reservation_date:reservationDate,
        positions:JSON.stringify(seatsFormated),
        id:undefined
    }

    /**
     * if it is for edit reservation
     */
    if(reservation != null){
        data.id = reservation.id;
        url = url + '/'+data.id;
        typeRequest = 'PUT';
    }

    /**
     * If there is no date 
     */
    if(reservationDate == 'none'){
    
        alert("Debe seleccionar una fecha");
    
    /**
     * if there are no seats
     */
    }else if(seatsFormated.length == 0){
    
        alert("Debe seleccionar por lo menos una silla");
    
    }else{

        /**
         * making ajax request with the necessary data
         */
        $.ajax({
            url: url,
            type: typeRequest,
            dataType:'json',
            data:data,
            //X-CSRF-TOKEN provided by laravel
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            //http errors
            error:function(error){  

                var errors = error.responseJSON.errors;
                var allErrorMessages = [];
                var errorString ='';
                
                /**
                 * if there are errors
                 */
                if(errors != undefined){
                    for(var key in errors){
                        allErrorMessages.push(errors[key]);
                    }
                }
                
                /**
                 * showing messages 
                 */

                if(allErrorMessages.length>0){
                    errorString = allErrorMessages.join(',');
                    alert(error.responseJSON.message+": "+errorString);

                }else{

                    alert(error.responseJSON.message);
                }
            },
            success: function(result){

                /**
                 * seats info
                 */
                var seatsInfo = result.seatsInfo; 
                
                /**
                 * if there are unavailable seats
                 */
                if(seatsInfo != undefined && seatsInfo.inUseSeats.length >0 ){

                    var sillasOcupadasStr = '';

                    /**
                     * format and set seats
                     */
                    for (var i = 0; i < seatsInfo.inUseSeats.length; i++) {
                        var element = seatsInfo.inUseSeats[i];
                        var seat = element.x+"_"+element.y;
                        sc.get(seat).click();
                        
                        sillasOcupadasStr += "-"+seat+" ";
                    }

                    /**
                     * set unavailable seats
                     */
                    for (var i = 0; i < seatsInfo.unavailable.length; i++) {
                        var element = seatsInfo.unavailable[i];
                        var seat = element.x+"_"+element.y;
                        sc.get([seat]).status('unavailable');
                    }

                    /**
                     * Display message of unavailable seats
                     */
                    alert("Las sillas "+sillasOcupadasStr+"ya estan ocupadas, se ha actualizado la información de la sala");
                }else{

                    /**
                     * if all is ok just redirect
                     */
                    window.location.href = baseUrl+'/reservation';

                }
                
            }});

    }

}

/**
 * Sets the options of dates from the selected movie
 */
function setDatesDropDown(){

    /**
     * Clear the dates dropdown
     */
    reservationDateDropDown.empty();

    /**
     * Get dates by movie id
     */
    var dates = getDatesByMovieId(moviesDropDown.val());

    /**
     * Adding default option
     */
    reservationDateDropDown.append(
        $('<option></option>').val('none').html('Seleccione una fecha')
    );

    /**
     * adding options
     */
    for (let i = 0; i < dates.length; i++) {
        var element = dates[i];
        reservationDateDropDown.append(
            $('<option></option>').val(element).html(element)
        );
    }
    /**
     * if there is reservation and is the same movie selected, assging the date from the reservation 
     */
    if(reservation != null){

        if(reservation.movies_id != moviesDropDown.val() ){
            reservationDateDropDown.val('none');
        
        }else{
            reservationDateDropDown.val(reservation.reservation_date);
        }
        reservationDateDropDown.trigger('change');
    }
}

/**
 * Get the dates from the movie by id from the data of movies var
 * @param {int} movieId 
 */    
function getDatesByMovieId(movieId){
    for (var i = 0; i < movies.length; i++) {
        if(movies[i].id == movieId){
            return movies[i].datesBetween; 
        }
    }
}

/**
 * Binding of save registration click event button
 */
$(document).on('click', '.save-reservation', function(){
    saveReservation();
});

/**
 * Trigger change for refreshing purposes
 */
moviesDropDown.trigger('change');