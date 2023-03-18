<?php $data = $this->params['content'];
$monthList = $this->params['month'];
$semestres = $this->params['semester'] ?>
<div>
    <section>
        <a href="{{baseUrl}}/view"><button type="">Return</button></a>
    </section>

    <section>
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
                        <option hidden disabled selected value>Bulan</option>
                        <?php
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

    <section class="container col" id="">
        <div class="">
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
        $('input:radio[name=category]').change(function() {
            var value = this.value
            $.ajax({
                type: 'POST',
                url: '/utilisasi-log/options/devices',
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
                `/utilisasi-log/app/view/Transaction/sus.php?idDevice=${$('#device').val()}&selectedTime=${$('.timeframe:visible').val()}&range=${$('input[type=radio][name=period]:checked').val()}&year=${$('#year').val()}`
            )
        })
    })
</script>