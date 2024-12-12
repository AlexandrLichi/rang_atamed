
import { FDate } from "../../library/DateClass";
import { GET_MY } from "../../library/GetPost";
import { integ } from "../../rocet/core/integration";
import { NAME, Rocet } from "../../rocet/core/rocet";
import { RECCLIENT } from "../admin/request";
import { InputText } from "../component/input-text";
import './../../CSS/send-client.scss'


export function SendClient(){

    const context = new Rocet('content-body')

    let path = window.location.origin + "/PHP/client/request-client.php"
    if(window.location.hostname == 'localhost')  path = window.location.origin + "/dist/PHP/client/request-client.php"

    RECCLIENT('get', {'client-verifi':'table'}, (data:any)=>{
        const table  = new Rocet('table');
        let TABLE:Array<JSX.Element> = [
            <tr>
                <th>Имя</th>
                <th>Телефон</th>
                <th>Верификация</th>
                <th>Начислить в ручную</th>
            </tr>
        ];

        // начислить 
        function verifi(){

        }
        
        data['client-verifi'].reverse().forEach((client:any) => {
                TABLE.push(
                    <tr>
                        <td>{client.fio}</td>
                        <td>{client.telefon}</td>
                        <td>{client.verifi}</td>
                        {client.verifi != 'CODE'?<td><button onclick={verifi}>Начислить</button></td>: <td>Начислено</td>}
                    </tr>
                )
        });

        table.render(()=>{
            return<table>{...TABLE}</table>
        })
    })


    function loader(boolean:boolean = true){
        if(boolean){
            return'<div class="flex-center" style="width: 100%;" id="load"><div class="loader"></div></div>'
        }else{
            document.querySelector('#load').remove();
            }
    }


    function sendMassanges(evt:any){
        NAME['report-sendMassanges'].innerHTML += loader()
        GET_MY(NAME['sendMassanges'].value).onload =  function(){
            
            loader(false)
            NAME['report-sendMassanges'].innerHTML += this.responseText + '<br> <p>Следующий запрос выполниться через: </p><timer></timer> '
           
            const timerr = new FDate()
                  timerr.TIMER(20000);

            let timer  = setInterval(()=>{

                let t = timerr.TIMER().split(':')

                document.querySelector('timer').innerHTML = '<b>00:' + t[1] + ':' + t[2]+'</b>';

                    if(timerr.TIMER() == '00:00:00'){
                        clearInterval(timer)

                        NAME['report-sendMassanges'].innerHTML+= loader()

                         GET_MY(NAME['sendSMS'].value).onload =  function(){

                            loader(false)

                            NAME['report-sendMassanges'].innerHTML+= this.responseText
                         }
                    }
            }, 1000)

           
                    // запрос 2
           
        }

    }
    function revise(evt:any){
        GET_MY(NAME['revise'].value).onload =  function(){
            NAME['report-revise'].innerHTML += this.responseText
        }

    }
    function bonus(evt:any){
        GET_MY(NAME['bonus'].value).onload =  function(){
            NAME['report-bonus'].innerHTML += this.responseText
        }

    }
    context.render(()=>{
        return<content-body className="client-content-body">
            <div className="block-column">
                <h1>Клиентские параметры</h1>

                <br/><br/>

                <h2>Cсылки</h2><br/>

                <div className="block-column">
                    <p>Крона на отправки сообщений</p>
                    <InputText style={"width: 100%;"} name="sendMassanges" placeholder="" value={path+"?sendWA=true"} onclickRefresh={sendMassanges} />
                    <InputText style={"width: 100%;"} name="sendSMS" placeholder="" value={path+"?sendSMS=true"}  />
                    <div className="block-report" name="report-sendMassanges">
                        <b>Ответ:</b>
                    </div>

                    <p>Крон сверки сообщений</p>
                    <InputText style={"width: 100%;"} name="revise" placeholder="" value={path+"?revise=true"} onclickRefresh={revise} />
                    <div className="block-report" name="report-revise">
                        <b>Ответ:</b>
                    </div>

                    <p>Крон сверки сообщений</p>
                    <InputText style={"width: 100%;"} name="bonus" placeholder="" value={path+"?bonus=true"} onclickRefresh={bonus} />
                    <div className="block-report" name="report-bonus">
                        <b>Ответ:</b>
                    </div>

                </div>

                <h2>Таблица сверки</h2>

                <br/>

                <div className="tech-table">
                    <table> Тут пока пусто</table>
                </div>
                <br/><br/><br/><br/><br/>
            </div>
        </content-body>
    })
}