
import { GET } from "./Request";
// import { PAGES } from "./Router";

declare var InertiaRequest:any
// console.log(InertiaRequest);

import {PAGES} from "./Router";


export function startReact(){
    try{
        const page:any = document.querySelector('meta[name=inertia]').getAttribute('data')
        if(PAGES[page])  return PAGES[page](InertiaRequest)
            console.error(`Error key in Router: ${page} pages `)
    }catch(e){
        document.body.innerHTML = `<div class="debag">${e}</>`
    }
}



export function Router(url:string){
    
    
  GET(url, null, true).onload  = function(){

            console.log("GET-ROUTER");

            let page = this.getResponseHeader("Inertia")
            
            let token = this.getResponseHeader('x-csrf-token');
            document.querySelector('meta[name=token-csrf]').setAttribute('data', token);

            if(PAGES[page]) return PAGES[page](JSON.parse(this.responseText))
             window.location.href = url;
             console.error(`Error key in Router: ${page} pages `)

        }
}







document.addEventListener('click', (e:any) =>{

     if(e.target.tagName == 'A'){
        transition(e.target.href)
        e.preventDefault()
     }

})



export function handleLocation(){
    Router(window.location.href) 
}

export function transition(path:string){
    window.history.pushState({},'', path)
    handleLocation()
}
window.onpopstate = handleLocation
