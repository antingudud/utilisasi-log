<?php
$log = $this->params['log'];
$monthList = $this->params['month'];
?>

<button onclick="location.reload()">Return</button>

<form id="timeFrame" action="" method="get">
    <select name="selectMonth" id="selectMonth">
        <option selected value="<?php echo date('n') ?>"><?php echo date('F') ?></option>
        <?php
        $iteration = 0;
        foreach ($monthList as $month) {
            $iteration++;
            echo "<option value='${iteration}'>$month</option>";
        }
        ?>
    </select>
    <select name="selectYear" id="selectYear">
        <?php
        $year = (int)date("Y");
        $yearLimit = 2010;
        while ($year != $yearLimit) {
            echo "<option value='${year}'>$year</option>";
            $year--;
        }
        ?>
    </select>
    <button class="button" type="submit" id="submitTimeFrame">Go</button>
</form>

<form class="flex flex-col h-screen" id="viewTableForm" action="" method="POST">
    <table class="relative w-full border striped" id="indexViewTable" border="1px" cellpadding="10px" cellspacing="0px">
        <tr>
            <th colspan="13" class="sticky top-0 px-6 py-3 bg-slate-300">
                <button class="button"><a href="{{baseUrl}}/view/new">Upload data</a></button>
                <button class="button">Category</button>
                <button class="button" name="alternateTableLook" id="alternateTableLook">Alternate Look</button>
                <button class="button" name="chartReport" id="chartReport"><a href="{{baseUrl}}/view/report">Report</a></button>
            </th>
        </tr>
        <tr>
            <td style="top: 53px;" class="sticky px-6 py-3 bg-white" rowspan="2">Tanggal</td>
            <td style="top: 53px;" class="sticky px-6 py-3 bg-white" colspan="2">CR Indihome</td>
            <td style="top: 53px;" class="sticky px-6 py-3 bg-white" colspan="2">CP Indihome</td>
            <td style="top: 53px;" class="sticky px-6 py-3 bg-white" colspan="2">PK Biznet</td>
            <td style="top: 53px;" class="sticky px-6 py-3 bg-white" colspan="2">PK Indosat</td>
            <td style="top: 53px;" class="sticky px-6 py-3 bg-white" colspan="2">CK Orbit</td>
            <td style="top: 53px;" class="sticky px-6 py-3 bg-white" colspan="2">CK XL</td>
        </tr>
        <tr>
            <th style="top: 93.6px;" class="sticky px-6 py-3 bg-white" >Download</th>
            <th style="top: 93.6px;" class="sticky px-6 py-3 bg-white" >Upload</th>
            <th style="top: 93.6px;" class="sticky px-6 py-3 bg-white" >Download</th>
            <th style="top: 93.6px;" class="sticky px-6 py-3 bg-white" >Upload</th>
            <th style="top: 93.6px;" class="sticky px-6 py-3 bg-white" >Download</th>
            <th style="top: 93.6px;" class="sticky px-6 py-3 bg-white" >Upload</th>
            <th style="top: 93.6px;" class="sticky px-6 py-3 bg-white" >Download</th>
            <th style="top: 93.6px;" class="sticky px-6 py-3 bg-white" >Upload</th>
            <th style="top: 93.6px;" class="sticky px-6 py-3 bg-white" >Download</th>
            <th style="top: 93.6px;" class="sticky px-6 py-3 bg-white" >Upload</th>
            <th style="top: 93.6px;" class="sticky px-6 py-3 bg-white" >Download</th>
            <th style="top: 93.6px;" class="sticky px-6 py-3 bg-white" >Upload</th>
        </tr>
        <?php $indexList = 0;
        foreach ($log as $row) : ?>
            <tr>
                <td><?php print $row["date"]; ?></td>
                <td><?php print $row["dl_CR_Indihome"]; ?></td>
                <td><?php print $row["ul_CR_Indihome"]; ?></td>
                <td><?php print $row["dl_CP_Indihome"]; ?></td>
                <td><?php print $row["ul_CP_Indihome"]; ?></td>
                <td><?php print $row["dl_PK_Biznet"]; ?></td>
                <td><?php print $row["ul_PK_Biznet"]; ?></td>
                <td><?php print $row["dl_PK_Indosat"]; ?></td>
                <td><?php print $row["ul_PK_Indosat"]; ?></td>
                <td><?php print $row["dl_CK_Orbit"]; ?></td>
                <td><?php print $row["ul_CK_Orbit"]; ?></td>
                <td><?php print $row["dl_CK_XL"]; ?></td>
                <td><?php print $row["ul_CK_XL"]; ?></td>
            </tr>
        <?php $indexList++;
        endforeach ?>
    </table>
</form>
<script>
    $(document).ready(function() {
        $('#timeFrame').submit(function(e) {
            e.preventDefault();

            year = $('#selectYear').val()
            month = $('#selectMonth').val()
            
            $.ajax(
                {
                    url: '/utilisasi-log/options/new',
                    type: 'POST',
                    data: {year: year, month: month},
                    cache: false,
                    success: function(response)
                    {
                        let lines = response.split('\n');
                        let newResponse = lines.slice(12).join('\n');
                        $('body').html(
                            newResponse
                        )
                    },
                    error: function(xhr, status, response)
                    {
                        console.log(response)
                    }
                })
        })
    })
</script>