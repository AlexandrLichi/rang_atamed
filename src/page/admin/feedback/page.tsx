// import { client } from "../../../interface/interface";
import { my_cookie } from "../../../library/cookie";
import { FDate } from "../../../library/DateClass";
import { integ } from "../../../rocet/core/integration";
import { Rocet } from "../../../rocet/core/rocet";
import { select } from "../../../rocet/core/rocet_elements";
import { transition } from "../../../router/api";
import { GET, POST } from "../../../router/Request";
import { Stars } from "../../component/block-review";
import { InputText } from "../../component/input-text";
import { loaderDot, loaderForm, noneFind } from "../../component/loader-grose";
import { news } from "../../component/news-masseng";
import { STATUS } from "../../constant/const";

import { Admin } from "../admin";
import { Header, Nav } from "../header";
import { REC } from "../request";
import './../../../CSS/client-request-feedback.scss'
import { watch } from "./watch";


interface search{
    text:string;
}

export function ReportClient(data:any){
        console.log(data);
    const context =  new Rocet('context');
    
        context.ExecAfter = [
            ()=>renderTable(true),
            ()=>select()
        ]
    
        const search:search = {
            text:""
        }



    const select = ()=>{

        let selectblock:HTMLElement = document.querySelector('.block-select')
        selectblock.onclick =()=>{
            loaderDot('client-request');
            transition("/admin/request");
        }

    }

        
        let TimeInput:any = null

        function searchText(evt:any){
             search.text = evt.target.value

           if(TimeInput) clearTimeout(TimeInput);

           TimeInput = setTimeout(()=>{
                loaderForm('table');
              GET("/admin/request", {search:search.text}).onload = function (){
                try{
                    data = JSON.parse(this.responseText)
                    console.log(data)
                    renderTable(false)
                }catch{
                    console.log(this.responseText)
                }
           
              }

           },1300)

        }




    function renderTable(bonusSetbool:boolean = true){

         if(data['client'].length == 0) return noneFind('table');
        
        const table =  new Rocet('table');

            const T:Array<JSX.Element> = [
            <tr>
                <th>Дата</th>
                <th>ФИО</th>
                <th>Телефон</th>
                <th>Cтатус</th>
                <th>Оценка</th>
                <th>Бонус</th>
                <th>Начислить</th>
                <th>Весь путь</th>
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


    function setBonus(evt:any, id:string, bonus:string){
        POST('/admin/request/set-bunus', {id:id}).then((post)=>{
           news("Вы установили статуc о начилении бонуса", false);
           const div = new Rocet(evt.target.parentElement);
           div.render(()=><div className="column center"><img src={require('./../../../images/setBonus.png')}/></div>)
        })
    }

        data['client'].forEach((row:any)=>{
          
            if(!["MACHINE", "NAME", "FAMILY", "NEGATIVE"].includes(row.path) && bonusSetbool) return;
                
            let bonusST:JSX.Element = (()=>{
                console.log(row.bonus_check)
                if(row.bonus_check == 0){

                    if(["MACHINE", "NAME", "FAMILY", "NEGATIVE"].includes(row.path)){
                        return <div className="column center"><button className="btn-url" onclick={(evt:any)=>setBonus(evt,row.id, row.bonus)}>Начислить</button></div>
                    }else{
                         return <div className="column center"><p>В ожидании</p></div>
                    }
                }else{
                    return <img src={require('./../../../images/setBonus.png')}/>
                }
            })()


            T.push(<tr className={row.path == "FAMILY"|| row.path == 'MACHINE' ||row.path == "NAME" || row.path == "NEGATIVE"?"row-green":"" }>
                <td><p>{new FDate(row['date-time'].split(' ')[0])['D Month YY']()}</p></td>
                <td><p className="min" onclick={bufer}>{row.name}</p></td>
                <td><p className="min" onclick={bufer}>{row.telefon}</p></td>
                <td><p className="path">{path(row.path, row.platform_path)}</p></td>
                <td><Stars stars={String(row.like_star)}/></td>
                <td><p>{row.bonus}</p></td>
                <td>{bonusST}</td>
                <td style={'min-width: 300px;'}>{watch(data['history'][row.id], row['date-time'], row['platform'])}</td>
            </tr>)
            

        })
       

        table.render( ()=>{
            return <table>{...T}</table>
        })

    }

    function showBonus(evt:any){
        if(evt.target.className == 'active'){
            renderTable(true);
        }else{
            renderTable(false);
        }
        evt.target.classList.toggle('active')
    }

        context.render(()=>{
            return<context>
                    <Header data={data}/>
                    <content>
                        <Nav data={data}/>
                        <content-body>
                            <div className="client-request">
                            <h1 data={data['client'].length}>Запросы отзывов</h1>
                            <br/>
                            <h2>{my_cookie.select}</h2>
                            <div className="search-block">
                                <div className="input-search">
                                    <img src={require('./../../../images/icons8-лупа-search.png')}/>
                                    <input type="text" onkeydown={searchText}/>
                                    <p>Поиск по номеру телефона, имени</p>
                                </div>

                                <div className="search-tab "><button className="" onclick={showBonus}>Показать все</button></div>
                            </div>

                            <table className="tables"></table>
                            </div>
                            <div></div>
                        </content-body>
                    </content>
                 </context>
        })
    
}