
    const goBack = document.querySelector("[data-go-back]");
    if (goBack) {
        goBack.addEventListener("click", function (e) {
            e.preventDefault();
            window.history.back();
        });
    }
    const goForward = document.querySelector("[data-go-forward]");
    if (goForward) {
        goForward.addEventListener("click", function (e) {
            e.preventDefault();
            window.history.forward();
        });
    }
    const reload = document.querySelector("[data-reload]");
    if (reload) {
        reload.addEventListener("click", function (e) {
            e.preventDefault();
            window.location.reload();
        });
    }
    const openNewWindow = document.querySelector("[data-open-new-window]");
    if (openNewWindow) {
        openNewWindow.addEventListener("click", function (e) {
            e.preventDefault();
            window.open();
        });
    }
    const closeWindow = document.querySelector("[data-close-window]");
    if (closeWindow) {
        closeWindow.addEventListener("click", function (e) {
            e.preventDefault();
            window.close();
        });
    }
    

    