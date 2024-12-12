import { FDate } from "../../../library/DateClass";
import { integ } from "../../../rocet/core/integration";
import { Rocet } from "../../../rocet/core/rocet";
import { Stars } from "../../component/block-review";
import { noneFind } from "../../component/loader-grose";
import { news } from "../../component/news-masseng";
import { STATUS } from "../../constant/const";
import { HeaderTech, NavTech } from "../Header";

export function TechClient(data:any){
    const context = new Rocet('context');
    context.ExecAfter = [
        ()=>renderTable(),
    ]

function renderTable(){

         if(data['client'].length == 0) return noneFind('table');
        const table =  new Rocet('table');

            const T:Array<JSX.Element> = [
            <tr>
                <th>Дата</th>
                <th>ФИО</th>
                <th>Филиал</th>
                <th>Телефон</th>
                <th>Cтатус</th>
                <th>Оценка</th>
                <th>Бонус</th>
                <th>Текст</th>
            </tr>
        ]

    function path(status:string|null, pl:string|null = null):string
    {
       STATUS['platform'] = pl;
        return STATUS[status]? STATUS[status]:"Не начинал"
    }


    function bufer(evt:any){
        let text = evt.target.textContent

        navigator.clipboard.writeText(text).then(()=>{
            news("Скопированно в буфер", false);
        });

    }


    function open_mess(evt:any){
        evt.target.classList.toggle('open')
    }

        data['client'].forEach((row:any)=>{
            T.push(<tr className={row.path == "FAMILY"|| row.path == 'MACHINE' ||row.path == "NAME"?"row-green":"" }>
                <td><p>{new FDate(row['date-time'].split(' ')[0])['D Month YY']()}</p></td>
                <td><p className="min"  style={'width: min-content;'} onclick={bufer}>{row.name}</p></td>
                <td><p className="min"  style={'width: min-content;'} >{row.filial}</p></td>
                <td><p className="min" onclick={bufer}>{row.telefon}</p></td>
                <td><p className="path" style={'width: min-content;'}>{path(row.path, row.platform_path)}</p></td>
                <td><Stars stars={String(row.like_star)}/></td>
                <td><p>{row.bonus}</p><button className="btn-url">Начислить</button></td>
                <td style={'min-width: 300px'}><p className="text" onclick={open_mess}>{row.text_negative}</p></td>
            </tr>)
        })
       

        table.render( ()=>{
            return <table>{...T}</table>
        })

    }

    context.render(()=>{
        return <context>
             <HeaderTech/>
            <content>
                 <NavTech data={data}/>
                <content-body>
                    <div className="client-request">
                    <h3>Таблица всех запросов</h3>
                    <table></table>
                    </div>
                </content-body>
            </content>
        </context>
    })

}