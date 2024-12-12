
function POST(url, content = {}) {

  content['token-csrf'] = document
    .querySelector("meta[name=token-csrf]")
    .getAttribute("value");

  console.log(content);
  let param = {
    method: 'POST',
    header: { 'Content-Type': 'application/json;charset=utf-8' },
    body: JSON.stringify(content),
  }

  return fetch(url, param)
}