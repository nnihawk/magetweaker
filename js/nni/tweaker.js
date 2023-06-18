class TweakerInfo {
    getUserAgent() {
        return window.navigator.userAgent;
    }

    getAppVersion() {
        return window.navigator.oscpu;
    }

    getCodeName() {
        return window.navigator.appCodeName;
    }

    getJqueryInfo() {
        if (typeof jQuery == 'undefined') {
            return '>> No jQuery installed <<';
        } else {
            return jQuery().jQuery;
        }
    }

    getPrototypeInfo() {
        if (typeof Prototype == 'undefined') {
            return '>> No Prototype installed <<';
        } else {
            return Prototype.Version;
        }
    }

    decorateInfoTable(tableId) {
        let table = document.getElementById(tableId);
        this.createTableRowElem(table, 'User-Agent', this.getUserAgent());
        this.createTableRowElem(table, 'Environment', this.getAppVersion());
        this.createTableRowElem(table, 'Code-Name', this.getCodeName());
        this.createTableRowElem(table, 'jQuery', this.getJqueryInfo());
        this.createTableRowElem(table, 'Prototype', this.getPrototypeInfo());
    }

    createTableRowElem(tableElem, label, value) {
        let tr = document.createElement('tr');
        let tdLabel = document.createElement('td');
        tdLabel.innerHTML = label + ":";
        let tdValue = document.createElement('td');
        tdValue.innerHTML = value;
        tr.appendChild(tdLabel);
        tr.appendChild(tdValue);
        tableElem.appendChild(tr);
    }
}

function clearEmailQueue(url)
{
    if (confirm("Really clear all emails in queue-list?")) {
        window.location.href = url;
    }
}


function showEmailMessage(messageId, url)
{
    popupWindow = window.open(url + 'message_id/' + messageId,'Message: ' + messageId,
        'height=500,width=800,left=100,top=100,resizable=yes,scrollbars=yes,' +
        'toolbar=yes,menubar=no,location=no,directories=no, status=yes');
    popupWindow.addEventListener('DOMContentLoaded', function () {
        popupWindow.document.title = "Queue-Message: " + messageId;
    });
}

function createToTopButton()
{
    function updateToTopButton()
    {
        let windowScrollY = window.scrollY;
        let treshold = windowScrollY / document.documentElement.scrollHeight * 100;
        if (treshold >= 5) {
            button.classList.add('show');
        } else {
            button.classList.remove('show');
        }
    }

    let button = document.createElement('div');
    button.classList.add('to-top-button');
    button.onclick  = function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    document.querySelector('body').append(button);
    document.addEventListener("scroll", function(){
        updateToTopButton();
    });

    updateToTopButton();
}

document.addEventListener('DOMContentLoaded', function() {
    //--- show hints for system-config options
    document.querySelectorAll('.tweaker-hint').forEach(hint => {
        hint.addEventListener('click', function(e) {
            if (e.target === hint.querySelector('.tweaker-hint-content')) {
                return;
            }
            document.querySelectorAll('.tweaker-hint.opened').forEach(hintOpened => {
                if (hint !== hintOpened) {
                    hintOpened.classList.remove('opened');
                }
            });
            hint.classList.toggle('opened');
        })
    })

    //--- create scroll-to-top button
    if (typeof tweakerAllowScrollToTop !== 'undefined' && tweakerAllowScrollToTop) {
        createToTopButton();
    }
})
