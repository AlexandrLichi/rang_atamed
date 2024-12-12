document.querySelector("#block-confirm-btn").onclick = function () {
  document.querySelector("#block-confirm").classList.toggle("disabled");
  document.querySelector("#block-text").classList.toggle("disabled");
};

document.querySelector("#block-text-btn").onclick = function () {
  let text = document.querySelector("textarea").value;
  POST(window.location.href, {
    text_negative: text,
  }).then((response) => {
    response.text().then((data) => {
      document.querySelector("#block-text").classList.toggle("disabled");
      document.querySelector("#block-finish").classList.toggle("disabled");
    });
  });
};
