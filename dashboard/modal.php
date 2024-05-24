<!-- The info Modal -->

<style>
    .modal {
        margin: 50px 0 0 25px;
        align-items: center;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .modal-dialog {
            max-width: 90%;
        }
    }

    @media (max-width: 576px) {
        .modal-dialog {
            max-width: 80%;
        }
    }
</style>

<div class="modal" id="myModal">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">

            <?php
            $lengthOfCycle = getAverageLengthOfCycle($cycles);
            $currentDay = getDayOfCycle($cycles);
            $ovulationStatus = getOvulationStatus($cycles);
            $pregnancyChance = getPregnancyChance($cycles);
            $phase = getPhase($cycles);
            ?>


            <!-- Modal body -->
            <div class="modal-body">
                <h3>Days <?php echo (($currentDay == 0) ? '' : "$currentDay/$lengthOfCycle"); ?></h3>
                <ul>
                    <li style='font-size: 16px; margin-bottom: 20px;'>
                        <span style='color:#FB5988; font-weight: bold;'><?php echo $phase['title']; ?></span>
                        <span> is the current phase of cycle</span>
                    </li>
                    <li style='font-size: 16px; margin-bottom: 20px;'>
                        <span>Ovulation</span>
                        <span style='color: #FB5988; font-weight: bold;'>
                            <?php echo $ovulationStatus; ?></span>
                    </li>
                    <li style='font-size: 16px;  margin-bottom: 20px;'>
                        <span style='color:#FB5988; font-weight: bold;'><?php echo $pregnancyChance; ?></span>
                        <span> chance of getting pregnant</span>
                    </li>
                </ul>
                <h3>Frequent symptoms</h3>
                <ul>
                    <?php foreach ($phase['symptoms'] as $item) { ?>
                        <li style='font-size: 16px;  margin-bottom: 20px;'><?php echo $item; ?></li>
                    <?php } ?>
                </ul>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>


<!-- Mark success modal -->
<div class="modal " id="successModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h4> Marked today as your <span style='color:#FB5988; font-weight: bold;'>1st period day </span> of
                    cycle. </h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="pageRefresh()">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function pageRefresh() {
        $('#successModal').hide(250);
        // fetchEventsAndPopulateEventData();
        location.reload();
    }
</script>

<!-- Add Symptom Modal -->
<div class="modal" id="eventModal">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Symptom</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- <form class="form" id="form" action="calendars.php" method="POST"> -->
            <form class="form" id="form">
                <div class="modal-body" style="max-height: 300px; overflow-y: auto;">
                    <div class="form-group">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" maxlength="50" required>
                    </div>
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Flow</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flow" id="light" value="light">
                            <label class="form-check-label" for="light">Light</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flow" id="medium" value="medium">
                            <label class="form-check-label" for="medium">Medium</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flow" id="heavy" value="heavy">
                            <label class="form-check-label" for="heavy">Heavy</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flow" id="disaster" value="disaster">
                            <label class="form-check-label" for="disaster">Disaster</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="flow" id="none" value="none">
                            <label class="form-check-label" for="none">None</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Symptoms</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="headache">
                            <label class="form-check-label" for="headache">Headache</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="low-back-pain">
                            <label class="form-check-label" for="low-back-pain">Low Back Pain</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="tender-breasts">
                            <label class="form-check-label" for="tender-breasts">Tender Breasts</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="abdominal-cramps">
                            <label class="form-check-label" for="abdominal-cramps">Abdominal Cramps</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="spotting">
                            <label class="form-check-label" for="spotting">Spotting</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="temperature" class="form-label">Temperature (°F)</label>
                        <input type="number" class="form-control" id="temperature" min="0" max="100" step="0.1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" value="Cancel" data-bs-dismiss="modal"
                        id="cancel-button">Cancel</button>
                    <button type="submit" value="OK" class="btn btn-primary" id="ok-button">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Welcome Modal -->
<div class="modal blur-background" id="welcomeModal">
    <div class="modal-dialog display-content">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Welcome to Period Calculator</h5>
            </div>
            <form class="form" id="form" action="update_cycles.php?action=INSERT" method="POST">
                <div class="modal-body" style="max-height: 300px; overflow-y: auto;">
                    <div class="form-group">
                        <label for="title" class="form-label">Date of your last period?
                            <span style="color:red;">*</span> </label>
                        <input type="date" class="form-control" id="last-period-start" name="last-period-start" max=""
                            required>
                    </div>
                    <div class="form-group">
                        <label for="description" class="form-label">How long did it last?
                            <span style="color:red;">*</span></label>
                        <input type="number" class="form-control" id="period-duration" name="period-duration" min="1"
                            max="10" placeholder="5" required></input>
                    </div>
                    <div class="form-group">
                        <label class="form-label">What is your cycle length?</label>
                        <input type="number" class="form-control" id="cycle-length" name="cycle-length" min="14"
                            max="60" placeholder="28">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" value="submit" class="btn btn-primary" id="welcome-button">Continue</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var today = new Date();
    var minDate = new Date(today.getFullYear(), today.getMonth() - 2, 1).toISOString().split('T')[0];
    document.getElementById('last-period-start').setAttribute('min', minDate);
    document.getElementById('last-period-start').setAttribute('max', new Date().toISOString().split('T')[0]);

</script>