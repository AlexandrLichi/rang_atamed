import { integ } from "../../rocet/core/integration";
import { Rocet } from "../../rocet/core/rocet";
import { RECTEH } from "../admin/request";
import '../../CSS/logs.scss'
import { Header, Nav } from "../admin/header";
import { HeaderTech, NavTech } from "./Header";
import { PUT } from "../../router/Request";
import { news } from "../component/news-masseng";
import { transition } from "../../router/api";


export function Logs(data:any){
    const context = new Rocet('context');

        context.ExecAfter = [
            ()=>{
                context.Element.querySelector('.butt-block').querySelector('button').click()
            }
        ]

        console.log(data);
        const BUTT:Array<JSX.Element> = [];
        Object.keys(data['LOG']).forEach((nameError:string)=>{
            BUTT.push(<button className=""  onclick={(evt:any)=>playError(evt, data['LOG'][nameError])} id="but">{nameError}</button>)
        })

        function playError(evt:any, data:any){
            const error = new Rocet('content-error');
            let collection  = evt.target.parentElement.children
            for(let i= 0; i < collection.length; i++){
               collection[i].className = ''
            }
        
            
             evt.target.classList.add('btn-focus')

            let P:Array<JSX.Element> = [];
            data.reverse().forEach((text:string)=> {
              P.push(<p>{text}</p>)
            });
            error.render(()=>{
                return <div className="content-error">{...P}</div>
            })
          
        }
        function clearLogs(){
            PUT('/tech/logs', {}).then((data)=>{
                transition(window.location.href);
                news('Логи очищены', false);
            })
        }


        context.render(()=>{
            return <context>
                <HeaderTech data={data} />
                    <content>
                        <NavTech data={data}/>
                        <content-body>
                            <div className="logs">
                                <h1>Лог Ошибок</h1>
                                <button className="clear-logs" onclick={clearLogs}>очистить логи</button>
                                <div className="butt-block">{...BUTT}</div>
                                <div className="content-error"></div>
                            </div>
                        </content-body>
                    </content>
                </context>
        })


    }