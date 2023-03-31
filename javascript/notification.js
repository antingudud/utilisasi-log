export function popup(url, response, callback)
{
    $.ajax({
        url: `${url}/app/view/resources/components/notification.html`,
        dataType: "html",
        success: function(html){
            $('body nav').after(html);
            checkMood(response.status, $("#popup"));
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

function checkMood(status, element)
{
    const error = ["failed", "error", "exception"];
    const success = ["success", "good", "ok"];

    if(error.includes(status))
    {
        element.removeClass('bg-gray-400 border-gray-500 text-gray-700 text-black');
        element.addClass('bg-red-500 border-red-600 text-red-700 text-white');
    }
    if(success.includes(status))
    {
        element.removeClass('bg-gray-400 border-gray-500 text-gray-700 text-black');
        element.addClass('bg-green-400 border-green-500 text-green-700 text-black');
    }
}