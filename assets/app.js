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
require('bootstrap');
import 'bootstrap';


// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

$(document).ready(function() {
    console.log('aaaa');

    if($('.search-flight').length) {
        console.log('found');
        var url = $('#flight-from').parent('.search-flight').data('fetch-airport-url');

        $('#flight-from').select2({
            width: '300px',
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

        // $('.search-flight').on('submit', function (e) {
        //     console.log('submit');
        //     e.preventDefault();
        //     var airportFrom = $('#flight-from').val();
        //     var airportTo = $('#flight-to').val();
        //     var _date = $('#datepicker').val();
        //     console.log(airportFrom, airportTo, _date);
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

    const forms = $('.needs-validation');
    console.log(forms);

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
});
