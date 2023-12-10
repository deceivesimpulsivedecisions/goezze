require('./styles/app.scss');
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

const $ = require('jquery');
import 'select2';
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
window.bootstrap = require('bootstrap/dist/js/bootstrap.bundle.js');
import 'bootstrap';


// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

$(document).ready(function() {
    console.log('aaaa');



    var body= $('body');

    if($('.search-flight').length) {
        console.log('found');
        var url = $('#flight-from').parent('.search-flight').data('fetch-airport-url');

        $('#flight-from').select2({
            width: '300px',
            placeholder: 'From',
            minimumInputLength: 1, // Minimum number of characters required to start searching
            ajax: {
                url: url, // The URL to your PHP script
                dataType: 'json',  // Expected data type (json in this case)
                delay: 20,        // Delay in milliseconds before sending the request
                processResults: function (data) {
                    console.log('Request sent');
                    // Process the data returned from the server
                    return {
                        results: data
                    };
                }
            }
        });

        $('#flight-to').select2({
            width: '300px',
            placeholder: 'To',
            minimumInputLength: 1, // Minimum number of characters required to start searching
            ajax: {
                url: url, // The URL to your PHP script
                dataType: 'json',  // Expected data type (json in this case)
                delay: 20,        // Delay in milliseconds before sending the request
                processResults: function (data) {
                    console.log('Request sent');
                    // Process the data returned from the server
                    return {
                        results: data
                    };
                }
            }
        });

        var selectedOption = $("input[name='tripStatus']:checked").val();
        console.log("Selected option on load: " + selectedOption);

        // Attach a change event handler to the radio buttons
        $("input[name='tripStatus']").change(function(){
            // Check which radio button is selected on change
            selectedOption = $("input[name='tripStatus']:checked").val();
            console.log("Selected option on change: " + selectedOption);
        });

        // $('.search-flight').on('submit', function (e) {
        //     console.log('submit');
        //     e.preventDefault();
        //     var airportFrom = $('#flight-from').val();
        //     var airportTo = $('#flight-to').val();
        //     var _date = $('#datepicker').val();
        //     console.log(airportFrom, airportTo, _date);
        // });

        // $('.example-popover').popover({
        //     container: 'body'
        // });
    }

    if($('.flight-list-container').length){
        console.log("found it");
        $('.flight-list-container').find('.loader').removeClass('d-none');
        var urlParams = new URLSearchParams(window.location.search);
        var searchUrl = $('.flight-list-container').data('url') + '?' + urlParams

        $.ajax({
            url: searchUrl,
            type: 'GET',
            success: function(response) {
                // On success, display the response
                $('.flight-list-container').find('.loader').addClass('d-none');
                $('#result').html(response);
            },
            error: function(xhr, status, error) {
                // On error, display the error details
                $('#result').text('Error: ' + error);
            }
        });

        $('.flight-list-wrapper').find('.fare-rule').on('click', function() {
            var $clickedElement = $(this); // Store the clicked element

            searchUrl = $clickedElement.data('url');
            var updatedContent = "<div>New Content</div>";
            $.ajax({
                url: searchUrl,
                type: 'POST',
                data: {
                    token: $clickedElement.data('token'),
                },
                success: function(response) {
                    console.log(response, $clickedElement, $clickedElement.data('bs-content'));
                    $clickedElement.data('bs-content', updatedContent);

                },
                error: function(xhr, status, error) {
                    // On error, display the error details
                    $('#result').text('Error: ' + error);
                }
            });
        });
    }
    const baggages = $('.baggage-wrapper');
    const meals = $('.meal-wrapper');

    meals.on('click', function () {

        var passengerIndex = $(this).data('passenger-index');
        $(this).addClass('selected-meal');
        $(this).parents('.accordion-body').find('.select').removeClass('d-none');
        $(this).parents('.accordion-body').find('.selected').addClass('d-none');
        $(this).parents('.accordion-body').find('.selected-meal').css('background-color', '');
        const mealId = $(this).data('meal-id');

        $(this).parents('.accordion-body').children('.add-meal').val(mealId);

        $(this).find('.select').addClass('d-none');
        $(this).find('.selected').removeClass('d-none');
        $(this).css('background-color', '#f5b9a0');

        var fareSummary = body.find('.fare-summary');
        var extraServices = fareSummary.find('.extra-services');
        var mealServices = extraServices.find('.meal-service');
        var grandTotal = fareSummary.find('.total-amount');

        mealServices.find('.meal-passenger-'+passengerIndex).removeClass('d-none');
        var mealName =  $(this).find('.meal-name').text();
        var mealPrice =  parseInt($(this).find('.meal-price').text());
        mealServices.find('.meal-name'+passengerIndex).text(mealName);
        mealServices.find('.meal-price'+passengerIndex).text(mealPrice);

        var basePrice = parseInt(fareSummary.find('.base-price').text());
        var taxes = parseInt(fareSummary.find('.taxes').text());
        var convienceFee = parseInt(fareSummary.find('.convenience').text());

        var baggagePrice =  parseInt(fareSummary.find('.price').text() === '' ? 0 : fareSummary.find('.price').text());

        extraServices.removeClass('d-none');
        mealServices.removeClass('d-none');
        let sumMealPrices = 0;

        $('.fare-meal-price').each(function () {
            sumMealPrices += parseFloat($(this).text()) || 0;
        });

        let sumBaggagePrices = 0;

        $('.fare-baggage-price').each(function () {
            sumBaggagePrices += parseFloat($(this).text()) || 0;
        });

        let sumSeatPrices = 0;

        $('.fare-seat-price').each(function () {
            sumSeatPrices += parseFloat($(this).text()) || 0;
        });

        grandTotal.text(basePrice + taxes + convienceFee + sumBaggagePrices + sumMealPrices + sumSeatPrices);
    });


    baggages.on('click', function () {
        var passengerIndex = $(this).data('passenger-index');
        $(this).addClass('selected-baggage');
        $(this).parents('.baggage-row').find('.select').removeClass('d-none');
        $(this).parents('.baggage-row').find('.selected').addClass('d-none');
        $(this).parents('.baggage-row').find('.selected-baggage').css('background-color', '');
        const baggageId = $(this).data('baggage-id');

        $(this).parents('.baggage-row').find('.add-baggage').val(baggageId);

        $(this).find('.select').addClass('d-none');
        $(this).find('.selected').removeClass('d-none');
        $(this).css('background-color', '#f5b9a0');

        var extraServices = body.find('.extra-services');
        var baggageServices = extraServices.find('.baggage-service');
        var fareSummary = body.find('.fare-summary');
        var grandTotal = fareSummary.find('.total-amount');


        var weight =  $(this).find('.baggage-weight').text();
        var basePrice = parseInt(fareSummary.find('.base-price').text());
        var taxes = parseInt(fareSummary.find('.taxes').text());
        var convienceFee = parseInt(fareSummary.find('.convenience').text());
        var baggagePrice =  parseInt($(this).find('.baggage-price').text());
        var mealPrice =  parseInt(fareSummary.find('.meal-price').text() === '' ? 0 : fareSummary.find('.meal-price').text());

        console.log(extraServices, baggageServices, weight, baggagePrice, basePrice, taxes, convienceFee, mealPrice);
        extraServices.removeClass('d-none');
        baggageServices.removeClass('d-none');
        baggageServices.find('.baggage-passenger-'+passengerIndex).removeClass('d-none');

        baggageServices.find('.extra-baggage'+passengerIndex).text(weight);
        baggageServices.find('.baggage-price'+passengerIndex).text(baggagePrice);
        let sumMealPrices = 0;

        $('.fare-meal-price').each(function () {
            sumMealPrices += parseFloat($(this).text()) || 0;
        });

        let sumBaggagePrices = 0;

        $('.fare-baggage-price').each(function () {
            sumBaggagePrices += parseFloat($(this).text()) || 0;
        });

        let sumSeatPrices = 0;

        $('.fare-seat-price').each(function () {
            sumSeatPrices += parseFloat($(this).text()) || 0;
        });
        grandTotal.text(basePrice + taxes + convienceFee + sumBaggagePrices + sumMealPrices + sumSeatPrices);
    });

    const forms = $('.needs-validation');

    // Prevent form submission if there are invalid fields
    forms.on('submit', function(event) {
        // Check form validity using jQuery
        if (!this.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            event.preventDefault();
            console.log($(this).serialize());
            var url = $(this).data('url');

            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // console.log(response, $clickedElement, $clickedElement.data('bs-content'));
                    // $clickedElement.data('bs-content', updatedContent);

                },
                error: function(xhr, status, error) {
                    // On error, display the error details
                    // $('#result').text('Error: ' + error);
                }
            });
        }


        // Add 'was-validated' class to the form
        $(this).addClass('was-validated');
    });

    if($('.plane').length){
        $("input:checkbox").on('click', function() {
            // in the handler, 'this' refers to the box clicked on
            var $box = $(this);
            var passengerIndex = $box.data('passenger');
            var $plane = $box.closest('.plane'+passengerIndex);
            var planes = body.find('.plane');
            if ($box.is(":checked")) {
                // the name of the box is retrieved using the .attr() method
                // as it is assumed and expected to be immutable
                var group = "input:checkbox[name='" + $box.attr("name") + "']";
                console.log('abcd', $(group), $plane);
                // the checked state of the group/box on the other hand will change
                // and the current value is retrieved using .prop() method
                $(group).prop("checked", false);
                $box.prop("checked", true);
                var price = parseInt($box.data('price'))
                var seat = $box.data('seat');
                var passengerIndex = parseInt($box.data('passenger'))
                var selectedSeat = body.find('.selected-seat-'+passengerIndex);
                selectedSeat.find('.seat').text(seat);
                selectedSeat.find('.amount').text(price);
                let sumMealPrices = 0;

                $('.fare-meal-price').each(function () {
                    sumMealPrices += parseFloat($(this).text()) || 0;
                });

                let sumBaggagePrices = 0;

                $('.fare-baggage-price').each(function () {
                    sumBaggagePrices += parseFloat($(this).text()) || 0;
                });
                var fareSummary = body.find('.fare-summary');
                var basePrice = parseInt(fareSummary.find('.base-price').text());
                var taxes = parseInt(fareSummary.find('.taxes').text());
                var convienceFee = parseInt(fareSummary.find('.convenience').text());

                var grandTotal = fareSummary.find('.total-amount');

                var extraServices = body.find('.extra-services');
                var seatServices = extraServices.find('.seat-service');
                extraServices.removeClass('d-none');
                seatServices.removeClass('d-none');
                seatServices.find('.seat-passenger-'+passengerIndex).removeClass('d-none');

                seatServices.find('.seat-name'+passengerIndex).text('('+seat+')');
                seatServices.find('.seat-price'+passengerIndex).text(price);

                let sumSeatPrices = 0;

                $('.fare-seat-price').each(function () {
                    sumSeatPrices += parseFloat($(this).text()) || 0;
                });

                grandTotal.text(basePrice + taxes + convienceFee + sumBaggagePrices + sumMealPrices + sumSeatPrices);
            } else {
                $box.prop("checked", false);
            }
        });
    }

    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl)
    })
});
