$(function () {

    tinymce.init({
        selector: '.type2',
        plugins: 'lists',
        toolbar: 'newdocument undo redo | bold italic | bullist',
        browser_spellcheck : true,
        language: 'fr_FR',
        statusbar: false,
        resize: false,
        branding: false,
        menu: {},
        setup: function (editor) {
            editor.on("a", function () {
                if (this.inError) {
                    alertFlash("Le champ n'a PAS ETE enregistré !", 'error', 3000);
                    return;
                }

                var id = this.id;
                var tmce = tinyMCE.get(id);
                var content = tmce.getContent();
                console.log(content);
                var elt = $('#'+id);

                $.post(elt.data('url'), {contenu: content, type: 'type2'}).done(function (res) {
                    console.log(res.displayContent);
                    tmce.setContent(res.displayContent);
                    alertFlash('Le champ a bien été enregistré', 'success', 1000);
                });
            })
        }
    });

    $('.info-icon').popover({
        html: true
    })

});