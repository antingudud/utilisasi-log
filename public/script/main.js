class InPageFunct {
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

  toggleAction() {
    let request;
    let buttonPressed;
    $(document).ready(function () {
      $("#viewTableForm").submit(function () {
        event.preventDefault();
        console.log("NEW DELETION");
        let s = 1;
        let idValues = [];

        let action = "deleteEntry";

        console.log(buttonPressed);

        $.each($("input[type=checkbox]:checked"), function () {
          console.log(this);
          idValues.push(this.value);
        });

        console.log(idValues);
        if (idValues.length > 0) {
          request = $.ajax({
            url: "/util/core/AJAXRouter.php",
            type: "POST",
            data: {
              id: idValues,
              action: action,
            },
            cache: false,
            success: function (response) {
              console.log("Deleted");
              $('#indexViewTable').html(response);
            },
          });
        } else {
          alert("Array is empty.");
        }
      });
    });
  }

  loadTo() {}

  formHandler() {
    let request;
    $(document).ready(function () {
      $("#utilForm").submit(function () {
        event.preventDefault();
        console.log("sus");

        let asus = $('table tr:nth-child(2)');
        console.log(asus)

        let download = $("#download").val();
        let upload = $("#upload").val();
        let idDevice = $("#device").val();
        let action = "newEntry";

        request = $.ajax({
          url: "/util/core/AJAXRouter.php",
          type: "POST",
          data: {
            download: download,
            upload: upload,
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

  confirmBox($message) {
    return alert(`Are you sure you want to ${$message}?`);
  }

  checkboxCheck() {
    const checkbox = document.getElementById;
  }
}

class Misc {
  tellTime() {
    let today = new Date();
    let currentHour = today.getHours();
    let whatTimeIsIt;

    if (currentHour <= 10) {
      whatTimeIsIt = "morning";
    } else if (currentHour <= 15) {
      whatTimeIsIt = "afternoon";
    } else if (currentHour <= 18) {
      whatTimeIsIt = "evening";
    } else {
      whatTimeIsIt = "night";
    }
    return whatTimeIsIt;
  }
}

class Table {
  refreshTable() {
    let request;
    $(document).ready(function () {
      $("#refreshViewIndex").click(function () {
        event.preventDefault();

        let action = "refreshTable";

        request = $.ajax({
          url: "/util/php_util/tableForRefresh.php",
          type: "POST",
          data: {
            action: action,
          },
          cache: false,
          success: function (response) {
            $("#indexViewTable").html(response);
          },
        });
      });
    });
  }
}
