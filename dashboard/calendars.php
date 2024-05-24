<link rel="stylesheet" href="css/style.css">

<div class="container mb-5">
    <div class="row">
        <div class="col-md-12">
            <div class="content w-100">
                <div class="calendar-container">
                    <div class="calendar">
                        <div class="month-header">
                            <span class="left-button fa fa-chevron-left" id="prev"> </span>
                            <span class="month" id="label"></span>
                            <span class="right-button fa fa-chevron-right" id="next"> </span>
                        </div>
                        <table class="days-table w-100">
                            <td class="day">Sun</td>
                            <td class="day">Mon</td>
                            <td class="day">Tue</td>
                            <td class="day">Wed</td>
                            <td class="day">Thu</td>
                            <td class="day">Fri</td>
                            <td class="day">Sat</td>
                        </table>
                        <div class="frame">
                            <table class="dates-table w-100">
                                <tbody class="tbody">
                                </tbody>
                            </table>
                        </div>
                        <div class="m-5">
                            <button class="button" id="add-button" data-bs-toggle="modal"
                                data-bs-target="#eventModal">Add
                                Symptom</button>
                        </div>
                    </div>
                </div>
                <div class="legend-container">
                    <div class="legend-item past-period"><span class="dot"></span>Past/Present Period</div>
                    <div class="legend-item ovulation-period"><span class="dot"></span>Ovulation Period</div>
                    <div class="legend-item future-period"><span class="dot"></span>Future Period</div>
                </div>
                <div class="events-container">
                </div>

                <!-- <div class="dialog" id="dialog">
                    <h2 class="dialog-header"> Add New Event </h2>
                    <form class="form" id="form">
                        <div class="form-container" align="center">
                            <label class="form-label" id="valueFromMyButton" for="name">Event name</label>
                            <input class="input" type="text" id="name" maxlength="36">
                            <label class="form-label" id="valueFromMyButton" for="count">Number of people to
                                invite</label>
                            <input class="input" type="number" id="count" min="0" max="1000000" maxlength="7">
                            <input type="button" value="Cancel" class="button" id="cancel-button">
                            <input type="button" value="OK" class="button button-white" id="ok-button">
                        </div>
                    </form>
                </div> -->
            </div>
        </div>
    </div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/popper.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>