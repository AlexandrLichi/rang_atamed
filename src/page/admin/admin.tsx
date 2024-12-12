import { integ } from "../../rocet/core/integration";
import { Rocet } from "../../rocet/core/rocet";
import '../../CSS/admin.scss'

import { my_cookie } from "../../library/cookie";
import { Header, Nav } from "./header";



export function Admin(data:any){
    
    const context  = new Rocet('context')
    
    // ставим куки (они должны быть обязательно)
    if(!my_cookie.filial) my_cookie.filial = 'all';
    const News:Array<JSX.Element> = []
    data['news'].forEach((el:any) => {
            News.push( <div className="content-news">
                    <h2>{el.header}</h2>
                    <p>{el.text}</p>
                </div>)
    });

    context.render(()=>{
        return <context>
         <Header data={data}/>
        <content>
            <Nav data={data}/>
            <content-body>
            <div className="admin-home">
                <h1>Новости</h1>
               {...News}
            </div>
            </content-body>
        </content>
        </context>
    })
}
