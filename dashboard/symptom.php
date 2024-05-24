<?php
include_once ("header.php");
require_once '../db_config.php';

// Create a new database connection
$conn = new mysqli(DBHOST, DBUSER, DBPWD, DBNAME);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$symptoms = '';
// Handle form submission for editing symptom
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['_method'])) {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $flow = $_POST['flow'];
        $symptoms = implode(", ", $_POST['symptoms']);
        $temperature = $_POST['temperature'];
        // Update the symptom in the database
        $update_query = "UPDATE symptom SET title='$title', description='$description', flow='$flow', symptoms='$symptoms', temperature='$temperature' WHERE id='$id'";
        if ($conn->query($update_query) === TRUE) {
            echo "Symptom updated successfully";
        } else {
            echo "Error updating symptom: " . $conn->error;
        }
    } else {
        // echo "<div style='margin-top:50px;'>";
        // echo "Post: <pre>";
        // print_r($_POST);
        // echo "</pre></br>";
        // echo "</div>";
        $id = $_POST['id'];
        $delete_query = "DELETE FROM symptom WHERE id=?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Error deleting record: " . $conn->error;
        }
        $stmt->close();
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
                            Symptom Data Table
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Flow</th>
                                        <th>Symptoms</th>
                                        <th>Temperature (°F)</th>
                                        <th class="hide-on-print">Edit</th>
                                        <th class="hide-on-print">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $symptom_sql = "SELECT * FROM symptom WHERE user_id='$userID' ORDER BY date DESC";
                                    $result = $conn->query($symptom_sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row['date'] . "</td>";
                                            echo "<td>" . $row['title'] . "</td>";
                                            echo "<td>" . $row['description'] . "</td>";
                                            echo "<td>" . $row['flow'] . "</td>";
                                            echo "<td>" . $row['symptoms'] . "</td>";
                                            echo "<td>" . $row['temperature'] . "</td>";
                                            echo "<td>
                                            <button class='btn btn-success edit-btn' data-bs-toggle='modal' data-bs-target='#symptomModal' 
                                            data-id='" . $row['id'] . "' 
                                            data-title='" . $row['title'] . "' 
                                            data-description='" . $row['description'] . "'
                                            data-flow='" . $row['flow'] . "'
                                            data-symptoms='" . $row['symptoms'] . "'
                                            data-temperature='" . $row['temperature'] . "'
                                            >Edit</button>
                                            </td>";
                                            echo "<td>
                                            <button class='btn btn-danger delete-btn' data-bs-toggle='modal' data-bs-target='#deleteSymptomModal' 
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

            <!-- Edit Symptom Modal -->
            <div class="modal" id="symptomModal">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Symptom</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form class="form" id="editForm" method="POST">
                            <div class="modal-body" style="max-height: 300px; overflow-y: auto;">
                                <input type="hidden" id="edit-id" name="id">
                                <div class="form-group">
                                    <label for="edit-title" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="edit-title" maxlength="50" required
                                        name="title">
                                </div>
                                <div class="form-group">
                                    <label for="edit-description" class="form-label">Description</label>
                                    <textarea class="form-control" id="edit-description" rows="3"
                                        name="description"></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Flow</label>
                                    <?php
                                    $flowOptions = array("light", "medium", "heavy", "disaster");
                                    $selectedFlow = '';
                                    foreach ($flowOptions as $option) {
                                        echo '<div class="form-check">';
                                        echo '<input class="form-check-input" type="radio" name="flow" id="' . $option . '" value="' . $option . '"';
                                        // if ($selectedFlow == $option) {
                                        //     echo $selectedFlow;
                                        //     echo ' selected';
                                        // }
                                        echo '>';
                                        echo '<label class="form-check-label" for="' . $option . '">' . ucfirst($option) . '</label>';
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Symptoms</label>
                                    <?php
                                    $symptomOptions = array("Headache", "Low back pain", "Tender breasts", "Abdominal cramps", "Spotting");
                                    $symptoms = explode(',', $symptoms);
                                    foreach ($symptomOptions as $option) {
                                        echo '<div class="form-check">';
                                        echo '<input class="form-check-input" type="checkbox" id="' . str_replace(' ', '-', strtolower($option)) . '" name="symptoms[]" value="' . $option . '"';
                                        if (in_array($option, $symptoms)) {
                                            echo ' checked';
                                        }
                                        echo '>';
                                        echo '<label class="form-check-label" for="' . str_replace(' ', '-', strtolower($option)) . '">' . $option . '</label>';
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                                <div class="form-group">
                                    <label for="edit-temperature" class="form-label">Temperature (°F)</label>
                                    <input type="number" class="form-control" id="edit-temperature" min="0" max="100"
                                        step="0.1" name="temperature">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Delete Symptom Modal -->
            <div class="modal" id="deleteSymptomModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Symptom</h5>
                        </div>
                        <form class="form" id="deleteForm" method="POST">
                            <div class="modal-body">
                                <input type="hidden" id="delete-id" name="id">
                                <input type="hidden" name="_method" value="DELETE">
                                <h4> Are you sure you want to <span style='color:#FB5988; font-weight: bold;'>
                                        delete ?</span></h4>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
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
                $(document).ready(function () {
                    $('.edit-btn').click(function () {
                        var id = $(this).data('id');
                        var title = $(this).data('title');
                        var description = $(this).data('description');
                        var flow = $(this).data('flow');
                        var symptoms = $(this).data('symptoms');
                        var temperature = $(this).data('temperature');
                        $('#edit-id').val(id);
                        $('#edit-title').val(title);
                        $('#edit-description').val(description);
                        $('input[name="flow"][value="' + flow + '"]').prop('checked', true);

                        var symptomsArr = symptoms.split(',').map(function (item) {
                            return item.trim();
                        });
                        $('input[name="symptoms[]"]').prop('checked', false);
                        $.each(symptomsArr, function (index, value) {
                            var checkboxId = '#' + value.trim().toLowerCase().replace(/\s+/g, '-');
                            $(checkboxId).prop('checked', true);
                        });

                        $('#edit-temperature').val(temperature);
                    });
                    $('.delete-btn').click(function () {
                        var id = $(this).data('id');
                        console.log(id);
                        $('#delete-id').val(id);
                    });
                });

            </script>