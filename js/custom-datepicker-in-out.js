var today = moment();
var tomorrow = moment().add(1, 'days');

$('#date-picker').daterangepicker({
    showISOWeekNumbers: true,
    autoUpdateInput: false,
    autoApply: true,
    timePicker: false,
    locale: {
        format: "MMM DD, YYYY",
        separator: " - ",
        firstDay: 1
    },
    startDate: today,
    endDate: tomorrow,
    minDate: today,
    opens: "right"
}, function (start, end) {

    // Show start and end date
    $('#date-picker').val(
        start.format("MMM DD, YYYY") + ' - ' + end.format("MMM DD, YYYY")
    );
});

// Set default value on page load
$('#date-picker').val(
    today.format("MMM DD, YYYY") + ' - ' + tomorrow.format("MMM DD, YYYY")
);
