document.querySelectorAll("#platform-button").forEach((button) => {
    button.onclick = clickPlatform;
});

function clickPlatform(evt) {

    let el = evt.target;
    if (el.tagName == "IMG") el = el.parentElement;
    if (el.tagName == "B") el = el.parentElement;
    let platform = el.getAttribute("data");
    document.querySelector("#block-platform").classList.toggle("disabled");
    document.querySelector("#block-confirm").classList.toggle("disabled");

    let bt = document.querySelector("#button-confirm");

    bt.textContent += " " + el.getAttribute("dataname");;
    bt.onclick = (evt) => sendPlatformPositive(evt, platform);
}

function sendPlatformPositive(evt, platform) {
    POST(window.location.href, {
        platform_path: platform,
    }).then((response) => {
        response.text().then((data) => {
            // console.log(data);
            window.location.href = data;
        });
    });
}
