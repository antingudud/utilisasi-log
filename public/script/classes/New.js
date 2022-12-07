class New {
  toggleCategory() {
    document.getElementById("lan").addEventListener("click", function () {
      console.log("SAY WHO");
      let xhr = new XMLHttpRequest();
      xhr.open("GET", "/utilisasi-log/php_util/insertLan.php");
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
      xhr.open("GET", "/utilisasi-log/php_util/insertWan.php");
      xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("device").innerHTML = this.responseText;
        }
      };

      xhr.send();
    });
  }

  insertFormHandler() {
    let request;
    $(document).ready(function () {
      $("#utilForm").submit(function () {
        event.preventDefault();
        console.log("sus");

        let download = $("#download").val();
        let upload = $("#upload").val();
        let date = $("#date").val();
        let idDevice = $("#device").val();
        let action = "newEntry";

        request = $.ajax({
          url: "/utilisasi-log/app/core/AJAXRouter.php",
          type: "POST",
          data: {
            download: download,
            upload: upload,
            date: date,
            idDevice: idDevice,
            action: action,
          },
          cache: false,
          success: function () {
            console.log("ASUSMOMONGUS");
          },
        });
        request.done(function (response, textStatus, jqXHR) {
          console.log("main.js success");
        });
        request.fail(function (jqXHR, textStatus, errorThrown) {
          console.error(
            "The following error occured: " + textStatus,
            errorThrown
          );
        });
      });
    });
  }

  reportFormHandler() {
    $(document).ready(function () {
      $("#utilForm").submit(function () {
        event.preventDefault();

        $("#chart").attr(
          "src",
          `/utilisasi-log/app/view/Transaction/sus.php?idDevice=${$('#device').val()}&selectedTime=${$('.timeframe:visible').val()}&range=${$('input[type=radio][name=period]:checked').val()}&year=${$('#year').val()}`
        )
        
      });
    });
  }
}
