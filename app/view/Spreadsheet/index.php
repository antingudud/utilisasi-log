<?php
if(isset($this->params['content'], $this->params['header'], $this->params['body'])){
    $data = $this->params['content'];
    $header = $this->params['header'];
    $body = $this->params['body'];
}
?>

<form class="grid" id="timeFrame" action="" method="POST">
    <div class="grid grid-cols-2 max-w-4xl">
        <select class="max-w-md" name="selectMonth" id="selectMonth">
            <option selected value="<?php echo date('n') ?>"><?php echo date('F') ?></option>
            <?php
            $iteration = 0;
            foreach ($monthList as $month) {
                $iteration++;
                echo "<option value='${iteration}'>$month</option>";
            }
            ?>
        </select>
        <select class="max-w-md" name="selectYear" id="selectYear">
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
        <select multiple class="max-w-lg" name="devices" id="list-added">
            <option value="wuuduheele">RouterPC</option>
            <option value="ooomaaagaaa">RouterUS</option>
        </select>
        <button class="items-center w-1/6 max-w-sm" formaction="#">+</button>
    </div>
    <div class="max-w-4xl">
        <button class="inline-block button" type="submit" id="submitTimeFrame">Go</button>
    </div>
</form>

<table>
    <thead>
        <tr>
            <?php foreach (array_keys($data[0]) as $column) { ?>
                <th><?php echo $column; ?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $row) { ?>
            <tr>
                <?php foreach (array_keys($row) as $column) { ?>
                    <td><?php echo $row[$column]; ?></td>
                <?php } ?>
            </tr>
        <?php } ?>
    </tbody>
</table>
<script type="text/javascript" src="{{base-url}}/node_modules\@selectize\selectize\dist\js\selectize.js"></script>
<link rel="stylesheet" type="text/css" href="{{base-url}}/node_modules\@selectize\selectize\dist\css\selectize.css" />
<script type="module">
    import { FormHandler } from "{{base-url}}/javascript/FormHandler.js";
    let formHandler = new FormHandler('timeFrame', '{{base-url}}/input-test');

    console.log('penis')
    let select = $(function() {
        $('#list-added').selectize({
            plugins: ["restore_on_backspace", "clear_button"],
            delimiter: ",",
            persist: false,
            maxItems: null,
            valueField: "device",
            labelField: "name",
            searchField: ["name", "device"],
            create: false,
        });
    });
</script>