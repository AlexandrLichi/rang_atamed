import { integ } from "../../rocet/core/integration";
import { NAME, Rocet } from "../../rocet/core/rocet";
import { PUT } from "../../router/Request";
import { InputText } from "../component/input-text";
import { news } from "../component/news-masseng";
import { Tech } from "./tech-cab";

export function Setting(data:any){
     if(!document.querySelector('content-body')) Tech(data);
const context = new Rocet('content-body')

function setConfig(evt:any){
    evt.preventDefault()

    if(NAME['PROTOCOL'].value == ''||NAME['IP'].value == ''||NAME['PATH'].value == '')
       return news('Не все поля заполнены!', true);
    PUT('/tech/setting',{config:
        {
            "PROTOCOL":NAME['PROTOCOL'].value.trim(),
            "IP":NAME['IP'].value.trim(),
            "PATH":NAME['PATH'].value.trim()
        }
    }).then((put:any)=>{
        console.log(put)
    })

}


context.render(()=>{
    return<content-body>
        <div className="new-project">
            <h1>Настройки</h1>
            <div className="block-filial">
            <div className="block-platform" data="ParserServer">
                <div className="name-platform"><h3>Настроки доступа к парсерам</h3></div>
               <form >
                <InputText name="PROTOCOL" value={data['config']['PROTOCOL']} placeholder={"PROTOCOL"}/>
                <InputText name="IP" value={data['config']['IP']} placeholder={"IP"}/>
                <InputText name="PATH" value={data['config']['PATH']} placeholder={"PATH"}/>
                <button className="btn-yellow" onclick={setConfig}>Сохранить</button>
               </form>
            </div>
            </div>
        </div>
    </content-body>
})
// })
}