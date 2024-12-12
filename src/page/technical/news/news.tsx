
import { integ } from "../../../rocet/core/integration";
import { NAME, Rocet, VALUE } from "../../../rocet/core/rocet";
import { POST } from "../../../router/Request";
import { InputFile } from "../../component/inputFile";

import { HeaderTech, NavTech } from "../Header";
import './../../../CSS/news/news.scss'

interface newsSample{
    text:string
    header:string
    autor?:string|null
    images?:string|null
}

export function NewsMy(data:any){
    const context = new Rocet('context')
    let sample:Array<any> = data['news'];
    console.log(sample)
    
    context.ExecAfter = [
        ()=>render()
    ]
    
    
    function render(){
    const newsContent:Array<JSX.Element> = [];

     const context = new Rocet('text-news')

    sample.forEach((el:any, i:number)=>{
        newsContent.push(<div className="news">
            <h3>{el.header}</h3><br/>
            <p>{el.text}</p><br/>
            <button onclick={()=>deleteNews(i)}>Delete</button>
        </div>)
    })

    context.render(()=><div className="block-news" id="text-news">
                                {...newsContent}
            </div>)
}

    function deleteNews(i:number){
      sample.splice(i, 1)
     POST('/tech/news', {data:sample}).then((data)=>{
           render()
        })
    }

    function sendNews(){
        if(VALUE.text && VALUE.header){
            sample.unshift(VALUE);
        }

        POST('/tech/news', {data:sample}).then((data)=>{
           render()
        })
    }

    context.render(()=>{
        return <context>
            <HeaderTech/>
                <content>
                    <NavTech data={data}/>
                    <content-body>
                        <div className="block-news">
                             <h1>Раздел новостей</h1> <br/><hr/><br/>
                             <form-m>
                                <input type="text" name="header" placeholder="Заголовок"></input>
                                <textarea name="text" placeholder="текст новости" />
                                <InputFile value={""} name="images" placeholder="Выбере файл" onchanges={undefined} />
                                <button onclick={sendNews}>Сохранить</button>
                             </form-m>
                             <br/><br/><hr/><br/><br/>
                             <div className="block-news" id="text-news">
                             
                             </div>
                        </div>
                    </content-body>
                </content>
        </context>
    })
 
}