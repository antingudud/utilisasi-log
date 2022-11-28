<?php
require_once dirname(__DIR__, 1) . '/vendor/autoload.php';
$a = new Device;
$e = $a->showDeviceCategory("LAN");
?>
<?php $iteration = 0 ?>
<option hidden disabled selected value="">Device</option>
<?php foreach ($e as $option) : ?>
    <option value="<?php print($e[$iteration]["idDevice"]) ?>">
        <p><?php print($e[$iteration]["nameDevice"]) ?></p>
    </option>
    <?php $iteration++ ?>
<?php endforeach ?>