<?php
// config.php (Assumed to contain database connection settings)
require 'config.php';

// Function to find total count of rows in a given table
function find_total($conn, $table)
{
    $sql = "SELECT COUNT(*) AS `total` FROM `$table`";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}

// Retrieve totals
$totals = [
    'students' => find_total($conn, 'students'),
    'teachers' => find_total($conn, 'teachers'),
    'alumni' => find_total($conn, 'alumni'),
    'applicants' => find_total($conn, 'applicants')
];

// Determine the maximum total value for scaling the bar heights
$max_total = max($totals);
$scale_factor = 300 / $max_total; // Adjust the height scale factor based on the container height (300px)
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/favicon/favicon.png" type="image/x-icon">
    <!-- Nucleo Icons CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/nucleo/2.0.6/css/nucleo.css" rel="stylesheet">
    <link rel="stylesheet" href="css/soft-design-system-pro.min3f71.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Bar Chart App</title>
    <style>
        .bar-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            width: 100%;
            height: 300px;
            /* Adjust height as needed */
        }

        .bars {
            width: 40px;
            border-radius: 3px;
            background-color: #f7f7f7;
            transition: height 1s ease-in-out;
            animation-name: barAnimation;
            animation-duration: 3.5s;
            animation-iteration-count: 1;
            animation-timing-function: ease-in;
        }

        @keyframes barAnimation {
            to {
                height: var(--bar-height);
            }
        }

        #bar-students {
            --bar-height: <?php echo find_total($conn, 'students') . 'px'; ?>;
        }
        #bar-teachers {
            --bar-height: <?php echo find_total($conn, 'teachers') . 'px'; ?>;
        }
        #bar-alumni {
            --bar-height: <?php echo find_total($conn, 'alumni') . 'px'; ?>;
        }
        #bar-applicants {
            --bar-height: <?php echo find_total($conn, 'applicants') . 'px'; ?>;
        }

        .points {
            height: 100%;
            position: relative;
            bottom: -30px;
        }
    </style>
</head>

<body class="bg-info-soft">
    <div class="container py-3">
        <!-- Card -->
        <div class="card p-3">
            <!-- Card header -->
            <div class="card-header bg-gradient-dark" style="position: relative;">
                <div class="d-flex align-items-end">
                    <!-- Points -->
                    <div class="points flex-column pe-3 text-center" id="points-container">
                        <?php for ($i = 1000; $i >= 0; $i -= 100) : ?>
                            <p class="chart-canvas text-white font-weight-bold"><?php echo $i; ?></p>
                            <?php endfor; ?>
                    </div>
                    <!-- Bars -->
                    <div class="bar-container">
                        <div class="bars" id="bar-students"></div>
                        <div class="bars" id="bar-teachers"></div>
                        <div class="bars" id="bar-alumni"></div>
                        <div class="bars" id="bar-applicants"></div>
                    </div>
                </div>
            </div>
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <!-- Display Sections Dynamically -->
                    <?php
                    $sections = [
                        'students' => ['icon' => 'ni ni-circle-08', 'bg' => 'bg-gradient-info'],
                        'teachers' => ['icon' => 'ni ni-circle-08', 'bg' => 'bg-gradient-dark'],
                        'alumni' => ['icon' => 'ni ni-circle-08', 'bg' => 'bg-gradient-warning'],
                        'applicants' => ['icon' => 'ni ni-circle-08', 'bg' => 'bg-gradient-primary'],
                    ];
                    foreach ($sections as $section => $details) {
                        $total = $totals[$section];
                    ?>
                        <div class="col-6 col-lg-3 py-3 ps-0">
                            <div class="d-flex mb-2">
                                <div class="icon icon-shape icon-xxs shadow border-radius-sm <?php echo $details['bg']; ?> text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="<?php echo $details['icon']; ?>"></i>
                                </div>
                                <p class="text-xs mt-1 mb-0 font-weight-bold"><?php echo ucfirst($section); ?></p>
                            </div>
                            <h4 class="font-weight-bolder text-uppercase" id="total-<?php echo $section; ?>" countTo="<?php echo $total; ?>"></h4>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <script src="js/countup.min.js"></script>
    <!-- <script src="js/script.js"></script> -->
    <script>
        // Pass the totals to JavaScript
        const totals = <?php echo json_encode($totals); ?>;

        // Function to start count up animation
        function startCountUp(id) {
            const element = document.getElementById(id);
            const countTo = element.getAttribute("countTo");
            const countUp = new CountUp(id, countTo);
            if (!countUp.error) {
                countUp.start();
            } else {
                console.error(countUp.error);
            }
        }

        // Start count up for each section
        ['total-students', 'total-teachers', 'total-alumni', 'total-applicants'].forEach(startCountUp);

        // Animate bars to rise from the bottom
        function animateBars() {
            document.querySelectorAll('.bars').forEach(bar => {
                bar.style.height = getComputedStyle(bar).getPropertyValue('--bar-height');
            });
        }

        // Call function to animate bars after page load
        window.onload = animateBars;
    </script>
</body>

</html>