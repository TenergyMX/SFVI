style_mode(localStorage.getItem("theme"));

$(".sidebar-toggle").on("click", function () {
    $("#sidebar").toggleClass("collapsed");
});

$(".dropdown-style-switcher .dropdown-item").on("click", function () {
    var theme = $(this).attr("data-theme");
    localStorage.setItem("theme", theme);
    style_mode(theme);
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
