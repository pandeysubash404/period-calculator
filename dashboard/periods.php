<?php
include_once ("header.php");
require_once '../db_config.php';

// Create a new database connection
$conn = new mysqli(DBHOST, DBUSER, DBPWD, DBNAME);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for editing period
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['_method'])) {
        $id = $_POST['id'];
        $start_date = $_POST['start-date'];
        $period_length = $_POST['period-length'];

        // Calculate cycle length based on next start date
        $nextStartDate = empty(trim($_POST['ajaxNextStartDates'])) ? date("Y-m-d") : $_POST['ajaxNextStartDates'];
        $cycle_length = round((strtotime($nextStartDate) - strtotime($_POST['start-date'])) / (60 * 60 * 24));

        $prevStartDate = $_POST["ajaxPrevStartDates"];
        $prev_cycle_length = round((strtotime($_POST['start-date']) - strtotime($_POST['ajaxPrevStartDates'])) / (60 * 60 * 24));

        // Calculate cycle length 
        if (($_POST['ajaxIndexs'] == 0) && ($_POST['ajaxLastRows'] == "true")) {
            $cycle_length = $_POST["cycle-length"];
        } else if ($_POST['ajaxIndexs'] == 0) {
            $cycle_length = 0;
        } else if ($_POST['ajaxLastRows'] == "true") {
            $cycle_length = round((strtotime($_POST["ajaxPrevStartDates"]) - strtotime($_POST['start-date'])) / (60 * 60 * 24));
        }

        /*
          // Output debug information
                echo "Post: <pre>";
                print_r($_POST);
                echo "</pre><br>";
                echo var_dump($_POST["ajaxLastRows"]);
                echo "</br>";
                echo "<div style='margin-top:60px;'>";
                echo "1st update<br>";
                echo "Start date: $start_date, Period Length: $period_length, Cycle Lengths: $cycle_length, Row id: $id";
                if (($_POST['ajaxLastRows'] == "true")) {
                    echo "success";
                } else {
                    echo "<br>2nd update<br>";
                    echo "Prev cycle length: $prev_cycle_length, Previous start date: $prevStartDate";
                }
                echo "<br>Next start date: $nextStartDate";
                echo "</div>";
        */

        // Update the period in the database
        $update_query = "UPDATE cycles SET start_date=?, period_length=?, cycle_length=? WHERE id=?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("siii", $start_date, $period_length, $cycle_length, $id);

        if ($stmt->execute()) {
            if ($_POST['ajaxLastRows'] == "false") {
                $update_sql = "UPDATE cycles SET cycle_length = ? WHERE start_date = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("is", $prev_cycle_length, $prevStartDate);
                if ($stmt->execute()) {
                    echo "success";
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            }
            $stmt->close();
        } else {
            echo "Error updating period: " . $conn->error;
        }

    } else {
        $id = $_POST['id'];
        $nextStartDate = isset($_POST['ajaxNextStartDate']) ? $_POST['ajaxNextStartDate'] : date("Y-m-d");
        $cycle_length = round((strtotime($nextStartDate) - strtotime($_POST["ajaxPrevStartDate"])) / (60 * 60 * 24));

        $prevStartDate = $_POST["ajaxPrevStartDate"];
        /*
                echo "Post: <pre>";
                print_r($_POST);
                echo "</pre></br>";
                echo var_dump($_POST['ajaxLastRow']);
                echo "</br>";
                echo "<div style='margin-top:50px;'>";
                echo "DELETE FROM cycles WHERE id=" . $id . "</br>";
                if ($_POST['ajaxLastRow'] == "false") {
                    $cycle_length = ($_POST['ajaxIndex'] == 0) ? 0 : $cycle_length;
                    echo "UPDATE cycles SET cycle_length =" . $cycle_length . "WHERE start_date =" . $prevStartDate . "</br>";
                }
                echo ("Next Start date:" . $nextStartDate . " Cycle Length:" . $cycle_length . " Row id" . $id);
                echo (" Previous start date:" . $prevStartDate);
                echo "</div>";
        */
        $delete_query = "DELETE FROM cycles WHERE id=?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            if ($_POST['ajaxLastRow'] == "false") {
                $cycle_length = ($_POST['ajaxIndex'] == 0) ? 0 : $cycle_length;
                $update_sql = "UPDATE cycles SET cycle_length = ? WHERE start_date = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("is", $cycle_length, $prevStartDate);
                if ($stmt->execute()) {
                    echo "success";
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            }
            $stmt->close();
        } else {
            echo "Error deleting cycle: " . $conn->error;
        }
    }
}
?>

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
            margin: 60px 0 0 15px;
            max-width: 80%;
        }
    }
</style>

<body class="sb-nav-fixed">
    <!-- Top Nav Start -->
    <?php include_once ("topnav.php"); ?>
    <!-- Top Nav End -->
    <div id="layoutSidenav">
        <!-- Side Nav Start-->
        <?php include_once ("sidenav.php"); ?>
        <!-- Side Nav End-->


        <!-- Content Start -->
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid py-4">
                    <div class="m-2">
                        <button class="btn btn-info" onclick="printTable()"><i class="fas fa-print"></i> Print
                            Table</button>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Period Data Table
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table">
                                <thead>
                                    <tr>
                                        <th>Start Date</th>
                                        <th>Period Length</th>
                                        <th>Cycle Length</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $symptom_sql = "SELECT * FROM cycles WHERE user_id='$userID' ORDER BY start_date DESC";
                                    $result = $conn->query($symptom_sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row['start_date'] . "</td>";
                                            echo "<td>" . $row['period_length'] . "</td>";
                                            echo "<td>" . $row['cycle_length'] . "</td>";
                                            echo "<td>
                                            <button class='btn btn-success edit-btn' data-bs-toggle='modal' data-bs-target='#periodModal' 
                                            data-id='" . $row['id'] . "' 
                                            data-start-date='" . $row['start_date'] . "' 
                                            data-cycle-length='" . $row['cycle_length'] . "'
                                            data-period-length='" . $row['period_length'] . "'
                                            >Edit</button>
                                            </td>";
                                            echo "<td>
                                            <button class='btn btn-danger delete-btn' data-bs-toggle='modal' data-bs-target='#deleteModal'
                                            data-id='" . $row['id'] . "' 
                                            >Delete</button>
                                            </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='7'>No data available</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Delete Symptom Modal -->
            <div class="modal" id="deleteModal">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Period</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form class="form" id="deleteForm" method="POST">
                            <div class="modal-body" style="max-height: 300px; overflow-y: auto;">
                                <h4> Are you sure you want to <span style='color:#FB5988; font-weight: bold;'>
                                        delete ?</span></h4>
                                <input type="hidden" id="delete-id" name="id">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" id="ajaxNextStartDate" name="ajaxNextStartDate">
                                <input type="hidden" id="ajaxPrevStartDate" name="ajaxPrevStartDate">
                                <input type="hidden" id="ajaxIndex" name="ajaxIndex">
                                <input type="hidden" id="ajaxLastRow" name="ajaxLastRow">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Symptom Modal -->
            <div class="modal" id="periodModal">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Period</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form class="form" id="editForm" method="POST">
                            <div class="modal-body" style="max-height: 300px; overflow-y: auto;">
                                <input type="hidden" id="edit-id" name="id">
                                <div class="form-group">
                                    <label for="edit-start-date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="edit-start-date" maxlength="" required
                                        name="start-date">
                                </div>
                                <div class="form-group">
                                    <label for="edit-period-length" class="form-label">Period Length</label>
                                    <input type="text" class="form-control" id="edit-period-length" name="period-length"
                                        maxlength="50" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit-cycle-length" class="form-label">Cycle Length</label>
                                    <input type="text" class="form-control" id="edit-cycle-length" name="cycle-length"
                                        maxlength="50" readonly style="background-color:#e9ecef;">
                                </div>
                            </div>
                            <input type="hidden" id="ajaxNextStartDates" name="ajaxNextStartDates">
                            <input type="hidden" id="ajaxPrevStartDates" name="ajaxPrevStartDates">
                            <input type="hidden" id="ajaxIndexs" name="ajaxIndexs">
                            <input type="hidden" id="ajaxLastRows" name="ajaxLastRows">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="container">
                <?php include_once ("function.php") ?>
                <table class="table table-striped px-10 pb-5">
                    <thead>
                        <tr>
                            <th>Particular</th>
                            <th>Days</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Average Cycle Length</td>
                            <td><?php echo getAverageLengthOfCycle($cycles); ?></td>
                        </tr>
                        <tr>
                            <td>Average Period Length</td>
                            <td><?php echo getAverageLengthOfPeriod($cycles); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <?php
            $conn->close();
            include_once ("footer.php");
            ?>
            <script src="js/jquery.min.js"></script>
            <script src="js/print.min.js"></script>
            <script>
                //print
                function printTable() {
                    // Print the table element with Print.js
                    printJS({ printable: 'datatablesSimple', type: 'html' });
                }

                var today = new Date();
                var minDate = new Date(today.getFullYear(), today.getMonth() - 2, 1).toISOString().split('T')[0];
                document.getElementById('edit-start-date').setAttribute('min', minDate);
                document.getElementById('edit-start-date').setAttribute('max', new Date().toISOString().split('T')[0]);

            </script>
            <script>
                $(document).ready(function () {
                    $('.delete-btn').click(function () {
                        var id = $(this).data('id');

                        // Determine the index of the row being edited
                        var index = $(this).closest('tr').index();
                        var lastRow = false;

                        var lastIndex = $("#datatablesSimple tbody tr").length - 1;

                        var lastRow = (index == lastIndex) ? true : false;

                        // Get the previous row's start date
                        var nextStartDate = index > 0 ? $("#datatablesSimple tbody tr:eq(" + (index - 1) + ") td:first").text() : new Date().toISOString().slice(0, 10);
                        // Get the next row's start date
                        var prevStartDate = index < ($("#datatablesSimple tbody tr").length - 1) ? $("#datatablesSimple tbody tr:eq(" + (index + 1) + ") td:first").text() : null;


                        $('#delete-id').val(id);

                        console.log("index:", index);
                        console.log("last row:", lastRow);
                        console.log("prevStartDate:", prevStartDate);
                        console.log("nextStartDate:", nextStartDate);

                        $.ajax({
                            url: 'periods.php',
                            type: 'POST',
                            data: {
                                nextStartDate: nextStartDate,
                                prevStartDate: prevStartDate,
                                index: index,
                                lastRow: lastRow,
                                ajax: true
                            },
                            success: function (response) {
                                $('#ajaxNextStartDate').val(nextStartDate);
                                $('#ajaxPrevStartDate').val(prevStartDate);
                                $('#ajaxIndex').val(index);
                                $('#ajaxLastRow').val(lastRow);
                                console.log('Value stored successfully in hidden input field.');
                            },
                            error: function (xhr, status, error) {
                                console.error('Error storing value in hidden input field.');
                            }
                        });
                    });
                    $('.edit-btn').click(function () {
                        var id = $(this).data('id');
                        var start_date = $(this).data('start-date');
                        var cycle_length = $(this).data('cycle-length');
                        var period_length = $(this).data('period-length');


                        $('#edit-id').val(id);
                        $('#edit-start-date').val(start_date);
                        $('#edit-period-length').val(period_length);
                        $('#edit-cycle-length').val(cycle_length);

                        // Determine the index of the row being edited
                        var indexs = $(this).closest('tr').index();
                        // Get the last index
                        var lastIndexs = $("#datatablesSimple tbody tr").length - 1;

                        var lastRows = false;
                        var lastRows = (indexs == lastIndexs) ? true : false;

                        // Variables to store the parsed dates
                        var nextStartDates;
                        var prevStartDates;
                        var setNextStartDates;
                        var setPrevStartDates;


                        if ((indexs <= 0) && (lastRows)) {
                            // Get the previous row's start date
                            nextStartDates = new Date().toISOString().slice(0, 10);
                            setNextStartDates = nextStartDates;

                            $('#edit-start-date').attr('max', setNextStartDates); // Y-m-d
                        } else if (indexs <= 0) {
                            // Get the previous row's start date
                            nextStartDates = new Date().toISOString().slice(0, 10);
                            setNextStartDates = nextStartDates;
                            // Get the next row's start date
                            prevStartDates = indexs < ($("#datatablesSimple tbody tr").length - 1) ? $("#datatablesSimple tbody tr:eq(" + (indexs + 1) + ") td:first").text() : null;

                            // Parse dates to adjust by 14 days
                            setPrevStartDates = prevStartDates ? new Date(prevStartDates) : new Date();
                            setPrevStartDates.setDate(setPrevStartDates.getDate() + 14);
                            setPrevStartDates = setPrevStartDates.toISOString().slice(0, 10);

                            // Set the min and max attributes of the start date input
                            $('#edit-start-date').attr('min', setPrevStartDates); // Y-m-d
                            $('#edit-start-date').attr('max', setNextStartDates); // Y-m-d
                        } else if (lastRows) {
                            //if (indexs == lastIndexs) {
                            // Get the previous row's start date
                            nextStartDates = null;
                            setNextStartDates = null;
                            // Get the next row's start date
                            var prevRowIndex = indexs - 1;
                            prevStartDates = prevRowIndex >= 0 ? $("#datatablesSimple tbody tr:eq(" + prevRowIndex + ") td:first").text() : null;

                            // Parse dates to adjust by 14 days
                            setPrevStartDates = prevStartDates ? new Date(prevStartDates) : new Date();
                            setPrevStartDates.setDate(setPrevStartDates.getDate() - 14);
                            setPrevStartDates = setPrevStartDates.toISOString().slice(0, 10);

                            // Set the min and max attributes of the start date input
                            $('#edit-start-date').attr('max', setPrevStartDates); // Y-m-d
                            $('#edit-start-date').attr('min', setNextStartDates); // Y-m-d
                        } else {
                            // Get the previous row's start date
                            nextStartDates = $("#datatablesSimple tbody tr:eq(" + (indexs - 1) + ") td:first").text();
                            // nextStartDates = indexs > 0 ? $("#datatablesSimple tbody tr:eq(" + (s - 1) + ") td:first").text() : new Date().toISOString().slice(0, 10);
                            // Get the next row's start date
                            prevStartDates = indexs < ($("#datatablesSimple tbody tr").length - 1) ? $("#datatablesSimple tbody tr:eq(" + (indexs + 1) + ") td:first").text() : null;

                            // Parse dates to adjust by 14 days
                            setNextStartDates = nextStartDates ? new Date(nextStartDates) : new Date();
                            setNextStartDates.setDate(setNextStartDates.getDate() - 14);
                            setNextStartDates = setNextStartDates.toISOString().slice(0, 10);

                            setPrevStartDates = prevStartDates ? new Date(prevStartDates) : new Date();
                            setPrevStartDates.setDate(setPrevStartDates.getDate() + 14);
                            setPrevStartDates = setPrevStartDates.toISOString().slice(0, 10);

                            // Set the min and max attributes of the start date input
                            $('#edit-start-date').attr('max', setNextStartDates); // Y-m-d
                            $('#edit-start-date').attr('min', setPrevStartDates); // Y-m-d
                        }


                        console.log("indexs:", indexs);
                        console.log("prevStartDates:", prevStartDates);
                        console.log("nextStartDates:", nextStartDates);
                        console.log("last rows:", lastRows);
                        console.log("setPrevStartDates:", setPrevStartDates);
                        console.log("setNextStartDates:", setNextStartDates);

                        $.ajax({
                            url: 'periods.php',
                            type: 'POST',
                            data: {
                                nextStartDates: nextStartDates,
                                prevStartDates: prevStartDates,
                                indexs: indexs,
                                lastRows: lastRows,
                                ajax: true
                            },
                            success: function (response) {
                                $('#ajaxNextStartDates').val(nextStartDates);
                                $('#ajaxPrevStartDates').val(prevStartDates);
                                $('#ajaxIndexs').val(indexs);
                                $('#ajaxLastRows').val(lastRows);
                                console.log('Value stored successfully in hidden input field.');
                            },
                            error: function (xhr, status, error) {
                                console.error('Error storing value in hidden input field.');
                            }
                        });
                    });
                });
            </script>