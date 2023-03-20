export class FormHandler
{
    form;
    actionUrl;

    constructor(formId, actionUrl)
    {
        this.form = document.getElementById(formId);
        this.actionUrl = actionUrl;
        this.form.addEventListener('submit', this.handleForm.bind(this));
    }

    handleForm(e)
    {
        e.preventDefault();
        const formData = new FormData(this.form);
        const data = {}
        for (let [key, value] of formData.entries()) {
            if (data.hasOwnProperty(key)) {
                if (!Array.isArray(data[key])) {
                data[key] = [data[key]];
                }
                data[key].push(value);
            } else {
                data[key] = value;
            }
        }
        
        $.ajax({
            type: 'POST',
            url:this.actionUrl,
            data: {data: data},
            success: function(response)
            {
                console.log(response);
                this.form.reset();
            }.bind(this),
            error: function(xhr, status, response)
            {
                console.log(response),
                this.form.reset();
            }.bind(this)
        })
    }
}