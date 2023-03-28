<?php
if(isset($this->params['data']))
{
    $data = $this->params['data'];
    if(isset($data['devices']))
    {
        // $devices = $data['devices'];
    }
}
?>
<div>
    <?php if(isset($devices)) :?>
        <ul>
        <?php foreach($devices as $key => $value) :?>
            <li><?php print_r($value) ?></li>
        <?php endforeach; ?>
        </ul>
    <?php endif;?>
</div>