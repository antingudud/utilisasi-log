class InPageFunct {
  deleteUpdate(target) {
    let request;

    $(document).ready(function () {
      $(target).submit(function (e) {
        e.preventDefault();
        // Update
        if (e.originalEvent["submitter"]["id"] == "buttonViewUpdate") {
          let idValues = [];

          $.each($("input[type=checkbox]:checked"), function () {
            idValues.push(this.value);
          });

          if (idValues.length > 0) {
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
                  "/util/app/view/Transaction/update.php #editForm",
                  { $_POST: response },
                  function () {
                    new InPageFunct().deleteUpdate("#editForm");
                  }
                );
                console.log(response);
              },
              error: function () {
                console.log("Error at updating.");
              },
            });
          } else {
            alert(`Nothing to update.`);
          }
        }
        // Delete
        else if (e.originalEvent["submitter"]["id"] == "buttonViewDelete") {
          if (new InPageFunct().confirmBox("delete") == true) {
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
                error: function () {
                  console.log("Error at deleting.");
                },
              });
            } else {
              alert("Nothing to delete.");
            }
          }
        }

        // Update form
        else if (e.originalEvent["submitter"]["id"] == "buttonSubmitEdit") {
          alert("I AM aaa");
          let idValues = [];
          let action = "submitEditEntry";
          let download = [];
          let upload = [];

          for (
            let i = 0;
            i < $(":input[type=number][name='download']").length;
            i++
          ) {
            download.push($(`#download${i}`).val());
            upload.push($(`#upload${i}`).val());
            idValues.push($(`#id${i}`).val());
          }
          console.log(download);
          console.log(upload);
          console.log(idValues);

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
        } else {
          console.log(
            `ERR! ID: ${e.originalEvent["submitter"]["id"]} not found!`
          );
          return false;
        }
      });
    });
  }

  confirmBox($message) {
    return confirm(`Are you sure you want to ${$message}?`); 
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
    $(document).ready(function () {
      $("#refreshViewIndex").click(function () {
        event.preventDefault();

        $("#viewTableForm").load(
          "/util/app/view/Transaction/index.php #indexViewTable"
        );
      });
    });
  }

  changeLook()
  {
    $(document).ready(function () 
    {
      $("#alternateTableLook").click(function () 
      {
        event.preventDefault();
        
        let request;
        request = $.ajax({
          url: "/util/core/AJAXRouter.php",
          type: "POST",
          data: {
            action: "showView"
          },
          cache: false,
          success: function (response) {
            console.log("Look changed");
            $("#viewTableForm").load(
              "/util/app/view/Transaction/alternate.php",
              {$_POST: response}
            );
          },
          error: function () {
            console.log("Error at changing look.");
          },
        });
      })
    }
    )
  }
}
