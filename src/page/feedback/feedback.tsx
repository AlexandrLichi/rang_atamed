import { FDate } from "../../library/DateClass"
import { toUpperCase } from "../../library/string"
import { integ } from "../../rocet/core/integration"
import { NAME, Rocet } from "../../rocet/core/rocet"
import { PUT } from "../../router/Request"
import { InputText } from "../component/input-text"
import { loaderForm } from "../component/loader-grose"
import { news } from "../component/news-masseng"

// import './api/api'
// стек панель для админов по ссылке 
export function Feedback(data:any){
    const context = new Rocet('body')
    
    // let DecodeSearch = decodeURI(window.location.search)
    let result:any = {}
    // получаем get запрос
    // if(DecodeSearch != (null||'')){
        
        // let request:string|Array<string> = DecodeSearch.replace('?','')
        // request = request.split('&')
        
        // request.forEach((str:string)=>{
        //         result[str.split('=')[0]] = str.split('=')[1]
        // })
        // if(!result['api'] || !result['filial'] )return ER404(context, 'Не все параметры GET запроса')


        context.render(()=>{
            
            return<body> 
                <context>
                <content-body>
                
                    <div className="form-feedback">
                        <h1>Запрос на отзыв</h1>
                        <br/>
                        <h2>{toUpperCase(data['filial'])}</h2>
                        <InputText name="famyli" placeholder="Фамилия" />
                        <InputText name="name" placeholder="Имя"/>
                        <InputText name="first-name" placeholder="Отчество"/>
                        <InputText name="tel" placeholder="Телефон"  />
                        {/* <button className="btn-yellow"  onclick="">Добавить в очередь</button> */}
                         <br/> 
                        <button className="btn-yellow" onclick={send}>Отправить сейчас</button>
                         <br/>
                        <info className='deactive'></info>
                    </div>
                </content-body>
                </context>
            </body>
        })
    
    
    function examintion(){
        let  bool = true;
        ['name', 'famyli', 'tel'].forEach((str:string)=>{
            const Input =   NAME[str].parentElement
            if(!NAME[str] || NAME[str].value ==''){ 
               Input.setAttribute('data', 'Заполните поле');
               bool = false;

            }else{
                
                Input.setAttribute('data', '');
            }
        })
        
        return bool;
    }


     function send(evt:any){
        if(examintion()){

            result.name = NAME['famyli'].value.trim()+ ' ' + NAME['name'].value.trim() + ' '+ NAME['first-name'].value.trim()
            let tel = NAME['tel'].value.trim()
            if(tel[0] == '8' ) tel =  '7' + tel.slice(0, 1)
            result.telefon = tel

            btn_disabled(true)
            
            const info = document.querySelector('info')
                info.classList.remove('deactive')
                loaderForm('info');
                

                    PUT('/feedback/'+data['api']+'/'+data['id'], result).then((put)=>{
    
                        btn_disabled(false)
                        if(typeof put == 'object'){
                            news(put.request, false);
                            if(put.request == 'Отправлено'){
                                info.className = ''
                                info.textContent = put.request
                            }else{
                                info.className = 'red'
                                info.textContent = put.request
                            }
                        }
                    })
                
        }

        }

        function btn_disabled(bool:boolean){
          document.querySelectorAll('.btn-yellow').forEach((btn:HTMLButtonElement)=>{
              bool? btn.setAttribute('disabled', ""): btn.removeAttribute('disabled');
            })
        }


    //         function sendDay(){
    //              if(examintion()){

    //                 result.fio = NAME['name'].value.trim() + ' ' + NAME['famyli'].value.trim() + ' ' + NAME['first-name'].value.trim()
    //                 let tel = NAME['tel'].value.trim()
    //                 if(tel[0] == '8' ) tel =  '7' + tel.slice(0, 1)
    //                 result.telefone = tel

    //                  block_btn();

    //                 const info = document.querySelector('info')
    //                     info.classList.remove('deactive')
    //                     loaderForm('info');

    //                 RECCLIENT('set', {'pannel-client-send':result}, (data:any)=>{
    //                     anti_block_btn();
    //                     // console.log(data)
    //                     info.textContent = data['pannel-client-send']
    //                 })
    
    //             }

    //         }
}