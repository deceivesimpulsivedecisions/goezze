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

        $('.search-flight').on('submit', function (e) {
            console.log('submit');
            e.preventDefault();
            var airportFrom = $('#flight-from').val();
            var airportTo = $('#flight-to').val();
            var _date = $('#datepicker').val();
            console.log(airportFrom, airportTo, _date);
        });
    }
});
