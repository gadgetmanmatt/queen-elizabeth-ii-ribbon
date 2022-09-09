jQuery(document).ready(function($) {
    $(".blackribbon-datepicker").datepicker({
      onClose: function(dateText,datePickerInstance) {
        day = datePickerInstance.selectedDay;
        month = datePickerInstance.selectedMonth;
        year = datePickerInstance.selectedYear;
        date = new Date(year, month, day);
        unixTime = Math.round(date.getTime()/1000);

        console.log($(this).attr('id'));

        $(".blackribbon-datepicker-output#"+$(this).attr('id')).val(unixTime);
      }
    });

});