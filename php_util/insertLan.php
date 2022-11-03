<?php
require($_SERVER['DOCUMENT_ROOT'] . "/util/include/include.php");
$a = new device;
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