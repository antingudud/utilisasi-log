<?php $data = $this->params['content'];
$monthList = $this->params['month'];
$semestres = $this->params['semester'] ?>
<div class="flex flex-col w-full">
    <section>
        <a href="{{base-url}}/spreadsheet"><button type="">Return</button></a>
    </section>

    <section class="max-w-2xl w-full">
        <form action="" id="utilForm">
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
                        <!-- <option hidden disabled selected value>Bulan</option> -->
                        <?php
                        for ($m = 1; $m<=12; $m++) {
                            $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
                            echo "<option value='$m'>$month</option>";
                        }
                        ?>
                    </select><br>

                    <label for="kuartal">Semester</label>
                    <input type="radio" name="period" id="kuartal" value="semester">
                    <select class="timeframe" id="semester" style="display: none;">
                        <option hidden disabled selected value>Semester</option>
                        <?php
                        $iteration = 0;
                        foreach ($semestres as $semester) {
                            $iteration++;
                            echo "<option value='${iteration}'>$semester</option>";
                        }
                        ?>
                    </select>
                <li>
                    <select name="year" id="year">
                        <?php
                        $year = (int)date("Y");
                        $yearLimit = 2010;
                        while ($year != $yearLimit) {
                            echo "<option value='${year}'>$year</option>";
                            $year--;
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

    <section class="flex flex-col items-center justify-center w-full container col" id="">
        <div class="self-start justify-self-start">
            <h3>Chart</h3>
        </div>

        <div class="" style="overflow: auto;">
            <img style="max-width:none !important;" id="chart" src="" alt="">
        </div>

        <div style="min-height: 90vh;">

        </div>

    </section>
</div>

<script>
    $(document).ready(function() {
        // Select current month in option
        let monthSelect = document.getElementById('month');
        let monthOptions = monthSelect.options;
        let currentMonth = new Date().getMonth() + 1;
        
        for(let i = 0; i < monthOptions.length; i++)
        {
            if(monthOptions[i].value == currentMonth)
            {
                monthSelect.selectedIndex = i;
                break;
            }
        }
        
        $('input:radio[name=category]').change(function() {
            var value = this.value
            $.ajax({
                type: 'POST',
                url: '{{base-url}}/options/devices',
                data: {
                    category: value
                },
                success: function(response) {
                    response = JSON.parse(response)
                    $('#device').empty()
                    $('#device').append('<option hidden disabled selected value> -- select an option -- </option>')
                    for (var i = 0; i < response.length; i++) {
                        $('#device').append('<option value="' + response[i].idDevice + '">' + response[i].nameDevice + '</option>');
                    }
                }
            })
        })

        $('input:radio[name=period]').change(function() {
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

        $('#utilForm').submit(function(e) {
            e.preventDefault()

            $("#chart").attr(
                "src",
                `{{base-url}}/app/view/Transaction/sus.php?idDevice=${$('#device').val()}&selectedTime=${$('.timeframe:visible').val()}&range=${$('input[type=radio][name=period]:checked').val()}&year=${$('#year').val()}`
            )
        })
    })
</script>