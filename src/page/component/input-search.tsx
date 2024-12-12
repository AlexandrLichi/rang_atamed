import { integ } from "../../rocet/core/integration";
import '../../CSS/component/input-search.scss'


export function InputSearch(props:{onkey:(this: GlobalEventHandlers, ev: KeyboardEventInit) => any, text?:string}){
   function blur(evt:any){
    evt.target.parentElement.classList.toggle('input-hover')
   }

   function focus(evt:any){
     evt.target.parentElement.classList.toggle('input-hover')
   }
   


    return <div className={"input-search"}>
        <img src={require('../../images/icons8-лупа-search.png')}></img>
        <input type="text" autofocus={props.text? "autofocus":""}  value={props.text? props.text:""} onkeyup={props.onkey} onblur={blur} onfocus={focus}></input>
        <p>Найдите отзыв по фрагменту текста или имени автора</p>
    </div>
}