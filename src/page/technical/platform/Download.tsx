import { integ } from "../../../rocet/core/integration";
import { Rocet } from "../../../rocet/core/rocet";
import { POST } from "../../../router/Request";
import { news } from "../../component/news-masseng";
import { find } from "../all-client/Companies";
import { Tech } from "../tech-cab";


export function Download(data:any){

    if(!document.querySelector('content-body')) Tech(data);

  let platform = data['platform']

  const context = new Rocet('content-body')
  const TR:Array<JSX.Element> = [
    <tr>
        <th>Филиал</th>
        <th>Платформа</th>
        <th>Получено</th>
        <th>Действие</th>
    </tr>
  ]


  function down(evt:any,idParser:any){

    disablendButton();
    buttonloader(evt);

    POST('/tech/download/'+data['company'][0]['api']+"/"+idParser,{}).then((post:any)=>{

      disablendButton();
      buttonloader(evt);

      if(post['download']){
        let UPDATE = "UPDATE: " + post['download']['UPDATE']
        let INSERT = "INSERT: "+ post['download']['INSERT']
          news(UPDATE + ',' + INSERT, false);
        evt.target.parentElement.parentElement.querySelector('#UPDATE').textContent  = UPDATE;
        evt.target.parentElement.parentElement.querySelector('#INSERT').textContent = INSERT
      }else{
        news("Парсер не прислал данные смотрите лог ошибок", true);
      }
      
    })


  }

  function buttonloader(evt:any){
    evt.target.classList.toggle('loader');
  }

  function disablendButton(){
    document.querySelector('.tech-table').querySelectorAll('button').forEach((button:HTMLButtonElement)=>{
      button.toggleAttribute('disabled')
    })
  }
  
  data['parser'].forEach((parser:any)=>{
    
    TR.push(
        <tr>
            <td>{parser.filial}</td>
            <td>{parser['name']}</td>
            <td><div className="count-downloand"><p id="All">{"All:"+parser['count']}</p>
                    <p id="UPDATE">{'UPDATE:0'}</p>
                    
                    <p id="INSERT">{"INSERT: 0"}</p></div></td>
            <td><button onclick={(evt:any )=>down(evt, parser['id'])}>Загрузить</button></td>
        </tr>
    )

  })


  context.render(()=>{
        return <content-body>
         <div className="tech-table" >
                <h1>Выгрузка отзывов</h1>
                <h2>{"Компания: "+data['company'][0]['company'] }</h2>
                <h2>{"API: "+data['company'][0]['api'] }</h2>
                <br/>
                <table >{...TR}</table>
            </div>
        </content-body>
  })
}