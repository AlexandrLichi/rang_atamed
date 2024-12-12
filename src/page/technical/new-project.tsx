import { integ } from "../../rocet/core/integration";
import { NAME, Rocet } from "../../rocet/core/rocet";
import '../../CSS/new-project.scss'
import { InputText } from "../component/input-text";
import { news } from "../component/news-masseng";
import { Tech } from "./tech-cab";
import { POST, PUT } from "../../router/Request";



 export function NewProject(data:any){
    if(!document.querySelector('content-body')) Tech(data);

    const context = new Rocet('content-body')

     function getApi(evt:any){
         evt.preventDefault();
         POST('/tech/new-project',{},).then((data:any)=>{
            NAME['api'].value = data['api']
         })
    }

    function create(evt:any){
        evt.preventDefault();
        
        if(NAME['email'].value == ''||
          NAME['company'].value == ''||
          NAME['api'].value == ''
        ) return news('Заполните все поля', true);

        PUT('/tech/new-project', { company:{

            company: NAME['company'].value,
            api:NAME['api'].value,
            email:NAME['email'].value
        }}).then((data:any)=>{
            console.log(data);

        })
    }
    
    context.render(()=>{
        return<content-body>
            <div className="new-project">
                <br/><br/>
                <h1>Новый проект</h1>
                <form>
                
                    <InputText name="company" placeholder="Название компании"/>
                    <InputText name="email" placeholder="Электронная почта"/>
                    <InputText name="api" placeholder="API" onclickRefresh={getApi} disabled={true}/>
                    <button className="btn-yellow" onclick={create}>Создать</button>
                    
                </form>
        </div>
        </content-body>
    })

 }
