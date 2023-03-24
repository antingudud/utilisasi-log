<?php
if(isset($this->params['data'])){
    $data = $this->params['data'];
    if(isset($data['table']))
    {
        $table = $data['table'];
    }
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

<?php if(isset($table)) :?>
<?php var_dump($table) ?>
<table>
    <?php //print_r($table)?>
    <thead>
        <tr>
            <?php foreach (array_keys($table[0]) as $column) { ?>
                <th <?php if($column === "date"): echo "class='w-56'";endif; ?>><?php echo ucfirst($column); ?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($table as $row) { ?>
            <tr>
                <?php foreach (array_keys($row) as $column) { ?>
                    <td><?php echo $row[$column]; ?></td>
                <?php } ?>
            </tr>
        <?php } ?>
    </tbody>
</table>
<?php endif; ?>

<script type="text/javascript" src="{{base-url}}/node_modules/@selectize/selectize/dist/js/selectize.js"></script>
<link rel="stylesheet" type="text/css" href="{{base-url}}/node_modules/@selectize/selectize/dist/css/selectize.css" />
<script type="module">
    $( function () {let select = $(function() {
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
                        console.log('WOWEEE');
                        callback(devices);
                    },
                    error: function(xhr,status,response)
                    {
                        console.error("error");
                        alert('error at grubbing');
                    }
                })
            }
        });
    })});
    import { FormHandler } from "{{base-url}}/javascript/FormHandler.js";
    let formHandler = new FormHandler('timeFrame', '{{base-url}}/view/table', function(response){
        const selectedOptions = {
            month: $('#selectMonth').val(),
            year: $('#selectYear').val(),
        }
        localStorage.setItem('selectedOptions', JSON.stringify(selectedOptions));
        // console.log(JSON.stringify({data: response}))
        $.ajax({
            type: 'POST',
            url: '{{base-url}}/view/spreadsheet',
            data: {data: response},
            success: function(resp)
            {
                console.log('Success');
                let newDoc = document.open("text/html", "replace");
                newDoc.write(resp);
                newDoc.close();
                // $(document).html(resp);
            },
            error: function (xhr, status, resp)
            {
                console.error(xhr + ' ' + status + ' ' + ' ' + resp)
            }
        });
    });
    let devices;
    console.log('start')

    // Select current month in option
    let monthSelect = document.getElementById('selectMonth');
    let monthOptions = monthSelect.options;
    let currentMonth = new Date().getMonth() + 1;
    
    const storedOptions = JSON.parse(localStorage.getItem('selectedOptions'));
    if(storedOptions){
        $('#selectMonth').val(storedOptions.month);
        $('#selectYear').val(storedOptions.year);
    } else {    
        for(let i = 0; i < monthOptions.length; i++)
        {
            if(monthOptions[i].value == currentMonth)
            {
                monthSelect.selectedIndex = i;
                break;
            }
        }
    }

    
</script>