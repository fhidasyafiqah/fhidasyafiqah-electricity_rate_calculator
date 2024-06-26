<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Electricity Rate Calculator</title>
    <h3>Fhidasyafiqah</h3>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1 class="mt-5">Electricity Rate Calculator</h1>
    <form action="" method="POST" class="mt-4">
        <div class="form-group">
            <label for="voltage">Voltage (V)</label>
            <input type="number" step="0.01" class="form-control" id="voltage" name="voltage" required>
        </div>
        <div class="form-group">
            <label for="current">Current (A)</label>
            <input type="number" step="0.01" class="form-control" id="current" name="current" required>
        </div>
        <div class="form-group">
            <label for="rate">Current Rate (sen/kWh)</label>
            <input type="number" step="0.01" class="form-control" id="rate" name="rate" required>
        </div>
        <button type="submit" class="btn btn-primary">Calculate</button>
    </form>

    <?php
    function calculate_charge($energy) {
        $total_charge = 0;

        if ($energy <= 200) {
            $total_charge = $energy * 21.80;
        } else if ($energy <= 300) {
            $total_charge = (200 * 21.80) + (($energy - 200) * 33.40);
        } else if ($energy <= 600) {
            $total_charge = (200 * 21.80) + (100 * 33.40) + (($energy - 300) * 51.60);
        } else if ($energy <= 900) {
            $total_charge = (200 * 21.80) + (100 * 33.40) + (300 * 51.60) + (($energy - 600) * 54.60);
        } else {
            $total_charge = (200 * 21.80) + (100 * 33.40) + (300 * 51.60) + (300 * 54.60) + (($energy - 900) * 57.10);
        }

        // Convert to RM
        $total_charge = $total_charge / 100;

        return $total_charge;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $voltage = floatval($_POST['voltage']);
        $current = floatval($_POST['current']);
        $rate = floatval($_POST['rate']);

        // Calculation
        $power = $voltage * $current; // in Watts
        $energy_per_hour = $power / 1000; // convert to kWh
        $total_charge_per_hour = calculate_charge($energy_per_hour); // in RM for an hour

        echo "<h2 class='mt-5'>Results</h2>";
        echo "<p>Power: " . $power . " W</p>";
        echo "<p>Energy per hour: " . $energy_per_hour . " kWh</p>";
        echo "<p>Total Charge per hour: RM " . number_format($total_charge_per_hour, 2) . "</p>";
        
        // Display hourly details for a day
        echo "<h3 class='mt-3'>Hourly Rates</h3>";
        echo "<table class='table'>";
        echo "<thead><tr><th>Hour</th><th>Energy (kWh)</th><th>Total Charge (RM)</th></tr></thead>";
        echo "<tbody>";

        $cumulative_energy = 0;
        for ($hour = 1; $hour <= 24; $hour++) {
            $cumulative_energy += $energy_per_hour;
            $charge = calculate_charge($cumulative_energy);
            echo "<tr><td>$hour</td><td>$cumulative_energy</td><td>" . number_format($charge, 2) . "</td></tr>";        }

        echo "</tbody></table>";
    }
    ?>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
