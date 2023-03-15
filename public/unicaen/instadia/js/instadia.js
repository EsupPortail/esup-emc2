/* ================================================== Instadia ==================================================== */
$.widget("unicaen.instadia", {
    dialogElement: undefined,
    saisieElement: undefined,
    saisieBtnElement: undefined,
    messagesElement: undefined,
    notificationElement: undefined,
    informationElement: undefined,
    dernierAffichage: undefined,
    messages: [],
    inRefresh: false,
    firstRender: true,

    options: {
        refreshDelay: 2000,
        title: "Messagerie instantanée",
        userId: undefined,
        userLabel: 'Anonyme',
        userHash: undefined,
        rubrique: undefined,
        sousRubrique: undefined,
        url: undefined,
        information: undefined,
        width: 500,
        height: 700,
        readOnly: false
    },



    initOptions: function ()
    {
        var optionsKeys = {
            refreshDelay: 'refresh-delay',
            title: 'title',
            userId: 'user-id',
            userLabel: 'user-label',
            userHash: 'user-hash',
            rubrique: 'rubrique',
            sousRubrique: 'sous-rubrique',
            url: 'url',
            information: 'information',
            width: 'width',
            height: 'height',
            readOnly: 'read-only'
        };

        for (var k in optionsKeys) {
            if (typeof this.element.data(optionsKeys[k]) !== 'undefined') {
                this.options[k] = this.element.data(optionsKeys[k]);
                if (k == 'readOnly') this.options[k] = (this.options[k] == '1' || this.options[k] == 'true');
            }
        }
    },



    afficherCacher: function ()
    {
        if (this.estAffiche()) {
            this.cacher();
        } else {
            this.afficher();
        }

        return this;
    },



    afficher: function ()
    {
        this.dialogElement.dialog("open");
        this.dernierAffichage = new Date();

        var mh = this.dialogElement.height() - this.informationElement.height() - this.saisieElement.height() - 30;
        this.messagesElement.css('height', mh);

        this.__renderNotification();
        this._trigger('afficher', null, this);

        return this;
    },



    cacher: function ()
    {
        this.dernierAffichage = new Date();
        this.dialogElement.dialog("close");
        this.__renderNotification();
        this._trigger('cacher', null, this);

        return this;
    },



    estAffiche: function ()
    {
        return this.dialogElement.dialog("isOpen");
    },



    envoyer: function ()
    {
        if (this.options.readOnly) return this;

        var that = this;
        var message = this.getMessage();

        this.inRefresh = true;
        $.post(this.options.url, {
            poster: 1,
            rubrique: this.options.rubrique,
            sousRubrique: this.options.sousRubrique,
            contenu: message
        }, function (res)
        {
            that.inRefresh = false;
        });

        this.addMessage(null, new Date, message);
        this.setMessage();
        this._trigger('envoyer', message, this);

        return this;
    },



    addMessage: function (user, horodatage, content)
    {
        if (this.options.readOnly) return this;

        if (!content) return this;

        var message = {
            user: (user ? user : this.getUser()),
            horodatage: horodatage,
            contenu: content
        };

        this.messages.push(message);
        this.__renderNotification().__renderMessages();
        this._trigger('addMessage', message, this);

        return this;
    },



    getMessage: function ()
    {
        return this.saisieElement.val();
    },



    setMessage: function (message)
    {
        this.saisieElement.val(message);

        return this;
    },



    getUser: function ()
    {
        return {
            id: this.options.userId,
            label: this.options.userLabel,
            hash: this.options.userHash
        };
    },



    _create: function ()
    {
        var that = this;

        this.dernierAffichage = new Date();
        this.initOptions();

        this.__make();

        var buttons = [];
        if (!this.options.readOnly) {
            buttons.push({
                html: "<span class='fas fa-paper-plane'></span> Poster le message",
                class: "btn btn-primary",
                icons: {
                    primary: "ui-icon-heart"
                },
                click: function ()
                {
                    that.envoyer();
                }
            });
        }

        buttons.push({
            text: "Fermer",
            class: "btn btn-secondary",
            click: function ()
            {
                $(this).dialog("close");
            }
        });


        this.dialogElement.dialog({
            autoOpen: false,
            dialogClass: 'instadia-dialog',
            width: this.options.width,
            height: this.options.height,
            resizable: false,
            buttons: buttons
        });
        this.notificationElement.on('click', function ()
        {
            that.afficherCacher();
        });
        this.saisieBtnElement.on('click', function ()
        {
            that.envoyer();
        });

        this.__renderNotification().__autoRefresh();
    },



    __make: function ()
    {
        var content = '' +
            '<div class="instadia-notification"><button type="button" class="btn btn-link"></button></div>' + "\n" +
            '<div class="instadia-dialog" style="display:none" title="' + this.options.title + '">' + "\n" +
            '    <div class="instadia-messages well">Chargement des messages <span class="loading">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></div>' + "\n";

        if (!this.options.readOnly) {
            content +=
                '    <div class="instadia-saisie">' + "\n" +
                '        <textarea id="message" class="form-control" style="min-width: 100%;max-width:100%;min-height:80px"></textarea>' + "\n" +
                '    </div>' + "\n";
        }
        if (this.options.information) content += '    <div class="information">' + this.options.information + '</div>' + "\n";
        content += '</div>';
        this.element.html(content);

        this.dialogElement = this.element.find('.instadia-dialog');
        this.saisieElement = this.element.find('.instadia-dialog .instadia-saisie #message');
        this.saisieBtnElement = this.element.find('.instadia-dialog .instadia-saisie .btn');
        this.messagesElement = this.element.find('.instadia-dialog .instadia-messages');
        this.notificationElement = this.element.find('.instadia-notification .btn');
        this.informationElement = this.element.find('.instadia-dialog .information');

        return this;
    },



    __renderNotification: function ()
    {
        var nbMessages = this.messages.length;
        var newMessages = 0;

        if (!this.estAffiche()) {
            for (i in this.messages) {
                if (this.messages[i].horodatage > this.dernierAffichage) {
                    if (this.messages[i].user && this.getUser().id == this.messages[i].user.id) {
                        // pas nouveau : on l'a écrit soit-même
                    } else {
                        newMessages++;
                    }
                }
            }
        }

        var content = this.options.title + ' (' + nbMessages + ' message' + ((nbMessages > 1) ? 's' : '') + ')';
        if (newMessages > 0) {
            content += ' <span class="label label-danger" title="Vous avez ' + newMessages + ' nouveau' + ((newMessages > 1) ? 'x' : '') + ' message' + ((newMessages > 1) ? 's' : '') + '">' + newMessages + ' nouveau' + ((newMessages > 1) ? 'x' : '') + '</span>';
        }
        this.notificationElement.html(content);

        return this;
    },



    __renderMessages: function ()
    {
        var content = '';
        var droite = false;

        if (this.messages.length == 0) {
            content += 'Pas de message';
        }

        for (msgId in this.messages) {
            var message = this.messages[msgId];

            if (!message.user) message.user = {id: null, label: 'Anonyme', hash: null};

            droite = !droite;
            var defaut = !(message.user.id && this.getUser().id && message.user.id === this.getUser().id);

            content += '<div>'
                + '<div style="float:' + (droite ? 'left' : 'right') + '">'
                + '<img class="avatar" src="http://www.gravatar.com/avatar/' + message.user.hash + '?s=40" alt="photo" />'
                + '</div>'
                + '<div class="message message-' + (droite ? 'droite' : 'gauche') + ' message-' + (defaut ? 'default' : 'mine') + '">'
                + '<pre>' + message.contenu.replace("\n", "<br />") + '</pre>'
                + '<small>' + (message.user.label ? message.user.label : 'Inconnu') + ', le ' + this.__formatDate(message.horodatage) + '</small>'
                + '</div>'
                + '</div>';
        }

        var lastScrollMax = this.messagesElement[0].scrollTopMax;

        this.messagesElement.html(content);

        if (this.firstRender || lastScrollMax < this.messagesElement[0].scrollTopMax) {
            this.messagesElement.animate({scrollTop: this.messagesElement[0].scrollTopMax});
        }

        this.firstRender = false;

        return this;
    },



    __formatDate: function (date)
    {
        var jour = date.getDate();
        if (jour < 10) jour = '0' + jour;
        var mois = date.getMonth() + 1;
        if (mois < 10) mois = '0' + mois;
        var annee = date.getFullYear();
        var heure = date.getHours();
        if (heure < 10) heure = '0' + heure;
        var minute = date.getMinutes();
        if (minute < 10) minute = '0' + minute;
        var seconde = date.getSeconds();
        if (seconde < 10) seconde = '0' + seconde;

        return jour + '/' + mois + '/' + annee + ' ' + heure + ':' + minute + ':' + seconde;
    },



    __autoRefresh: function ()
    {
        var that = this;

        if (!this.inRefresh) {
            this.inRefresh = true;
            $.post(this.options.url, {
                poster: 0,
                rubrique: this.options.rubrique,
                sousRubrique: this.options.sousRubrique
            }, function (messages)
            {
                that.messages = messages;
                for (i in that.messages) {
                    that.messages[i].horodatage = new Date(that.messages[i].horodatage * 1000);
                }
                that.__renderNotification().__renderMessages();
                that.inRefresh = false;
            });
        }

        setTimeout(function ()
        {
            that.__autoRefresh();
        }, that.options.refreshDelay);

        return this;
    }
});



$(function ()
{
    WidgetInitializer.add('instadia', 'instadia');
});