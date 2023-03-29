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
    <div class="flex-grow-0 flex-shrink-0">

    </div>
    <section class="flex flex-col w-full ml-auto pt-6 pb-6 pl-6 pr-6 max-w-2xl">
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
    
    <hr class="mt-12">

    <div class="border-2 border-gray-100 rounded-2xl border-solid flex mt-12">
        <div class="grid w-full grid-cols-2">
            <?php if(isset($devices)) :?>
                <?php foreach($devices as $key => $value): ?>
                    <section class="" id="device-list">
                        <ul>
                            <?php foreach($value as $k => $device): ?>
                            <li <?php echo "name='" . $device['idDevice'] . "'" ?> >
                                <a class="text-sky-600 hover:underline" href="{{base-url}}/device?device=<?php echo $device['idDevice'] ?>"><?php echo $device['nameDevice'] ?></a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </section>
                <?php endforeach; ?>
            <?php endif;?>
        </div>
    </div>
</div>

<script>
</script>