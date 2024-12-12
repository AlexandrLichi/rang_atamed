import { integ } from "../../rocet/core/integration";
import '../../CSS/component/input-text.scss'
import { Rocet } from "../../rocet/core/rocet";

export function InputText(props:{name:string, placeholder:string, onclickRefresh?:Function, onExamination?:Function, newInput?:boolean,  disabled?:boolean, id?:string, value?:string , style?:string }){

    function add(evt:any){
        const el = evt.target 
        el.classList.toggle('deactive')
        const div = el.parentElement
        const newIn = document.createElement('div')
        const count = document.querySelectorAll('.new-input').length
        newIn.className = "input-text"
        newIn.setAttribute('id', "new-input-" + count)
        div.insertAdjacentElement('afterend',  newIn);
        const inp = new Rocet(newIn.id)
        inp.render(()=>{
           let name = props.name.split('-')[0];
            return<InputText name={name + '-' + count} placeholder={props.placeholder} newInput={true} id={newIn.id}></InputText>
        })
    }
    
    props.onExamination = function (evt:any , data:string, error = false ){
        console.log(evt, data)
    }

    return <div className="input-text" id={props.id?props.id:''} style={props.style?props.style:''}>
        <input  type="text" name={props.name} placeholder={props.placeholder} disabled={props.disabled}
        value={props.value?props.value:''}></input>
        <img  className={props.onclickRefresh?'':'deactive'} src={require('../../images/icons8-refresh-gray.png')} onclick={(evt:any)=>{evt.preventDefault(); props.onclickRefresh(evt)}}></img>
        <img  className={props.newInput?'new-input':'deactive'} src={require('../../images/icons8-add-gray.png')} onclick={add}></img>
    </div>
}