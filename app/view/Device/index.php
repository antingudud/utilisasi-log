<?php
if(isset($this->params['data']))
{
    $data = $this->params['data'];
    if(isset($data['devices']))
    {
        $devices = $data['devices'];
    }
}
?>
<div class="flex-col flex w-full">
    <section class="flex flex-col max-w-2xl">
        <h3>Add new device</h3>
        <form class="flex flex-row" action="">
            <select required class="w-2/4 mr-4" name="category" id="category">
                <option value="LAN">LAN</option>
                <option value="WAN">WAN</option>
            </select>
            <input required class="w-4/4 mr-4" type="text" name="name" id="nameDevice">
            <input required class="w-1/4 mr-4 text-center" type="submit" value="OK">
        </form>
    </section>
    
    <div class="flex mt-12">
        <div id="device-list" class="grid w-full grid-cols-2">
            <?php if(isset($devices)) :?>
                <?php foreach($devices as $key => $value): ?>
                    <section class="">
                        <ul>
                            <?php foreach($value as $k => $device): ?>
                            <li <?php echo "name='" . $device['idDevice'] . "'" ?> >
                                <?php echo $device['nameDevice'] ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </section>
                <?php endforeach; ?>
            <?php endif;?>
        </div>
    </div>
</div>