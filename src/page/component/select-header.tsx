
import { my_cookie } from "../../library/cookie";
import { integ } from "../../rocet/core/integration";


export function Select(props:{content:Array<{filil:string, id:string}>, start?:string,  onchange?:Function}){
    let option:Array<JSX.Element> = []
    let control:JSX.Element
    let len = (props.content.length + 1)*25


    props.content.forEach((fil:any, ind:number)=>{
        if(props.start){
           if(fil.id == props.start){
            control = <control>{fil.filial}</control>
              my_cookie.select = fil.filial;
            }
        }else{
            if(ind == 1){
                control = <control>{fil.filial}</control>
                my_cookie.select = fil.filial;
            }
        }

        option.push(<li className="option" data={fil.id} onclick={clli}>{fil.filial}</li>)
    })
    
    if(props.start == 'all') control = <control>{"Все филиалы"}</control>

    function clli(evt:any){
        let value =  evt.target.textContent;
        openselect(value)
        if(props.onchange){
            if(value != 'Все филиалы'){
                props.onchange( evt.target.getAttribute('data'))
            }else{
                props.onchange('all')
            }
        }
    }

    function openselect(text:string|null = null){
        my_cookie.select = text;
       const select_block:HTMLDivElement =  document.querySelector('ul.block-select')
               select_block.classList.toggle('open')
               select_block.focus()
        if(text) select_block.parentElement.querySelector('control').textContent = text
    }

 return <div className="select-header" >
    {control}
    <ul className="block-select" style={'--h-sel: ' + len +'px'}  >
            {...option}
        <li className="option" onclick={clli}>Все филиалы</li>
    </ul>
    <span className='select-but' onclick={(evt:any)=>openselect()}>{'❯'}</span>
 </div>
}