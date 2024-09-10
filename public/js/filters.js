/**
 * Filter to format the date
 */
angular.module('HIS')
    .filter('dateToISO', function () {
        return function (input) {
            if (!input) return input; // Handle empty or undefined input

            // Split the input date string into components
            var t = input.split(/[- :]/);
            // Ensure that we are parsing integers correctly
            var year = parseInt(t[0], 10);
            var month = parseInt(t[1], 10) - 1; // Months are zero-based in JavaScript
            var day = parseInt(t[2], 10);
            var hour = parseInt(t[3], 10);
            var minute = parseInt(t[4], 10);
            var second = parseInt(t[5], 10);

            // Create a new Date object in UTC
            var date = new Date(Date.UTC(year, month, day, hour, minute, second));
            // Return the ISO string representation of the date
            return date.toISOString();
        };
    })
    .filter('exactNumber', function () {
        return function (number) {
            var num = parseInt(number, 10);
            return num.toString();
        }
    });