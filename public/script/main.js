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

  deleteUpdate(target) {
    let request;

    $(document).ready(function () {
      console.log("iamsus")
      console.log(target);
      $(target).submit(function (e) {
        e.preventDefault();
        // Update
        if (e.originalEvent["submitter"]["id"] == "buttonViewUpdate") {
          let idValues = [];
          
          $.each($("input[type=checkbox]:checked"), function () {
            idValues.push(this.value);
          });

          if (idValues.length > 0){
            console.log(`I am updating ${idValues.length} thing(s)`);
            let action = "updateEntry";

            request = $.ajax({
              url: "/util/core/AJAXRouter.php",
              type: "POST",
              data: {
                id: idValues,
                action: action,
              },
              cache: false,
              success: function (response) {
                console.log("Updated");
                $(target).load(
                  "/util/app/view/Transaction/update.php #editForm", {$_POST: response}, function () {
                new InPageFunct().deleteUpdate("#editForm");
                  }
                );
                console.log(response)
              },
              error: function(){
                console.log("Error at updating.");
              },
            });
          }
          else {
            alert(`Nothing to update.`);
          }
        } 
        // Delete
        else if (e.originalEvent["submitter"]["id"] == "buttonViewDelete") {
          let idValues = [];

          let action = "deleteEntry";

          $.each($("input[type=checkbox]:checked"), function () {
            idValues.push(this.value);
          });

          if (idValues.length > 0) {
            request = $.ajax({
              url: "/util/core/AJAXRouter.php",
              type: "POST",
              data: {
                id: idValues,
                action: action,
              },
              cache: false,
              success: function () {
                console.log("Deleted");
                $(target).load(
                  "/util/app/view/Transaction/index.php #indexViewTable"
                );
              },
              error: function(){
                console.log("Error at deleting.");
              },
            });
          } 
          
          else {
            alert("Nothing to delete.");
          }
        }
        
        // Update form
        else if (e.originalEvent["submitter"]["id"] == "buttonSubmitEdit") {
          alert("I AM aaa");
          let idValues = [];
          let action = "submitEditEntry"
          let download = [];
          let upload = [];

          for (let i = 0; i < $(":input[type=number][name='download']").length; i++) {
            download.push( $(`#download${i}`).val() )
            upload.push( $(`#upload${i}`).val() )
            idValues.push( $(`#id${i}`).val() )
          }
          console.log(download);
          console.log(upload);
          console.log(idValues)


          request = $.ajax({
            url: "/util/core/AJAXRouter.php",
            type: "POST",
            data: {
              idTrx: idValues,
              download: download,
              upload: upload,
              action: action,
            },
            cache: false,
            success: function () {
              console.log("sdsd");
            },
          });
        }


        else {
          console.log(
            `ERR! ID: ${e.originalEvent["submitter"]["id"]} not found!`
          );
          return false;
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

        let asus = $("table tr:nth-child(2)");
        console.log(asus);

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

        $("#viewTableForm").load(
          "/util/app/view/Transaction/index.php #indexViewTable"
        );

      });
    });
  }
}
