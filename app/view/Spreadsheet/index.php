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
<?php //var_dump($table) ?>
<table id="table" class="cell-border stripe order-column table-fixed">
    <?php //print_r($table)?>
    <thead>
        <tr id="header-row-top">
            <?php foreach ($table[0] as $key => $column) { ?>
                <?php if(preg_match('/download|id|upload/', $key)) {continue;}?>
                <th <?php
                if(preg_match("/name|date/", $key, $matches)){
                    echo "name='$matches[0]' ";
                }
                if(preg_match("/name/", $key, $matches)){
                    echo "class='w-1/6' colspan='2'";
                } else if(preg_match("/date/", $key, $matches)){
                    echo "class='w-1/6' rowspan='2'";
                }; ?>>
                <?php if(preg_match('/date/', $key)): echo ucfirst($key); else :echo ucfirst($column); endif; ?></th>
            <?php } ?>
        </tr>
        <tr id="header-row-bottom">
            <?php foreach (array_keys($table[0]) as $column) { ?>
                <?php if(preg_match('/(date|name|id)/', $column)) : continue; endif; ?>
                <th <?php if(preg_match('/date/', $column)){echo "rowspan='2'";}; if(preg_match("/(date|name|download|id|upload)/", $column, $matches)): echo "name='$matches[0]' class='w-1/6 dt-center'";endif; ?>><?php echo ucfirst($matches[0]); ?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($table as $row) { ?>
            <tr>
                <?php foreach (array_keys($row) as $column) { ?>
                    <?php if(preg_match('/(name|id)/', $column)) : continue; endif; ?>
                    <td <?php if(preg_match('/id/', $column)){echo "class='hidden'";}; if(!preg_match('/date/', $column)){echo "name='$column' class='dt-center'"; }?> ><?php echo $row[$column]; ?></td>
                <?php } ?>
            </tr>
        <?php } ?>
    </tbody>
</table>
<?php endif; ?>

<link rel="stylesheet" href="{{base-url}}/vendor/DataTables/datatables.min.css"><script src="{{base-url}}/vendor/DataTables/datatables.min.js"></script>
<script src="{{base-url}}/vendor/jquery-tabledit-1.2.3/jquery.tabledit.js"></script>
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
    $(document).ready(function() {
        $('#table').DataTable({
            paging: false,
            ordering: false,
            searching: false
        });
        if($('#table').length)
        {
            // let editerator = 
            $('#table').Tabledit({
                url: '{{base-url}}/spreadsheet/edit',
                deleteButton: false,
                saveButton: false,
                autoFocus: false,
                editButton: true,
                buttons: {
                    edit: {
                        class: 'btn btn-sm btn-default w-1/6',
                        html: 'Edit'
                    }
                },
                columns: {
                    identifier: [0, 'date'],
                    editable: <?php
                        if(isset($table)){
                            $iterator = 1;
                            $cowsay = [];
                            foreach (array_keys($table[0]) as $column) {
                                if(!preg_match('/download|upload/', $column)) {continue;}
                                $cowsay[] = [$iterator ,$column ];
                                $iterator++;
                            }
                        echo json_encode($cowsay);
                        }
                        else
                        {
                            echo "[]";
                        }
                        ?>
                },
                onSuccess: function (data, textStatus, jqXHR)
                {
                    console.log({data,textStatus,jqXHR});
                },
                onFail: function(jqXHR, textStatus, errorThrown)
                {
                    
                },
                onDraw: function()
                {
                    $("#header-row-top th:last-child")
                    .addClass("w-1/6")
                    $("#header-row-bottom td:last-child button").remove()
                }
            })
        }
    })
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
                // let newDoc = document.open("text/html", "replace");
                // newDoc.write(resp);
                // newDoc.close();
                $('#mainbody').html(resp);
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