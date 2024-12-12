import { integ } from "../../rocet/core/integration";
import '../../CSS/component/toggle.scss'
export function Toggle(props:{tog:boolean, name:string, onclick?:Function}){
    
function onTogg(evt:any){
        evt.target.classList.toggle("out")
       
        if(evt.target.classList.contains('out')){
            evt.target.setAttribute('value', "0")
        }else{
              evt.target.setAttribute('value', "1")
        }
        if(props.onclick){
            props.onclick(evt)
        }
    }
    return<span className={props.tog?"toggle":"toggle out"} onclick={onTogg} name={props.name} value={props.tog?"1":"0"}></span>
}