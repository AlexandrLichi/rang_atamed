import { integ } from "../../../rocet/core/integration";
import { NAME, Rocet, VALUE } from "../../../rocet/core/rocet";
import '../../../CSS/tech-cab.scss'
import { RECTEH } from "../../admin/request";
// import { company, google, yandex } from "../../../interface/interface";
import { Age } from "../../../library/objectJS";
import { news } from "../../component/news-masseng";
// import { toUpperCase } from "../../../library/string";
import { InputText } from "../../component/input-text";
import { Select } from "../../component/select-header";
import { Tech } from "../tech-cab";
import { toUpperCase } from "../../../library/string";
import { GET, POST, PUT} from "../../../router/Request";
import { Router, transition } from "../../../router/api";
// import { includeParam } from "../../../main";
// interface AllPlatform extends google, yandex{
//   api:string
//   company:string
// }

export function Platform(data:any){
     if(!document.querySelector('content-body')) Tech(data);
    console.log(data);
  let platform = data['platform']

  const context = new Rocet('content-body')

  const TR:Array<JSX.Element> =[
    <tr><th>Подключеные API/Филиал</th><th>Параметры</th></tr>
  ]

  // Формируем таблицу филиалов
  data[platform].forEach((filial:any)=>{
    let parametr:Array<JSX.Element> = [];
  let setting = JSON.parse(filial.setting)

    Object.keys(setting).forEach((key:string)=>{
      parametr.push(<div className="parametr"><p>{key+" : "}</p><input className="redact-input" name={key} value={setting[key]}/></div>)
    });
    function inputApiSet(evt:any){
      NAME[platform].value = evt.target.textContent
    }

    TR.push(<tr>
          <td>
            <p>{filial.filial}</p>
            <br/>
            <p className="min" onclick={inputApiSet}>{filial.api}</p>
          </td>
          <td><form>
              {...parametr}
              <div className="but-action"><p>Действия: </p><button onclick={(evt:any)=>settingSend(evt, filial.id)}>Изменить</button></div>
            </form>
          </td>
    </tr>)
  })


  // обновление настроек парсера по филиалу
  function settingSend (evt:any, id:string){
    evt.preventDefault()

   let send:any = {
      "name":platform,
      "id": id,
      "setting":{}
    }

    evt.target.parentElement.parentElement.querySelectorAll('input').forEach((input:HTMLInputElement)=>{send['setting'][input.name] = input.value})

      PUT("/tech/plat/"+ platform, send).catch((updata)=>{
          news('Параметр изменен');
      })
  }


      function inputApi(evt:any){

        let API = NAME[platform].value

        if(API.split('-').length == 3){
          NAME[platform].parentElement.setAttribute('data','')
          NAME[platform].parentElement.classList.remove('error')

          POST(`/tech/filial/${API}`).then((post:any)=>{
                renderFilial(post, API)
          })

        }else{
          NAME[platform].parentElement.setAttribute('data','Неверный API')
          NAME[platform].parentElement.classList.add('error')

        }
    }

    function renderFilial(FilialList:Array<any>, valueIput:string){
      const form = new Rocet('include')
      const option:Array<JSX.Element> = [<option>Новое название</option>] 
      FilialList.forEach((filial:any)=>option.push(<option>{filial.filial}</option>))

      function onSelectChenge(evt:any){
        if(evt.target.value == 'Новое название'){
          document.querySelector('.input-new-filial').classList.remove('deactive')
        }else{
          document.querySelector('.input-new-filial').classList.add('deactive')
        }
      }

      function sendNewFilial(){
       let fil = null;
       !NAME['input-filial'].classList.contains('deactive')?
          fil = NAME['input-filial'].value.trim(): fil = NAME['select-filial'].value
        
  
        if(fil && fil != 'Новое название'){
         let send = {
            api:valueIput,
            filial:fil,
         }

           POST('/tech/plat/'+platform, send).then((put:any)=>{
              Router('/tech/plat/'+platform);
              news(put, true)
           })
           
        }else{
          news('Не все поля заполнены', true)
        }
      }

      form.render(()=>{
        return<div className="include" data='Добавить филиал'>

              <InputText name={platform} placeholder="API" onclickRefresh={inputApi} value={valueIput}/>
              <div className="filial-block">
                <select onchange={onSelectChenge} name="select-filial">{...option}</select>
                <input type="text" name="input-filial" className="input-new-filial" placeholder="Введите новое назание"></input>
              </div>
               <div className="filial-block">
                <button onclick={sendNewFilial}>Подключить</button>
               </div>
            </div>
      })
    }
    let listParam:any = {};
    data['list'].forEach((list:any)=>{
      if(data['platform'] == list.name) listParam = list;
    })

    function replace_url_default(){

      POST('/tech/url_default/'+listParam.id, {url_default: NAME['url_default'].value}).then((post:any)=>{
          console.log(post);
      })
    }

    function new_param(){
      if(NAME['new_param'].value == '') return news('Нет параметра в поле!');
        POST('/tech/new_param/'+listParam.id, {new_param: NAME['new_param'].value}).then((post:any)=>{
          transition(window.location.href);
          news("Параметр добавлен", false);
      })
    }

      context.render(()=>{
        return <content-body>
          <div className="tech-table">
          <h1>{toUpperCase(data['platform'])}</h1>
          <div className="include url-default" data='Новый параметр'><InputText name="new_param" value="" placeholder="Новый параметр" onclickRefresh={new_param}/></div>
          <div className="include url-default" data='Cсылка для перехода на отзыв'><InputText name="url_default" value={listParam.default_url} placeholder="Url default" onclickRefresh={replace_url_default}/></div>
          <div className="include" data='Добавить филиал'><InputText name={data['platform']} placeholder="API" onclickRefresh={inputApi}/></div>
            <table>{...TR}</table>
          <br/><br/><br/> <br/><br/><br/>
          </div>
        </content-body>
      })
}