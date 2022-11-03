
class inPageFunct {
  toggleCategory() {
    document.getElementById("lan").addEventListener("click", function () {
      console.log("SAY WHO");
      let xhr = new XMLHttpRequest();
      xhr.open("GET", "/util/php_util/insertLan.php");
      xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("device").innerHTML = this.responseText;
        }
      };

      xhr.send();
    });

    document.getElementById("wan").addEventListener("click", function () {
      console.log("SAY WHO");
      let xhr = new XMLHttpRequest();
      xhr.open("GET", "/util/php_util/insertWan.php");
      xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("device").innerHTML = this.responseText;
        }
      };

      xhr.send();
    });
  }

  loadTo(){
  }

  formHandler(){
    $(document).ready(function(){
        $('#utilForm').submit(function() {
            event.preventDefault();
            console.log("sus");

            let download = $('#ownload').val();
            let upload = $('#upload').val();
            let idDevice = $('#device').val();
            let action = "true";

            $.ajax({
                url: "/util/core/AJAXRouter.php",
                type: "POST",
                data: {
                    download: download,
                    upload: upload,
                    idDevice
                },
                cache: false,
                success: function(){
                    console.log("ASUSMOMONGUS");
                }                
            })
        })
    })
  }
}
