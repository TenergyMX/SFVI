style_mode(localStorage.getItem("theme"));

$(".sidebar-toggle").on("click", function () {
    $("#sidebar").toggleClass("collapsed");
});

$(".dropdown-style-switcher .dropdown-item").on("click", function () {
    var theme = $(this).attr("data-theme");
    localStorage.setItem("theme", theme);
    style_mode(theme);
});

$(".step-wizard-item .progress-count").on("click", function (e) {
    $(".step-wizard-item").removeClass("current-item");
    $(this).closest(".step-wizard-item").addClass("current-item");
    var tabId = $(this).attr("id");
    if (tabId.includes("-tab")) {
        $("#" + tabId).tab("show");
    }
});

function style_mode(style = "light") {
    const $themeCss = $(".js-stylesheet");
    const $body = $("body");

    switch (style) {
        case "dark": {
            $body.attr("data-theme", "dark");
            $themeCss.attr("href", RUTA_URL + "css/dark.css");
            break;
        }
        case "light": {
            $body.attr("data-theme", "default");
            $themeCss.attr("href", RUTA_URL + "css/light.css");
            break;
        }
        default: {
        }
    }
}
