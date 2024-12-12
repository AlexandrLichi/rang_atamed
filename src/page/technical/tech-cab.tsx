import {   integ } from "../../rocet/core/integration";
import { NAME, Rocet } from "../../rocet/core/rocet";
import { transition } from "../../router/api";
import { POSTF } from "../../router/Request";
import { InputText } from "../component/input-text";
// import { RECTEH } from "../admin/request";
// import { Platform } from "./platform/platform";
// import { form_new_client } from "./new-project";
// import { tech_cab_setting } from "./tech-cab-setting";
// import { AllClient } from "./all-client/allclient";
// import { Logs } from "./logs";
// import { upload } from "./upload-parser";
// import { SendClient } from "./send-client";
// import { toUpperCase } from "../../library/string";
import './../../CSS/new-parser.scss'
import { HeaderTech, NavTech } from "./Header";
// import { InputText } from "../component/input-text";
// import { POST_MY_FILES } from "../../library/GetPost";
// import { news } from "../component/news-masseng";

export function Tech(data:any){
    const context = new Rocet('context')

      
    

       function setNewParser(){
        let parser:any = {
            name:NAME['name'].value,
            default_url:NAME['default_url'].value,
            query:{}
        }
            document.querySelectorAll('.input-text').forEach((el:HTMLDivElement, i:number)=>{
                if(i != 0 && i !=1) parser.query[el.querySelector('input').value] = ""
                
            })

            let files:{[name:string]:File} = {}

            document.querySelectorAll('input[type=file]').forEach((el:HTMLInputElement)=>{
              files[el.name] = el.files[0]
            })

            POSTF('/tech/new-parser', parser, files).then((post)=>{
               post.text().then((str)=>{console.log(str)})
            })
        
       }

    
       
       context.render(()=>{
        return <context>
            <HeaderTech data={data}/>
         <content>
            <NavTech data={data}/>
            <content-body>
                <div className="new-parser">
                <h1>Технический кабинет</h1><br/>
                <h2>Создание нового парсера</h2>
                <InputText name='name' placeholder="Имя (лат. без доб символов)"/>
                <InputText name='default_url' placeholder="URI для места открытия отзывов"/>
                <b>Синяя иконка png</b>
                <input type="file" name="icon-blue"></input>
                 <b>Оригинальная png</b>
                <input type="file" name="icon-origin"></input>
                <InputText name="param" placeholder="Параметры" newInput={true}/>
                <button className="btn-yellow" onclick={setNewParser}>Добавить Новый парсер</button>
                </div>
            </content-body>
        </content>
        </context>
    })
  
}