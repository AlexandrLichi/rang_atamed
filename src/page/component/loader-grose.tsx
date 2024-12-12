export function loaderDot(classEl:string){
    let el = document.querySelector('.' + classEl)
    if(el) return el.innerHTML = '<content-body><loader-container><loader-grose></loader-grose></loader-container></content-body>';
    el = document.querySelector(classEl)
    if(el) return el.innerHTML = '<content-body><loader-container><loader-grose></loader-grose></loader-container></content-body>';
}
export function noneFind(classEl:string){
     let el = document.querySelector('.' + classEl)
    if(el) return el.innerHTML = '<loader-container><img src="/view/images/none-find.png" /><br> <p>Не найдено</p></loader-container>';
    el = document.querySelector(classEl)
    if(el) return el.innerHTML = '<loader-container><img src="/view/images/none-find.png" /><br><p>Не найдено</p></loader-container>';
}
export function loaderForm(classEl:string){
    let el = document.querySelector('.' + classEl)
    if(!el) el = document.querySelector(classEl)

    return el.innerHTML = '<loader-container><loader-grose></loader-grose></loader-container>';
}