(function ($) {
  "use strict";

  // Setup the calendar with the current date
  $(document).ready(function () {
    // Call the fetchEventsAndPopulateEventData function when the page loads
    fetchEventsAndPopulateEventData();

    var date = new Date();
    var today = date.getDate();
    // Set click handlers for DOM elements
    $("#next").click({ date: date }, next_month);
    $("#prev").click({ date: date }, prev_month);

    $("#add-button").click({ date: date }, new_event);

    init_calendar(date);
    var events = check_events(today, date.getMonth() + 1, date.getFullYear());
    show_events(events, date.getMonth(), today);

    // Disable the "Add Symptom" button if the selected date is greater than today
    var selectedDate = new Date(date.getFullYear(), date.getMonth(), today);
    var currentDate = new Date();
    console.log("selectedDate:", selectedDate, "currentDate:", currentDate);
    if (selectedDate > currentDate) {
      $("#add-button").prop("disabled", true);
    }
  });

  // Initialize the calendar by appending the HTML dates
  function init_calendar(date) {
    $(".tbody").empty();
    $(".events-container").empty();
    var calendar_days = $(".tbody");
    var month = date.getMonth();
    var year = date.getFullYear();
    var day_count = days_in_month(month, year);
    var row = $("<tr class='table-row'></tr>");
    var today = date.getDate();

    // Check if today's date is valid for the current month
    if (today > day_count) {
      // If today's date is greater than the number of days in the month,
      // set it to the first day of the month
      date.setDate(1);
    } else {
      // Set date to the current day to find the first day of the month
      date.setDate(today);
    }

    var first_day = date.getDay();
    // 35+firstDay is the number of date elements to be added to the dates table
    // 35 is from (7 days in a week) * (up to 5 rows of dates in a month)
    for (var i = 0; i < 35 + first_day; i++) {
      // Since some of the elements will be blank,
      // need to calculate actual date from index
      var day = i - first_day + 1;
      // If it is a sunday, make a new row
      if (i % 7 === 0) {
        calendar_days.append(row);
        row = $("<tr class='table-row'></tr>");
      }
      // if current index isn't a day in this month, make it blank
      if (i < first_day || day > day_count) {
        var curr_date = $("<td class='table-date nil'>" + "</td>");
        row.append(curr_date);
      } else {
        var curr_date = $("<td class='table-date'>" + day + "</td>");
        var events = check_events(day, month + 1, year);
        if (today === day && $(".active-date").length === 0) {
          curr_date.addClass("active-date");
          show_events(events, month, day);
        }
        // If this date has any events, style it with .event-date
        if (events.length !== 0) {
          curr_date.addClass("event-date");
        }

        // Check if the current date matches any date in the array
        var dateString = year + "-" + pad(month + 1, 2) + "-" + pad(day, 2);
        if (forecast_period_days.includes(dateString)) {
          curr_date.addClass("future-period");
        }

        if (past_period_days.includes(dateString)) {
          curr_date.addClass("past-period");
        }

        if (forecast_ovulation_days.includes(dateString)) {
          curr_date.addClass("ovulation-period");
        }

        // Set onClick handler for clicking a date
        curr_date.click({ events: events, month: month, day: day }, date_click);
        row.append(curr_date);
      }
    }
    // Append the last row and set the current year
    calendar_days.append(row);
    // Update the month label
    $(".month").text(months[month] + "-" + year);
  }

  // Helper function to pad single digits with leading zeros
  function pad(num, size) {
    var s = "000000000" + num;
    return s.substring(s.length - size);
  }

  // Initialize forecast_period_days object with an empty array
  var forecast_period_days = [];

  // Initialize past_period_days object with an empty array
  var past_period_days = [];

  // Initialize forecast_ovulation_days object with an empty array
  var forecast_ovulation_days = [];

  $(document).ready(function () {
    // Fetch the forecast period days
    $.get("period_days.php?action=FUTURE", function (forecastPeriodDays) {
      // console.log(forecastPeriodDays);
      forecast_period_days = forecastPeriodDays;
    });

    // Fetch the past period days
    $.get("period_days.php?action=PAST", function (periodDays) {
      // console.log(periodDays);
      past_period_days = periodDays;
    });

    // Fetch the future ovulation days
    $.get("period_days.php?action=OVULATION", function (ovulationDays) {
      // console.log(periodDays);
      forecast_ovulation_days = ovulationDays;
    });
    init_calendar(new Date());
  });

  // Get the number of days in a given month/year
  function days_in_month(month, year) {
    var monthStart = new Date(year, month, 1);
    var monthEnd = new Date(year, month + 1, 1);
    return (monthEnd - monthStart) / (1000 * 60 * 60 * 24);
  }

  // Event handler for when a date is clicked
  function date_click(event) {
    $(".events-container").show(250);
    $("#eventModal").hide(250);
    $(".active-date").removeClass("active-date");
    $(this).addClass("active-date");
    show_events(event.data.events, event.data.month, event.data.day);

    // console.log(event);

    // Disable the "Add Symptom" button if the selected date is greater than today
    var selectedDate = new Date(
      new Date().getFullYear(),
      event.data.month,
      event.data.day
    );
    var currentDate = new Date();
    console.log("selectedDate:", selectedDate, "currentDate:", currentDate);
    if (selectedDate > currentDate) {
      $("#add-button").prop("disabled", true);
    } else {
      $("#add-button").prop("disabled", false);
    }
  }

  // Event handler for when the month right-button is clicked
  function next_month(event) {
    $("#eventModal").hide(250);
    var date = event.data.date;
    var new_month = (date.getMonth() + 1) % 12;
    $("month").html(new_month);
    date.setMonth(new_month);
    init_calendar(date);

    // Disable the "Add Symptom" button if the selected date is greater than today
    var today = date.getDate();
    var selectedDate = new Date(date.getFullYear(), date.getMonth(), today);
    var currentDate = new Date();
    if (selectedDate > currentDate) {
      $("#add-button").prop("disabled", true);
    } else {
      $("#add-button").prop("disabled", false);
    }
  }

  // Event handler for when the month left-button is clicked
  function prev_month(event) {
    $("#eventModal").hide(250);
    var date = event.data.date;
    var new_month = (date.getMonth() + 11) % 12;
    $("month").html(new_month);
    date.setMonth(new_month);
    init_calendar(date);

    // Disable the "Add Symptom" button if the selected date is greater than today
    var today = date.getDate();
    var selectedDate = new Date(date.getFullYear(), date.getMonth(), today);
    var currentDate = new Date();
    if (selectedDate > currentDate) {
      $("#add-button").prop("disabled", true);
    } else {
      $("#add-button").prop("disabled", false);
    }
  }

  // Event handler for clicking the new event button
  function new_event(event) {
    // if a date isn't selected then do nothing
    if ($(".active-date").length === 0) return;
    // remove red error input on click
    $("input").click(function () {
      $(this).removeClass("error-input");
    });
    // empty inputs and hide events
    $("#eventModal input[type=text]").val("");
    $(".events-container").hide(250);
    $("#eventModal").show(250);
    // Event handler for cancel button
    $("#cancel-button").click(function () {
      $("#title").removeClass("error-input");
      $("#description").removeClass("error-input");
      $("#eventModal").hide(250);
      $(".events-container").show(250);
    });
    // Event handler for ok button
    $("#ok-button")
      .unbind()
      .click({ date: event.data.date }, function (event) {
        // Prevent default form submission behavior
        event.preventDefault();
        // console.log("hello 2");
        var date = event.data.date;
        var title = $("#title").val().trim();
        var description = $("#description").val().trim();
        var flow = document.querySelector('input[name="flow"]:checked').value;
        // Retrieve checked symptoms
        var checkedSymptoms = [];
        document
          .querySelectorAll('input[type="checkbox"]:checked')
          .forEach(function (checkbox) {
            checkedSymptoms.push(checkbox.nextElementSibling.textContent);
          });
        var temperature = parseFloat($("#temperature").val().trim());
        var day = parseInt($(".active-date").html());
        // Basic form validation
        if (title.length === 0) {
          $("#title").addClass("error-input");
        } else if (description.length === 0) {
          $("#description").addClass("error-input");
        } else {
          $("#eventModal").hide(250);
          new_event_json(
            title,
            description,
            flow,
            checkedSymptoms,
            temperature,
            date,
            day
          );
          date.setDate(day);
          init_calendar(date);
        }
      });
  }

  //new code start
  function new_event_json(
    title,
    description,
    flow,
    symptoms,
    temperature,
    date,
    day
  ) {
    var event = {
      title: title,
      description: description,
      flow: flow,
      symptoms: symptoms,
      temperature: temperature,
      date: date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + day,
    };

    // Make an AJAX request to insert the event data into the database
    $.ajax({
      url: "insert_event.php", // URL of the PHP script that handles insertion
      method: "POST",
      data: event, // Send the event data to the PHP script
      success: function (response) {
        // console.log(response);
        location.reload();
        $.get("period_days.php?action=FUTURE", function (forecastPeriodDays) {
          // console.log(forecastPeriodDays);
          forecast_period_days = forecastPeriodDays;
        });

        // Fetch the past period days
        $.get("period_days.php?action=PAST", function (periodDays) {
          // console.log(periodDays);
          past_period_days = periodDays;
        });

        // Fetch the future ovulation days
        $.get("period_days.php?action=OVULATION", function (periodDays) {
          // console.log(periodDays);
          past_period_days = periodDays;
        });

        init_calendar(new Date());
      },
      error: function (xhr, status, error) {
        // Handle errors
        console.error(xhr.responseText);
        // location.reload();
        fetchEventsAndPopulateEventData();
      },
    });
  }

  // Initialize event_data object with an empty array of events
  var event_data = {
    events: [],
  };

  // Function to fetch events from the server and populate event_data
  function fetchEventsAndPopulateEventData() {
    $.ajax({
      url: "fetch_events.php",
      method: "GET",
      success: function (response) {
        const mappedEvents = response.map((event) => {
          return {
            title: event.title,
            description: event.description,
            flow: event.flow,
            symptoms: event.symptoms,
            temperature: event.temperature,
            year: parseInt(event.date.substring(0, 4)),
            month: parseInt(event.date.substring(5, 7)),
            day: parseInt(event.date.substring(8, 10)),
          };
        });

        // Assign mapped events to event_data.events
        event_data.events = mappedEvents;
        init_calendar(new Date());
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
      },
    });
  }

  const months = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ];

  // Display all events of the selected date in card views
  function show_events(events, month, day) {
    // Clear the dates container
    $(".events-container").empty();
    $(".events-container").show(250);
    //  console.log(event_data["events"]);
    // If there are no events for this date, notify the user
    if (events.length === 0) {
      var event_card = $("<div class='event-card'></div>");
      var event_name = $(
        "<div class='event-name'>There is no symptoms recorded in " +
          months[month] +
          " " +
          day +
          ".</div>"
      );
      $(event_card).css({ "border-left": "10px solid #FF1744" });
      $(event_card).append(event_name);
      $(".events-container").append(event_card);
    } else {
      // Go through and add each event as a card to the events container
      for (var i = 0; i < events.length; i++) {
        var event_card = $("<div class='event-card'></div>");
        var event_name = $(
          "<div class='event-name'> <b>Title:</b> " +
            events[i]["title"] +
            "</div>"
        );

        var event_details = $("<div class='event-details'></div>");

        // Add description
        var event_description = $(
          "<div class='event-detail'>Description: " +
            events[i]["description"] +
            "</div>"
        );
        event_details.append(event_description);

        // Add flow
        var event_flow = $(
          "<div class='event-detail'>Flow: " +
            events[i]["flow"] +
            " <i class='fas fa-tint'></i></div>"
        );
        event_details.append(event_flow);

        // Add symptoms
        var event_symptoms = $(
          "<div class='event-detail'>Symptoms: " +
            events[i]["symptoms"] +
            "</div>"
        );
        event_details.append(event_symptoms);

        // Add temperature
        var event_temperature = $(
          "<div class='event-detail'>Temperature: " +
            events[i]["temperature"] +
            "°F <i class='fas fa-thermometer-half'></i></div>"
        );
        event_details.append(event_temperature);

        if (events[i]["cancelled"] === true) {
          $(event_card).css({
            "border-left": "10px solid #FF1744",
          });
          event_details = $("<div class='event-cancelled'>Cancelled</div>");
        }

        $(event_card).append(event_name).append(event_details);
        $(".events-container").append(event_card);
      }
    }
  }

  // Checks if a specific date has any events
  function check_events(day, month, year) {
    var events = [];
    for (var i = 0; i < event_data["events"].length; i++) {
      var event = event_data["events"][i];
      if (
        event["day"] === day &&
        event["month"] === month &&
        event["year"] === year
      ) {
        events.push(event);
      }
    }
    return events;
  }
})(jQuery);
