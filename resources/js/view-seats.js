

var sc;
var $cart = $('#selected-seats');
var $counter = $('#counter');

reservation.positions = JSON.parse(reservation.positions);

function setSeatingGrid(){

    
  sc = $('#seat-map').seatCharts({
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

            //seat has been vacated
            return 'available';
    }   

});


	//this will handle "[cancel]" link clicks
    $(document).on('click', '.cancel-cart-item', function () {
        
        sc.get($(this).parents('li:first').data('seatId')).click();

    });


    for (var i = 0; i < reservation.positions.length; i++) {
        var element = reservation.positions[i];
        var seat = element.x+"_"+element.y;
        sc.get(seat).status('selected');
       
        $('<li>Silla # '+seat+': </li>')
        .attr('id', 'cart-item-'+seat)
        .data('seatId', seat)
        .appendTo($cart);
    }

    $counter.text(sc.find('selected').length);


}

    setSeatingGrid();

    

