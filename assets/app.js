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
// window.bootstrap = require('bootstrap/dist/js/bootstrap.bundle.js');
require('bootstrap');
import 'bootstrap';
// import Swiper JS
import Swiper from 'swiper';
// import Swiper styles
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';


// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

$(document).ready(function() {

    var swiper = new Swiper(".mySwiper", {
        grabCursor: true,
        centeredSlides: true,
        loop: true,
        spaceBetween: 20,
        coverflowEffect: {
            rotate: 0,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints:{
            576: {
                slidesPerView: 1, // Show 1 slide for screens larger than or equal to 576px
            },
            768: {
                slidesPerView: 2, // Show 2 slides for screens larger than or equal to 768px
            },
            992: {
                slidesPerView: 3, // Show 3 slides for screens larger than or equal to 992px
            }

        }
    });



    var body= $('body');
    $('#destinations').select2({
        width: '200px',
        placeholder: 'Destination',
    });
    if($('.search-flight').length) {
        console.log('found');
        var url = $('#flight-from').parents('.search-flight').data('fetch-airport-url');

        $('#flight-from').select2({
            width: '100%',
            placeholder: 'From',
            minimumInputLength: 1, // Minimum number of characters required to start searching
            ajax: {
                url: url, // The URL to your PHP script
                dataType: 'json',  // Expected data type (json in this case)
                delay: 20,        // Delay in milliseconds before sending the request
                processResults: function (data) {
                    console.log('Request sent',data);
                    // Process the data returned from the server
                    return {
                        results: data
                    };
                }
            }
        });

        $('#flight-to').select2({
            width: '100%',
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
        $("input[name='tripStatus']").on('change', function(){
            // Check which radio button is selected on change
            selectedOption = $("input[name='tripStatus']:checked").val();
            console.log("Selected option on change: " + selectedOption);

            if(selectedOption === 'round-trip'){
                console.log('aaaa', body.find('#to-date'));
                body.find('#to-date').prop("disabled", false);
            } else {
                body.find('#to-date').prop("disabled", true);
            }
        })

        $('select[name="adult"]').on('change', function() {
            // Get the selected value of the "Adult" select
            var selectedAdults = parseInt($(this).val());

            // Calculate the maximum allowed children and infants based on the selected adults
            var maxChildren = Math.max(0, 9 - selectedAdults);
            var maxInfants = Math.min(selectedAdults, 5);

            // Update the options of the "Children" select based on the calculated maximum
            updateOptions('children', maxChildren);

            // Update the options of the "Infants" select based on the calculated maximum
            updateOptions('infant', maxInfants);
        });


        $('select[name="adult"]').on('change', function() {
            // Get the selected value of the "Adult" select
            var selectedAdults = parseInt($(this).val());

            // Calculate the maximum allowed children and infants based on the selected adults
            var maxChildren = Math.max(0, 9 - selectedAdults);

            // Update the options of the "Children" select based on the calculated maximum
            updateOptions('children', maxChildren);

            // Update the options of the "Infants" select based on the calculated maximum
            updateOptions('infant', selectedAdults);
        });

        function updateOptions(selectName, maxOptions) {
            var $select = $('select[name="' + selectName + '"]');
            var selectedValue = $select.val(); // Store the selected value

            $select.empty(); // Clear existing options

            // Generate new options based on the calculated maximum
            for (var i = 0; i <= maxOptions; i++) {
                $select.append('<option value="' + i + '">' + i + '</option>');
            }

            // Restore the selected value if it's within the new range
            if (selectedValue <= maxOptions) {
                $select.val(selectedValue);
            } else {
                // If the selected value is now out of range, select the maximum available value
                $select.val(maxOptions);
            }
        }

        $('select[name="adult"], select[name="children"]').on('change', function() {
            // Get the selected values of the "Adult" and "Children" selects
            var adultCount = parseInt($('select[name="adult"]').val()) || 0;
            var childrenCount = parseInt($('select[name="children"]').val()) || 0;

            // Calculate the total passenger count
            var totalPassengers = adultCount + childrenCount;

            // Update the passenger count in the accordion header
            $('.passenger-count').text(totalPassengers);
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

    if($('.all-packages-wrapper').length){
        var urlParams = new URLSearchParams(window.location.search);
        var searchUrl = $('.all-packages-wrapper').data('url') + '?' + urlParams;

        function getQueryParams() {
            var queryParams = {};
            var queryString = window.location.search.substring(1);
            var pairs = queryString.split("&");

            for (var i = 0; i < pairs.length; i++) {
                var pair = pairs[i].split("=");
                var key = decodeURIComponent(pair[0]);
                var value = decodeURIComponent(pair[1]);

                // If the parameter has multiple values, create an array
                if (queryParams[key]) {
                    if (Array.isArray(queryParams[key])) {
                        queryParams[key].push(value);
                    } else {
                        queryParams[key] = [queryParams[key], value];
                    }
                } else {
                    queryParams[key] = value;
                }
            }

            return queryParams;
        }
        function checkCheckboxesFromParams() {
            var queryParams = getQueryParams();
            console.log(queryParams);
            // Check category checkboxes
            if (queryParams.category) {
                var categoryArray = queryParams.category.split(',');
                categoryArray.forEach(function(value) {
                    $('#' + value).prop('checked', true);
                });
            }

            // Check destination checkboxes
            if (queryParams.destination) {
                var destinationValues = queryParams.destination.split(',');
                destinationValues.forEach(function(value) {
                    $('#' + value).prop('checked', true);
                });
            }

            if(queryParams.search){
                $('.form-control').val(queryParams.search);
            }
        }

        // Example: Check checkboxes based on query parameters
        checkCheckboxesFromParams();
        $('.grabPromo').click(function(e){
            $('.slideDown').slideToggle();
            $('.slideDown').toggleClass('d-none');
        });

        function updateUrlParameter(key, value) {
            var url = new URL(window.location.href);
            var currentValue = url.searchParams.get(key);

            // Decode the current value
            if (currentValue) {
                currentValue = decodeURIComponent(currentValue);
            }

            // Check if the parameter already exists
            if (currentValue) {
                // Split the current value into an array
                var valuesArray = currentValue.split(',');

                // Check if the value is already in the array
                var index = valuesArray.indexOf(value);
                if (index === -1) {
                    // If not, add the new value
                    valuesArray.push(value);
                } else {
                    // If yes, remove the value (uncheck)
                    valuesArray.splice(index, 1);
                }

                // Join the array back into a comma-separated string
                value = valuesArray.join(',');
            }

            // Encode the value before setting it in the URL
            value = encodeURIComponent(value).replace(/%2C/g, ',');

            url.searchParams.set(key, value);
            window.history.replaceState({}, '', url);

            console.log(searchUrl.split('?')[0]+ '?' +window.location.search.substring(1).replace(/%2C/g, ','));
            fetchPackages();
        }

        // Function to handle checkbox click event
        function handleCheckboxClick() {
            // Get the checkbox value and id
            var checkboxValue = $(this).val();
            var checkboxId = $(this).attr('name');

            // Update URL parameters based on checkbox type (category or destination)
            if (checkboxId.startsWith('category')) {
                updateUrlParameter('category', checkboxValue);
            } else if (checkboxId.startsWith('destination')) {
                updateUrlParameter('destination', checkboxValue);
            }
        }

        function updateSearchUrlParameter(key, value) {
            var url = new URL(window.location.href);
            url.searchParams.set(key, value);
            window.history.replaceState({}, '', url);
            console.log(searchUrl.split('?')[0]+ '?' +window.location.search.substring(1).replace(/%2C/g, ','));
            fetchPackages();
        }
        function handleSearchButtonClick() {
            var searchValue = $('.form-control').val();

            // Update URL parameters based on the search value
            updateSearchUrlParameter('search', searchValue);
        }

        // Attach click event handler to checkboxes
        $('.form-check-input').on('click', handleCheckboxClick);
        $('.search-packages').on('click', handleSearchButtonClick);
        console.log($('.search-packages'))


        fetchPackages();

        function fetchPackages() {
            $.ajax({
                url: searchUrl.split('?')[0] + '?' + window.location.search.substring(1).replace(/%2C/g, ','),
                type: 'GET',
                success: function(response) {
                    // On success, display the response
                    $('.all-packages-wrapper').find('.loader').addClass('d-none');
                    $('#result').html(response);
                },
                error: function(xhr, status, error) {
                    // On error, display the error details
                    $('#result').text('Error: ' + error);
                }
            });
        }
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
    if($('.meal-wrapper').length){
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
    }


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

    if($('.package-details-wrapper').length){
        // $('#packageEnquiryForm').submit(function (e) {
        //     e.preventDefault(); // Prevent the form from submitting in the traditional way
        //
        //     var formData = $(this).serialize(); // Serialize the form data
        //
        //     $.ajax({
        //         type: 'POST',
        //         url: $(this).attr('action'), // Use the form's action attribute as the URL
        //         data: formData,
        //         success: function (data) {
        //             // Handle the success response
        //             $('.success-data').removeClass('d-none');
        //             $('.enquiry-form').addClass('d-none');
        //             // You can update the page or perform additional actions here
        //         },
        //         error: function (data) {
        //             // Handle the error response
        //             $('.error-data').removeClass('d-none');
        //             $('.enquiry-form').addClass('d-none');
        //             // You can display error messages or perform additional error handling here
        //         }
        //     });
        // });

        $('#package_enquiry_childrens, #package_enquiry_adults').on('input', function () {
            updateAmount();
        });

        // Function to update the amount based on the selected values
        function updateAmount() {

            var packagePrice = parseFloat($('#defaultPackagePrice').data('price')) || 0;
            var adults = parseInt($('#package_enquiry_adults').val()) || 0;
            var childrens = parseInt($('#package_enquiry_childrens').val()) || 0;

            var amount = packagePrice * (adults + childrens) / 100;

            $('#package_enquiry_amount').val(amount.toFixed(2));
        }
        updateAmount();
    }
});
