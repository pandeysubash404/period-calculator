document.addEventListener('DOMContentLoaded', function () {
    // Function to get the month name
    function getMonthName(monthIndex) {
        var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        return monthNames[monthIndex];
    }

    // Function to get day names
    function getDayNames(dayIndex) {
        var dayName = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        return dayName[dayIndex];
    }

    // Function to populate calendar for a given month
    function populateCalendar(monthIndex, tbodyId, periodStartDay, periodLength, lastPeriodDate, cycleLength) {
        var tbody = document.getElementById(tbodyId);
        tbody.innerHTML = ''; // Clear previous content

        // Get the current year and month
        var currentDate = new Date();
        var currentYear = currentDate.getFullYear();
        var currentMonth = currentDate.getMonth();

        // Calculate the year and month for the provided index
        var targetDate = new Date(currentYear, currentMonth + monthIndex, 1);
        var targetYear = targetDate.getFullYear();
        var targetMonth = targetDate.getMonth();

        // Create a new date object for the first day of the month
        var firstDay = new Date(targetYear, targetMonth, 1);

        // Get the index of the first day of the month (0: Sunday, 1: Monday, ..., 6: Saturday)
        var firstDayIndex = firstDay.getDay();


        // Create rows and columns for the day names
        var dayNamesRow = '<tr>';
        for (var i = 0; i < 7; i++) {
            dayNamesRow += '<th>' + getDayNames(i) + '</th>';
        }
        dayNamesRow += '</tr>';
        tbody.innerHTML = dayNamesRow;

        // Get the number of days in the month
        var daysInMonth = new Date(targetYear, targetMonth + 1, 0).getDate();

        // Create rows and columns for the calendar
        var html = '';
        var dayCounter = 1;

        for (var i = 0; i < 6; i++) { // 6 rows for the calendar
            html += '<tr>';
            for (var j = 0; j < 7; j++) { // 7 columns for the days of the week
                if (i === 0 && j < firstDayIndex) {
                    html += '<td></td>'; // Empty cells before the first day of the month
                } else if (dayCounter > daysInMonth) {
                    html += '<td></td>'; // Empty cells after the last day of the month
                } else {
                    // Check if the current day should be highlighted
                    var currentDate = new Date(targetYear, targetMonth, dayCounter);
                    var isHighlighted = false;

                    if ((monthIndex === 0) && periodStartDay && dayCounter >= periodStartDay && dayCounter < periodStartDay + periodLength) {
                        isHighlighted = true;
                    }

                    if ((monthIndex === 1) && lastPeriodDate && cycleLength) {
                        var nextPeriodStartDay = new Date(lastPeriodDate);
                        nextPeriodStartDay.setDate(lastPeriodDate.getDate() + cycleLength);
                        var nextPeriodEndDay = new Date(nextPeriodStartDay);
                        nextPeriodEndDay.setDate(nextPeriodStartDay.getDate() + periodLength);
                        if (currentDate >= nextPeriodStartDay && currentDate <= nextPeriodEndDay) {
                            isHighlighted = true;
                        }
                    }

                    if (monthIndex === 2 && lastPeriodDate && cycleLength) {
                        var nextPeriodStartDay = new Date(lastPeriodDate);
                        nextPeriodStartDay.setDate(nextPeriodStartDay.getDate() + cycleLength * 2);
                        var nextPeriodEndDay = new Date(nextPeriodStartDay);
                        nextPeriodEndDay.setDate(nextPeriodStartDay.getDate() + periodLength);
                        if (currentDate >= nextPeriodStartDay && currentDate <= nextPeriodEndDay) {
                            isHighlighted = true;
                        }
                    }

                    // Add the "highlight" class if the day is highlighted
                    if (isHighlighted) {
                        html += '<td class="highlight">' + dayCounter + '</td>';
                    } else {
                        html += '<td>' + dayCounter + '</td>';
                    }

                    dayCounter++;
                }
            }
            html += '</tr>';
        }

        tbody.innerHTML += html; // Append the calendar content
        var note = '<p>Note: This period calculation is only an estimation. Your unique menstrual cycle may vary from these results</p>';
        document.getElementById('note').innerHTML = note;
        // Update month label
        document.getElementById('label' + (monthIndex + 1)).textContent = getMonthName(targetMonth);
    }

    // Attach click event listener to the Calculate Your Cycle button
    document.getElementById('calculateCycleBtn').addEventListener('click', function () {

        var lastPeriodDate = new Date(document.getElementById('lastPeriodDate').value);
        var cycleLength = parseInt(document.getElementById('cycleLengthValue').value);
        var periodLength = parseInt(document.getElementById('durationValue').value);

        // Calculate period start date and end date
        var periodStartDate = new Date(lastPeriodDate);
        periodStartDate.setDate(periodStartDate.getDate());

        var periodEndDate = new Date(lastPeriodDate);
        periodEndDate.setDate(periodStartDate.getDate() + cycleLength);

        // Display the next 3 months' calendars with highlights 
        populateCalendar(0, 'tbody1', periodStartDate.getDate(), periodLength, lastPeriodDate, null);

        // Current month
        populateCalendar(1, 'tbody2', periodEndDate.getDate(), periodLength, lastPeriodDate, cycleLength);

        // Next month 
        populateCalendar(2, 'tbody3', periodEndDate.getDate(), periodLength, lastPeriodDate, cycleLength);

        console.log("Display");
        document.getElementById('calendarSection').style.display = 'block';

    });

    // Increment and decrement duration value 
    var durationValue = document.getElementById('durationValue');
    document.getElementById('incrementDuration').addEventListener('click', function () {
        if ((parseInt(durationValue.value) >= 0) && (parseInt(durationValue.value) <= 10)) {
            durationValue.value = parseInt(durationValue.value) + 1;
        } else {
            durationValue.value = 0;
        }
    });

    document.getElementById('decrementDuration').addEventListener('click', function () {
        if (parseInt(durationValue.value) > 0) {
            durationValue.value = parseInt(durationValue.value) - 1;
        }
    });

    // Increment and decrement cycle length value 
    var cycleLengthValue = document.getElementById('cycleLengthValue');
    document.getElementById('incrementCycle').addEventListener('click', function () {
        cycleLengthValue.value = parseInt(cycleLengthValue.value) + 1;
    });
    document.getElementById('decrementCycle').addEventListener('click', function () {
        if (parseInt(cycleLengthValue.value) > 0) {
            cycleLengthValue.value = parseInt(cycleLengthValue.value) - 1;
        }
    });

    // Set the max date for the lastPeriodDate input to today's date 
    var today = new Date().toISOString().split('T')[0];
    document.getElementById('lastPeriodDate').value(new Date());
    document.getElementById('lastPeriodDate').setAttribute('max', today);
});