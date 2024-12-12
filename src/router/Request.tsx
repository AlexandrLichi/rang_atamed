import { my_cookie } from "../library/cookie";


export function GET(url:string, body:{[name:string]:any} = null, cache:boolean = false ){
    
    let response = ''

    if(body){

        response = '?'

            Object.keys(body).forEach((key:string)=>{
                if(typeof  body[key] == 'string'){
                    response += key + "=" + body[key]+"&";
                }else{
                    response += key + "=" + JSON.stringify(body[key])+"&";
                }
            })
    }

    const request:XMLHttpRequest = new XMLHttpRequest()

    request.open('GET', url + response, true)

    if(cache == false) request.setRequestHeader("Cache-Control", "no-cache, no-store, max-age=0");

    request.setRequestHeader("Content-type", "application/json");
    request.send();

    return request
}

export function POST(url:string, body:{[name:string]:any} = {}, method ="POST" , LS:boolean = false ):Promise<any>
{
    let token =  document.querySelector('meta[name=token-csrf]').getAttribute('data');
    console.log(token);
    body['token-csrf'] = token;

    const request:XMLHttpRequest = new XMLHttpRequest()

    request.open(method , url, true)
    request.setRequestHeader("Content-type", "application/json");
   
    let sends = null;
    try{
        sends = JSON.stringify(body)
        request.send(sends);
    }catch(e){

    }

    return new Promise((resolve, reject)=>{
    let key = ''
    if(LS) key = JSON.stringify({url:url, body:JSON.stringify(body)});

        request.onload = function(){
            if(this.status == 200){
                try{
                    let data = JSON.parse(this.responseText);
                    if(LS) localStorageW(key,this.responseText, 'PUT')
                    return   resolve(data)
                }catch(e){
                      if(LS) localStorageW(key,this.responseText, 'PUT')
                      resolve(this.responseText)
                }
                console.log(this.responseText);
                
            }

            if(this.status == (400||404)){
                document.querySelector('body').outerHTML = this.responseText
            }
            debag(this, method);
        }
    })
   

}

export function DELETE(url:string, body:any,):Promise<any>{
   return POST(url, body, "DELETE")
}

export function PUT(url:string, body:any,):Promise<any>{
   return POST(url, body, "PUT")
}


export async function POSTF(directory:string, body:any, files:{[name:string]:File}|null = null ){
     let token =  document.querySelector('meta[name=token-csrf]').getAttribute('data');
        console.log(token);
         body['token-csrf'] = token;
     const FromData = new FormData();
     FromData.append('token-csrf',token)

    Object.keys(files).forEach((name:string)=>{
        FromData.append(name, files[name])
    })


      Object.keys(body).forEach((post:any)=>{
        if(typeof body[post] == 'object'){
             FromData.append(post,JSON.stringify(body[post]))
        }else{
             FromData.append(post,body[post])
        }
      })
    
     const request = await fetch(directory,{
          method:'POST',
          body:FromData,
          redirect:'follow'
     })
     
     return request
}

function debag(xhr:XMLHttpRequest, method:string){
   let deb = document.querySelector('div[class=debag]')
const text = '<div><p>status:'+xhr.status+'; method:'+method+';</p>'+ xhr.responseText + '</div>'
   if(deb){
        deb.className = 'debag'
        deb.innerHTML = text
   }else{
       let newdeb = document.createElement('div')
            newdeb.className = 'debag'
            newdeb.innerHTML = text
            document.body.append(newdeb)
   }
}


function localStorageW(key:any, data:any = null, type:string = 'GET'):Promise<any>
{
return new Promise((res, rej)=>{
    sha256(JSON.stringify(key)).then((hash:any)=>{
        if(type == 'GET'){
            localStorage[hash]? res(JSON.parse(localStorage[hash])):rej('none GET key');
        }
         if(type == 'PUT'){
            localStorage[hash] = JSON.stringify(data) 
            res('ok')
        }
   });
}); 
}

async function sha256(source:string) {
    const sourceBytes = new TextEncoder().encode(source);
    const digest = await crypto.subtle.digest("SHA-256", sourceBytes);
    const resultBytes = [...new Uint8Array(digest)];
    return resultBytes.map(x => x.toString(16).padStart(2, '0')).join("");
}

// localStorageW('key',{'hi':'watch'}, 'PUT').then((data)=>{
//     console.log(data)
// })  
// localStorageW('key',null, 'GET').then((data:any)=>{
//     console.log(data)
// })