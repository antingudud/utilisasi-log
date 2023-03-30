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
        <section id="device-details" class="flex-col border-2 border-gray-200 rounded-2xl border-solid flex mt-12 pt-6 pb-6 pr-6 pl-6">
            <div>
                <button id="return">
                    Return
                </button>
            </div>
            <hr>
            <?php if(isset($devices)): ?>
                <form method="POST" id="device-edit">
                    <div class="mb-6">
                        <h3 class="font-black max-w-md min-w-fit">
                            <input class="w-full min-w-fit" type="text" name="name" id="device-name" placeholder="<?php echo $devices['name'] ?>" value="<?php echo $devices['name'] ?>">
                            <input type="hidden" name="id" id="id" value="<?php echo $devices['id'] ?>">
                        </h3>
                    </div>
                    <div class="mb-6 max-w-sm min-w-fit w-1/6">
                        <label for="category">Category: </label>
                        <select class="w-full" name="category" id="category">
                            <?php $category = $devices['category']; ?>
                            <option value="<?php echo $category ?>" selected><?php echo $category ?></option>\
                            <option value="<?php if($category === "WAN") { echo "LAN";} else {echo "WAN";} ?>"><?php if($category === "WAN") { echo "LAN";} else {echo "WAN";} ?></option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <button type="submit">Save</button>
                        <button id="reset">Reset</button>
                        <button class="bg-red-600 text-white" id="delete">Delete</button>
                    </div>
                </form>
            <?php endif; ?>
        </section>
    </div>
</div>

<script type="module">
    import { FormHandler } from "{{base-url}}/javascript/FormHandler.js";
    import { popup } from "{{base-url}}/javascript/notification.js";

    $(document).ready(function() {
        const initialName = $("#device-name").val();
        const initialCategory = $("#category").val();
        let formHandler = new FormHandler('device-edit', '{{base-url}}/device/edit', function(response) {
            let pop = popup("{{base-url}}", response);
        })
        $("#reset").on('click', function(e) {
            e.preventDefault();
            $("#device-name").val(initialName);
            $("#category").val(initialCategory);
        });
        $("#return").on('click', function (e) {
            e.preventDefault();
            window.location.replace("{{base-url}}/devices");
        })

        $("#delete").on('click', function(e) {
            e.preventDefault();
            let name = $("#device-name").val();
            let id = $("#id").val();
            if(confirm("Are you sure you want to delete?"))
            {
                $.ajax({
                    type: 'POST',
                    url: "{{base-url}}/device/remove",
                    // dataType: "json",
                    data: JSON.stringify({data: {name: name, id: id}}),
                    success: function(response, status, jqXHR)
                    {
                        window.location.replace("{{base-url}}/devices");
                    },
                    error: function(jqXHR, textStatus, errorThrown)
                    {
                        let pop = popup("{{base-url}}", JSON.parse(jqXHR.responseText));
                    }
                })
            }
        });
    })
</script>