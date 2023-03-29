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
    <div>
        <section id="device-details" class="flex-col border-2 border-gray-100 rounded-2xl border-solid flex mt-12 pt-6 pb-6 pr-6 pl-6">
            <?php if(isset($devices)): ?>
                <h3 class="font-black max-w-md min-w-fit">
                    <input class="w-full min-w-fit" type="text" name="name" id="device-name" placeholder="<?php echo $devices['name'] ?>" value="<?php echo $devices['name'] ?>">
                </h3>
                <p>Category: </p>
                <select name="category" id="category">
                    <option value="<?php echo $devices['category'] ?>" selected><?php echo $devices['category'] ?></option>
                </select>
            <?php endif; ?>
        </section>
    </div>
</div>