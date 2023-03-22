<?php
if(isset($this->params['data'])){
    $data = $this->params['data'];
    $devices = $data['devices'];
}
?>

<form class="grid" id="timeFrame" action="" method="POST">
    <div class="grid grid-cols-2 max-w-4xl">
        <select required class="max-w-md" name="month" id="selectMonth">
            <?php
            for ($m = 1; $m<=12; $m++) {
                $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
                echo "<option value='$m'>$month</option>";
            }
            ?>
        </select>
        <select required class="max-w-md" name="year" id="selectYear">
            <?php
            $year = (int)date("Y");
            $yearLimit = 2010;
            while ($year != $yearLimit) {
                echo "<option value='${year}'>$year</option>";
                $year--;
            }
            ?>
        </select>
    </div>

    <div class="grid grid-cols-3 max-w-4xl">

        <select required multiple class="max-w-lg" name="devices" id="list-added">
        </select>

        <button class="items-center w-1/6 max-w-sm" formaction="#">+</button>
    </div>

    <div class="max-w-4xl">
        <button class="inline-block button" type="submit" id="submitTimeFrame">Go</button>
    </div>
</form>
<?php print_r($devices) ?>
<table>
    <thead>
        <tr>
            <?php //foreach (array_keys($data[0]) as $column) { ?>
                <th><?php //echo $column; ?></th>
            <?php //} ?>
        </tr>
    </thead>
    <tbody>
        <?php //foreach ($data as $row) { ?>
            <tr>
                <?php //foreach (array_keys($row) as $column) { ?>
                    <td><?php //echo $row[$column]; ?></td>
                <?php //} ?>
            </tr>
        <?php //} ?>
    </tbody>
</table>

<script type="text/javascript" src="{{base-url}}/node_modules/@selectize/selectize/dist/js/selectize.js"></script>
<link rel="stylesheet" type="text/css" href="{{base-url}}/node_modules/@selectize/selectize/dist/css\selectize.css" />
<script type="module">
    import { FormHandler } from "{{base-url}}/javascript/FormHandler.js";
    let formHandler = new FormHandler('timeFrame', '{{base-url}}/view/table', function(response){
    });
    let devices;
    console.log('penis')

    let monthSelect = document.getElementById('selectMonth');
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

    let select = $(function() {
        $('#list-added').selectize({
            plugins: ["restore_on_backspace", "clear_button"],
            delimiter: ",",
            persist: false,
            maxItems: null,
            valueField: "idDevice",
            labelField: "nameDevice",
            searchField: "nameDevice",
            create: false,
            preload: true,
            load: function(query, callback)
            {
                // if(!query.length) return callback();
                $.ajax({
                    type: 'POST',
                    url: '{{base-url}}/spreadsheet/devices',
                    success: function(response)
                    {
                        devices = JSON.parse(JSON.stringify(response));
                        devices = devices.data.LAN.concat(devices.data.WAN);
                        console.log(devices)
                        callback(devices);
                    },
                    error: function(xhr,status,response)
                    {
                        alert('error at grubbing');
                    }
                })
            }
        });
    });
</script>