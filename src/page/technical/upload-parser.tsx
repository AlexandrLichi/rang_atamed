import { GET_MY } from "../../library/GetPost";
import { integ } from "../../rocet/core/integration";
import { NAME, Rocet } from "../../rocet/core/rocet";
import { GET } from "../../router/Request";
import { RECTEH } from "../admin/request";
import { InputText } from "../component/input-text";
import { Tech } from "./tech-cab";

export function upload(data:any){
    
     if(!document.querySelector('content-body')) Tech(data);

        const context = new Rocet('content-body')
        const PL:Array<JSX.Element> = [];

        data['list'].forEach((pl:any)=>{
            let url =window.location.origin + "/api/"+pl.name+"-update"
            PL.push(
                <div className="url-upload" style={'flex-direction: column;'}>
                    <InputText style={'width: 100%;'} value={url} placeholder="" name={url} onclickRefresh={()=>onrefresh(url, pl.name)} disabled/>
                    <div className="otvet-server"  >
                        <p>Ответ сервера :</p>
                        <span name={pl.name}></span>
                    </div>
                </div>
            )
        })

        let urlImap = window.location.origin + "/imap-update"

        PL.push(
              <div className="url-upload" style={'flex-direction: column;'}>
                    <h4>Обновление парсеров из почты</h4>
                    <InputText style={'width: 100%;'} value={urlImap} placeholder="" name={urlImap} onclickRefresh={()=>onrefreshMath(urlImap, 'imap')} disabled/>
                    <div className="otvet-server" name="imap" >
                        <p>Ответ сервера :</p>
                        {/* <span name={pl.name}></span> */}
                    </div>
                </div>
          )

       
        // сверка клиентов 
         let urlMatch = window.location.origin + "/match"

          PL.push(
             
              <div className="url-upload" style={'flex-direction: column;'}>
                    <h4>Сверка клиентов</h4>
                    <InputText style={'width: 100%;'} value={urlMatch} placeholder="" name={urlMatch} onclickRefresh={()=>onrefreshMath(urlMatch,'match' )} disabled/>
                    <div className="otvet-server" name="match" >
                        <p>Ответ сервера :</p>
                        {/* <span name={pl.name}></span> */}
                    </div>
                </div>
          )

            // сверка клиентов 
         let urlParalele = window.location.origin + "/paralele"

          PL.push(
             
              <div className="url-upload" style={'flex-direction: column;'}>
                    <h4>Запрос на получение отзывов со всех площадок</h4>
                    <InputText style={'width: 100%;'} value={urlParalele} placeholder="" name={urlParalele} onclickRefresh={()=>onrefreshMath(urlParalele,'paralele' )} disabled/>
                    <div className="otvet-server" name="paralele" >
                        <p>Ответ сервера :</p>
                        {/* <span name={pl.name}></span> */}
                    </div>
                </div>
          )

         


          

           function onrefreshMath(url:string, name:string){
                GET(url).onload = function(){
                     NAME[name].innerHTML += this.responseText;
                }
           }

        function onrefresh(url:string, id:string){

           GET(url).onload = function(){
               let responseEl = NAME[id]
            try{

                let get = JSON.parse(this.responseText)
                console.log(get);
                 console.log(responseEl);
                 let str = ''
                 str += `<span> Принято ${get['response'][get['response'].length - 1].COUNT}<br>`
                                            ;
               get['response'].forEach((el:any , i:number)=>{
                
                    if(i !=  get['response'].length - 1 )
                         str += `<span> API: ${el.API}<br> FILIAL:${el.FILIAL}<br> UPDATE:${el.UPDATE}<br> INSERT:${el.INSERT}<br>ERROR:${el.ERROR.length != 0 ? el.ERROR.join(' '):''}<br>`
                })
    
               responseEl.innerHTML += str+"</span>";
            }catch(e){
                responseEl.innerHTML += this.responseText
            }
           }    
        }

        context.render(()=>{
            return <content-body style={'flex-direction: column;'}>
                <div><h1>Ручное обновление отзывов</h1></div>
                <div style={'flex-direction: column;'}>
                   {...PL}
                </div>
            </content-body>
        })
    // })

}