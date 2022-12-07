<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/utilisasi-log/public/script/classes/New.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/chota@latest">
</head>

<body>
    <header class="is-full-width bg-dark text-light">
        <nav class="container nav">
            <div class="nav-left">
                <h1>Reporting</h1>
            </div>

            <div class="nav-right tabs">
                <a class="text-light" href="http://localhost/utilisasi-log/">Home</a>
            </div>
        </nav>
    </header>
    <main class="container is-center">
        <section class="container is-center row">

            <section class="container">
                <button><a href="../view">Return</a></button>
            </section>

            <section class="container" id="chartView">
                <form action="" method="post" id="utilForm" required>
                    <ul>
                        <li>
                            <label for="category">Kategori</label><br>
                            <input type="radio" name="category" value="LAN" id="lan">
                            <label for="lan">LAN</label><br>
                            <input type="radio" name="category" value="WAN" id="wan">
                            <label for="wan">WAN</label>
                        </li>
                        <li>
                            <label for="device">Device</label>
                            <select name="device" id="device" required>
                                <option hidden disabled selected value> -- select an option -- </option>
                            </select>
                        </li>
                        <li>
                            <label for="bulan">Bulan</label>
                            <input required type="radio" name="period" id="bulan" value="bulan">
                            <select class="timeframe" id="month" style="display: none;">
                                <option hidden disabled selected value>Bulan</option>
                                <?php
                                $monthList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                $iteration = 0;
                                foreach ($monthList as $month) {
                                    $iteration++;
                                    echo "<option value='${iteration}'>$month</option>";
                                }
                                ?>
                            </select><br>

                            <label for="kuartal">Semester</label>
                            <input type="radio" name="period" id="kuartal" value="semester">
                            <select class="timeframe" id="semester" style="display: none;">
                                <option hidden disabled selected value>Semester</option>
                                <?php
                                $semestres = ['Semester 1', 'Semester 2'];
                                $iteration = 0;
                                foreach ($semestres as $semester) {
                                    $iteration++;
                                    echo "<option value='${iteration}'>$semester</option>";
                                }
                                ?>
                            </select>
                        <li>
                            <select name="year" id="year">
                                <option selected value="<?php echo (int)date("Y") ?>"><?php echo (int)date("Y") ?></option>
                                <?php
                                $years = 2010;
                                while ($years <= (int)date("Y")) {
                                    echo "<option value='${years}'>$years</option>";
                                    $years++;
                                }
                                ?>
                            </select>
                        </li>
                        </li>
                        <button name="submit" id="submit">Submit</button>
                        </li>
                    </ul>
                </form>
            </section>
            
            <div class="container col" id="">
                <div class="">
                    <h3>Chart</h3>
                </div>

                <div class="" style="overflow: auto;">
                    <img style="max-width:none !important;" id="chart" src="" alt="">
                </div>

                <div style="min-height: 90vh;">

                </div>

            </div>

        </section>
    </main>
</body>

<script>
    let ajax = new New();
    ajax.toggleCategory();
    ajax.reportFormHandler();

    $('input[type=radio][name=period]').change(function() {
        switch ($(this).val()) {
            case 'bulan':
                console.log($(this).val())
                $("#month").css("display", "")
                $("#semester").css("display", "none")
                $("#semester").val(null);
                break;
            case 'semester':
                console.log($(this).val())
                $("#semester").css("display", "")
                $("#month").css("display", "none")
                $("#month").val(null);
                break;
        }
    })
</script>

</html>