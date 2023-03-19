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
        const data = Object.fromEntries(formData.entries())
        
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