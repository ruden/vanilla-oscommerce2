const cookieBar = function () {
  const datasetCookieGroups = document.querySelector('button[data-cookie-groups]').dataset.cookieGroups;
  const cookieGroups = new Array(datasetCookieGroups);
  const documentCookie = document.cookie.replace(/(?:(?:^|.*;\s*)cookieAccepted\s*\=\s*(\[^\]*).*$)|^.*$/, "$1") || [];
  const setCookies = new Set(documentCookie);

  const addChecked = function (group, el) {
    if (documentCookie.indexOf(group) !== -1) {
      el.checked = true;
    }
  }

  const addEvent = function () {
    cookieGroups.forEach(function (group) {
      const el = document.getElementById('cookies-' + group);

      if (el) {
        addChecked(group, el);

        el.addEventListener('click', function (e) {
          if (e.target.checked) {
            setCookies.add(group);
          } else {
            setCookies.delete(group);
          }

          createCookie();
        })
      }
    });

    addEventDisableAll();
    addEventAllowAll();
    addEventSaveChanges();
  }

  const addEventDisableAll = function () {
    const disableAll = document.getElementById('cookies-disable-all');

    disableAll.addEventListener('click', function () {
      createCookie(Array());
    })
  }

  const addEventAllowAll = function () {
    const allowAll = document.getElementById('cookies-allow-all');

    allowAll.addEventListener('click', function () {
      createCookie(cookieGroups);
    })
  }

  const addEventSaveChanges = function () {
    const saveChanges = document.getElementById('cookies-save-changes');

    saveChanges.addEventListener('click', function () {
      createCookie();
    })
  }

  const createCookie = function (cookie) {
    if (cookie === undefined) {
      cookie = [...setCookies];
    }

    document.cookie = 'cookieAccepted=' + JSON.stringify(cookie) + ';path=/;expires=Fri, 31 Dec 2038 23:59:59 GMT; SameSite=Lax;' + (location.protocol === 'https:' ? 'Secure' : '');
  }

  return {
    init: function () {
      addEvent();
    }
  }
}();

cookieBar.init();