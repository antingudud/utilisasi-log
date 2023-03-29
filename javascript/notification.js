export function popup(url, response)
{
    $.ajax({
        url: `${url}/app/view/resources/components/notification.html`,
        dataType: "html",
        success: function(html){
            $('body').append(html);
            $('#popup-topbar').text(response.action);
            $('#popup-content').text(response.message);
        },
        error: function()
        {
            console.error("Error fetching notification")
        }
    })
}