<?php
date_default_timezone_set('Asia/Kathmandu');
$sql = "SELECT id, start_date, period_length, cycle_length FROM cycles WHERE user_id='$userID'";
$result = $conn->query($sql);

// Initialize an empty array to store cycles
$cycles = [];

// Fetch cycles from the database and store them in the $cycles array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Define the structure of each cycle as an associative array
        $cycle = [
            'cycleLength' => $row['cycle_length'],
            'periodLength' => intval($row['period_length']),
            'startDate' => $row['start_date']
        ];
        // Add the current cycle to the $cycles array
        $cycles[] = $cycle;
    }
} else {
    ?>
    <script src="js/jquery.min.js"></script>
    <script>
        (function ($) {
            "use strict";

            $(document).ready(function () {
                <?php if (empty($cycles)) { ?>
                    $('#welcomeModal').show(250);
                <?php } ?>
            });
        })(jQuery);
    </script>

    <?php
}
// Function to get the start date of the last cycle
function getLastStartDate($cycle)
{
    if (empty($cycle)) {
        return ""; // If $cycles array is empty, return an empty string
    }

    // Get the last cycle from the $cycles array
    $lastCycle = end($cycle);
    // Return the start date of the last cycle
    return $lastCycle['startDate'];
}
function getLengthOfLastPeriod($cycle)
{
    if (empty($cycle)) {
        return 0; // If $cycles array is empty, return zero
    }

    // Get the last cycle from the $cycles array
    $lastCycle = end($cycle);
    // Return the period length of the last cycle
    return $lastCycle['periodLength'];
}

function getDayOfCycle($cycles)
{
    if (empty($cycles)) {
        return 0;
    }

    // Get the start date of the first cycle
    $start = strtotime($cycles[count($cycles) - 1]['startDate']);
    // Get the current date
    $currentDate = strtotime(date('Y-m-d'));

    // Calculate the difference in days between current date and start date, and add 1
    $dayOfCycle = floor(($currentDate - $start) / (60 * 60 * 24)) + 1;

    return intval($dayOfCycle);
}

function getAverageLengthOfCycle($cycles)
{

    if (empty($cycles)) {
        return 0;
    }

    if ((count($cycles) == 1) && ($cycles[count($cycles) - 1]['cycleLength'] == 0)) {
        return 28;
    }

    if (count($cycles) == 1) {
        return $cycles[count($cycles) - 1]['cycleLength'];
    }

    $sum = 0;
    for ($i = 0; $i < count($cycles) - 1; $i++) {
        $sum += $cycles[$i]['cycleLength'];
    }
    return round($sum / (count($cycles) - 1));
}

function getAverageLengthOfPeriod($cycles)
{
    if (empty($cycles)) {
        return 0;
    }

    if (count($cycles) == 1) {
        return $cycles[count($cycles) - 1]['periodLength'];
    }

    $sum = 0;
    for ($i = 0; $i < count($cycles) - 1; $i++) {
        $sum += $cycles[$i]['periodLength'];
    }

    return round($sum / (count($cycles) - 1));
}

function getPhase($cycles)
{
    // Define phase titles, descriptions, and symptoms
    $phases = [
        'non' => [
            'title' => "",
            'description' => "",
            'symptoms' => [""]
        ],
        'menstrual' => [
            'title' => "Menstrual phase",
            'description' => "This cycle is accompanied by low hormone levels.",
            'symptoms' => [
                "lack of energy and strength",
                "pain",
                "weakness and irritability",
                "increased appetite"
            ]
        ],
        'follicular' => [
            'title' => "Follicular phase",
            'description' => "The level of estrogen in this phase rises and reaches a maximum level.",
            'symptoms' => [
                "strength and vigor appear",
                "endurance increases",
                "new ideas and plans appear",
                "libido increases"
            ]
        ],
        'ovulation' => [
            'title' => "Ovulation phase",
            'description' => "Once estrogen levels peak, they trigger the release of two important ovulation hormones, follicle-stimulating hormone and luteinizing hormone.",
            'symptoms' => [
                "increased sexual desire",
                "optimistic mood",
                "mild fever",
                "lower abdominal pain",
                "chest discomfort and bloating",
                "characteristic secretions"
            ]
        ],
        'luteal' => [
            'title' => "Luteal phase",
            'description' => "Levels of the hormones estrogen and progesterone first rise and then drop sharply just before a period. Progesterone reaches its peak in the luteal phase.",
            'symptoms' => [
                "breast tenderness",
                "puffiness",
                "acne and skin rashes",
                "increased appetite",
                "diarrhea or constipation",
                "irritability and depressed mood"
            ]
        ]
    ];

    // Initialize variables
    $lengthOfCycle = getAverageLengthOfCycle($cycles);
    $lengthOfPeriod = getLengthOfLastPeriod($cycles);
    $currentDay = getDayOfCycle($cycles);
    $lutealPhaseLength = 14;
    $ovulationOnError = 2;

    // Determine the phase based on current day and cycle lengths
    if (empty($cycles)) {
        return $phases['non'];
    }

    $ovulationDay = $lengthOfCycle - $lutealPhaseLength;

    if ($currentDay <= $lengthOfPeriod) {
        return $phases['menstrual'];
    }
    if ($currentDay <= $ovulationDay - $ovulationOnError) {
        return $phases['follicular'];
    }
    if ($currentDay <= $ovulationDay + $ovulationOnError) {
        return $phases['ovulation'];
    }
    return $phases['luteal'];
}

function getPastFuturePeriodDays($cycles)
{
    // Get today's date
    $nowDate = date('Y-m-d');

    // Get period dates from cycles
    $periodDates = getPeriodDays($cycles);

    // Get the average length of period
    $lengthOfPeriod = getAverageLengthOfPeriod($cycles);

    // If there are cycles, check if current cycle has ended
    if (!empty($cycles)) {
        $endOfCurrentCycle = date('Y-m-d', strtotime($cycles[count($cycles) - 1]['startDate'] . ' +' . $cycles[0]['periodLength'] . ' days'));
        if ($endOfCurrentCycle >= $nowDate) {
            return [];
        }
    }

    // Add future period days
    $futurePeriodDays = [];
    for ($day = 0; $day < ($lengthOfPeriod ?: 5); $day++) {
        $futurePeriodDays[] = date('Y-m-d', strtotime($nowDate . " +$day day"));
    }

    // Combine past and future period days
    $periodDates = array_merge($periodDates, $futurePeriodDays);

    return $periodDates;
}
function getPeriodDays($cycles)
{
    $periodDays = [];

    foreach ($cycles as $cycle) {
        $startOfCycle = strtotime($cycle['startDate']);

        for ($i = 0; $i < $cycle['periodLength']; $i++) {
            $newDate = strtotime("+$i days", $startOfCycle);
            $formattedDate = date('Y-m-d', $newDate);
            $periodDays[] = $formattedDate;
        }
    }

    return $periodDays;
}

function getNewCyclesHistory($periodDays)
{
    // Check if periodDays array is empty
    if (empty($periodDays)) {
        return [];
    }

    // Sort periodDays array in ascending order
    sort($periodDays);

    $newCycles = [
        [
            'cycleLength' => 28,
            'periodLength' => 1,
            'startDate' => $periodDays[0],
        ],
    ];

    // Iterate through periodDays array starting from the second element
    for ($i = 1; $i < count($periodDays); $i++) {
        $date = strtotime($periodDays[$i]);
        $prevDate = strtotime($periodDays[$i - 1]);
        $diffInDays = floor(($date - $prevDate) / (60 * 60 * 24));

        if ($diffInDays <= 1) {
            $newCycles[0]['periodLength']++;
        } elseif ($diffInDays <= 2) {
            $newCycles[0]['periodLength'] += 2;
        } else {
            $newCycleLength = $diffInDays + $newCycles[0]['periodLength'] - 1;
            array_unshift($newCycles, [
                'cycleLength' => 0,
                'periodLength' => 1,
                'startDate' => $periodDays[$i],
            ]);
            $newCycles[1]['cycleLength'] = $newCycleLength;
        }
    }

    // Filter newCycles array to remove cycles with start date in the future
    $today = date('Y-m-d');
    $newCycles = array_filter($newCycles, function ($cycle) use ($today) {
        return strtotime($cycle['startDate']) <= strtotime($today);
    });

    return $newCycles;
}

function getForecastPeriodDays($cycles)
{
    if (empty($cycles)) {
        return [];
    }

    // Calculate average length of cycle and period
    $lengthOfCycle = getAverageLengthOfCycle($cycles);
    $lengthOfPeriod = getAverageLengthOfPeriod($cycles);
    $dayOfCycle = getDayOfCycle($cycles);
    $nowDate = strtotime('today');

    $forecastDates = [];

    function addForecastDates($startDate, $lengthOfPeriod)
    {
        $forecastDates = [];
        for ($i = 0; $i < $lengthOfPeriod; ++$i) {
            $forecastDate = strtotime("+$i days", $startDate);
            $formattedDate = date('Y-m-d', $forecastDate);
            $forecastDates[] = $formattedDate;
        }
        return $forecastDates;
    }

    $nextCycleStart = strtotime("+$lengthOfCycle days", strtotime($cycles[count($cycles) - 1]['startDate']));
    // if ($dayOfCycle <= $lengthOfCycle) {
    //     $nextCycleStart = strtotime("+$lengthOfCycle days", strtotime($cycles[count($cycles) - 1]['startDate']));
    // } else {
    //     $nextCycleStart = $nowDate;
    // }

    $forecastDates = array_merge($forecastDates, addForecastDates($nextCycleStart, $lengthOfPeriod));

    if (count($cycles) === 1) {
        return $forecastDates;
    }

    $cycleCount = 6;
    for ($i = 0; $i < $cycleCount; ++$i) {
        $nextCycleStart = strtotime("+$lengthOfCycle days", $nextCycleStart);
        $forecastDates = array_merge($forecastDates, addForecastDates($nextCycleStart, $lengthOfPeriod));
    }

    return $forecastDates;
}

function isPeriodToday($cycles)
{
    if (empty($cycles)) {
        return false;
    }

    // Get the day of the cycle
    $dayOfCycle = getDayOfCycle($cycles);

    return $dayOfCycle <= $cycles[count($cycles) - 1]['periodLength'];
}
function isAddPeriodToday($cycles)
{
    if (empty($cycles)) {
        return false;
    }

    // Get the day of the cycle
    $dayOfCycle = getDayOfCycle($cycles);

    return $dayOfCycle <= ($cycles[count($cycles) - 1]['periodLength']) + 7;
}

function getPregnancyChance($cycles)
{
    if (empty($cycles)) {
        return "";
    }

    // Get the day of the cycle
    $dayOfCycle = getDayOfCycle($cycles);
    // Get the average length of the cycle
    $cycleLength = getAverageLengthOfCycle($cycles);

    // Define luteal phase length
    $lutealPhaseLength = 14;
    // Calculate the day of ovulation
    $ovulationDay = $cycleLength - $lutealPhaseLength;
    // Calculate the difference between the ovulation day and the current day of the cycle
    $diffDay = $ovulationDay - $dayOfCycle;

    // Check the difference day to determine pregnancy chance
    if ($diffDay >= -2 && $diffDay <= 1) {
        return "High";
    } else {
        return "Low";
    }
}

function getOvulationStatus($cycles)
{
    if (empty($cycles)) {
        return "";
    }

    // Get the average length of the cycle
    $cycleLength = getAverageLengthOfCycle($cycles);
    // Get the day of the cycle
    $dayOfCycle = getDayOfCycle($cycles);

    // Define luteal phase length
    $lutealPhaseLength = 14;
    // Calculate the day of ovulation
    $ovulationDay = $cycleLength - $lutealPhaseLength;
    // Calculate the difference between the ovulation day and the current day of the cycle
    $diffDay = $ovulationDay - $dayOfCycle;

    // Check the difference day to determine ovulation status
    if ($diffDay < -2) {
        return "finished";
    } elseif ($diffDay < 0) {
        return "possible";
    } elseif ($diffDay === 0) {
        return "today";
    } elseif ($diffDay === 1) {
        return "tomorrow";
    } else {
        return "in " . $diffDay . " Days";
    }
}
// PHP code
function startOfToday()
{
    return date_create(date("Y-m-d 00:00:00"));
}

function differenceInDays($date1, $date2)
{
    $diff = date_diff($date1, $date2);
    return $diff->days;
}

function startOfDay($date)
{
    // If $date is already a DateTime object, clone it
    if ($date instanceof DateTime) {
        $startOfDay = clone $date;
    } else {
        // If $date is a string, create a new DateTime object from it
        $startOfDay = date_create($date);
    }
    // Set the time to the start of the day (00:00:00)
    $startOfDay->setTime(0, 0, 0);
    return $startOfDay;
}

function addDays($date, $amount)
{
    $date->modify("+$amount days");
    return $date;
}

// Make sure to import or define getAverageLengthOfCycle and getDayOfCycle functions

function getDaysBeforePeriod($cycles)
{
    if (empty($cycles)) {
        return [
            'title' => "Period in",
            'days' => "---"
        ];
    }

    $periodLength = $cycles[count($cycles) - 1]['periodLength'];
    $dayOfCycle = getDayOfCycle($cycles);

    if ($dayOfCycle <= $periodLength) {
        return [
            'title' => "Period",
            'days' => "Day $dayOfCycle"
        ];
    }

    $startDate = $cycles[count($cycles) - 1]['startDate'];
    $cycleLength = getAverageLengthOfCycle($cycles);

    $startDateString = date_format(date_create($startDate), 'Y-m-d');
    $startOfDay = startOfDay($startDateString);

    // Calculate date of finish by adding cycleLength days to startOfDay
    $dateOfFinish = addDays($startOfDay, $cycleLength);

    $now = startOfToday();
    $dayBefore = differenceInDays($dateOfFinish, $now);

    if (($dayBefore < 0) || ($cycles[count($cycles) - 1]['cycleLength'] == 0)) {
        return [
            'title' => "Period in",
            'days' => "$dayBefore Days"
        ];
    }

    if ((count($cycles) === 1) && ($dayBefore === 0)) {
        return [
            'title' => "Period is",
            'days' => "Possible today"
        ];
    }

    if ($dayBefore === 0) {
        return [
            'title' => "Period",
            'days' => "Today"
        ];
    }

    if ($dayOfCycle > $cycleLength) {
        return [
            'title' => "Delays",
            'days' => abs($dayBefore) . " Days"
        ];
    }

    if (($dayBefore > 0) && ($cycles[count($cycles) - 1]['cycleLength'] != 0) && count($cycles) == 1) {
        return [
            'title' => "Period in",
            'days' => "$dayBefore Days"
        ];
    }


    return [
        'title' => "Delay",
        'days' => abs($dayBefore) . " Days"
    ];
}

function getOvulationDays($cycles)
{
    if (empty($cycles)) {
        return "";
    }

    // Get the average length of the cycle
    $cycleLength = getAverageLengthOfCycle($cycles);
    // Get the day of the cycle
    $dayOfCycle = getDayOfCycle($cycles);

    // Define luteal phase length
    $lutealPhaseLength = 14;
    // Calculate the day of ovulation
    $ovulationDay = $cycleLength - $lutealPhaseLength;
    // Calculate the difference between the ovulation day and the current day of the cycle
    $diffDay = $ovulationDay - $dayOfCycle;
    $ovulationDates = [];

    $nowDate = new DateTime();
    $startDate = $nowDate->modify("+$diffDay day");
    for ($i = 0; $i < 5; $i++) {
        if ($i == 0) {
            $ovulationDates[] = $startDate->format('Y-m-d');
            continue;
        }
        $ovulationDates[] = $startDate->modify("+1 day")->format('Y-m-d');
    }

    return $ovulationDates;
}

function getOvulation($cycles)
{
    if (count($cycles) < 2) {
        return [];
    }

    $averageCycle = getAverageLengthOfCycle($cycles);
    $dayOfCycle = getDayOfCycle($cycles);
    $ovulationDates = [];

    foreach ($cycles as $cycle) {
        $startOfCycle = new DateTime($cycle['startDate']);
        $startOfCycle->setTime(0, 0, 0);
        $finishOfCycle = null;

        if ($cycle['cycleLength'] === 0) {
            if ($dayOfCycle > $averageCycle) {
                $finishOfCycle = addDays($startOfCycle, $dayOfCycle - 17);
            } else {
                $finishOfCycle = addDays($startOfCycle, $averageCycle - 16);
            }
        } else {
            $finishOfCycle = addDays($startOfCycle, $cycle['cycleLength'] - 16);
        }

        for ($i = 0; $i < 4; ++$i) {
            $newDate = clone $finishOfCycle;
            $newDate->modify("+$i days");
            $ovulationDates[] = $newDate->format('Y-m-d');
        }
    }

    return $ovulationDates;
}
//This is single line comment
//nasmn cnas
/*
This is 
multi line comment
jsnac 
asncm,'
*/
?>

