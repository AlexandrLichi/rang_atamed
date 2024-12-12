import { POST_MY } from "../../library/GetPost";
import { IsJsonString } from "../../library/json";

export async function REC(name:string, body:{[name:string]:any}|null|undefined, callback:Function){
    if(body == (null||undefined)){ body = {'ok':'ok'}}
    
     POST_MY('./PHP/admin/request.php', name, JSON.stringify(body)).onload = function(){
        if(IsJsonString(this.responseText)){
            callback(JSON.parse(this.responseText))
        }else{
            console.log(this.responseText)
        }

     }
}

export async function RECTEH(name:string, body:{[name:string]:any}|null|undefined, callback:Function){
    if(body == (null||undefined)){ body = {'ok':'ok'}}
    
     POST_MY('./PHP/tech-cab/request-tech.php', name, JSON.stringify(body)).onload = function(){
        if(IsJsonString(this.responseText)){
            callback(JSON.parse(this.responseText))
        }else{
            console.log(this.responseText)
        }

     }
}

export async function RECCLIENT(name:string, body:{[name:string]:any}|null|undefined, callback:Function){
    if(body == (null||undefined)){ body = {'ok':'ok'}}
    
     POST_MY('./PHP/client/request-client.php', name, JSON.stringify(body)).onload = function(){
        if(IsJsonString(this.responseText)){
            callback(JSON.parse(this.responseText))
        }else{
            console.log(this.responseText)
        }

     }
}