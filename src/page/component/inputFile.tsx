import { integ } from "../../rocet/core/integration";
import { NAME } from "../../rocet/core/rocet";
import './../../CSS/component/inputFile.scss'

export function InputFile(props:{value:null|string,
                                 onchanges:Function , 
                                 placeholder?:string, 
                                 name?:string
                                }){
                                    
    function openLoadFile(evt:any){
       evt.target.parentElement.children[1].click()
    }
    
    function inputChange(evt:any){
        let file:File = evt.target.files[0]
        if(file){
            evt.target.parentElement.children[0].textContent = file.name
        }
        if(typeof props.onchanges == 'function' )
        props.onchanges(file)
    }
    
    return<div className="file">
        <b onClick={openLoadFile} >{props.value == ""?props.placeholder:props.value}</b>
        <input type="file" name={props.name?props.name:""} onchange={inputChange}></input>
    </div>
}