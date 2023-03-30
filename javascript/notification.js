export function popup(url, response, callback)
{
    $.ajax({
        url: `${url}/app/view/resources/components/notification.html`,
        dataType: "html",
        success: function(html){
            const MOOD = checkMood(response.status);
            html = html.replace(/gray/g, MOOD);
            $('body nav').after(html);
            $('#popup-topbar').text(capitalizeFirstLetter(response.action));
            $('#popup-content').text(capitalizeFirstLetter(response.message));

            $("#popup").hide();
            $("#popup").fadeIn(150, function() {
                $("#popup").delay(3000).fadeOut('slow', function () {
                    $(this).remove();
                })
            })
            if(callback)
            {
                callback(response);
            }
        },
        error: function()
        {
            console.error("Error fetching notification")
        }
    })
}
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function checkMood(status)
{
    const error = ["failed", "error", "exception"];
    const success = ["success", "good", "ok"];
    if(error.includes(status))
    {
        return "red";
    }
    if(success.includes(status))
    {
        return "green";
    } else
    {
        return "gray";
    }
}