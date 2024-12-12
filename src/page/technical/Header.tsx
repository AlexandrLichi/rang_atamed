import { integ } from "../../rocet/core/integration";
import { transition } from "../../router/api";

export function HeaderTech(props: { data?: any }) {
    console.log(props.data);
    return <header className="admin-header">
            <div className="center-header">
                <div className="btn-block">
                  <logo onclick={()=>transition('/admin')} >Reviews And Ratings</logo>
                    <p style="font-size: 12px;">Тех-канал </p>
                    <p style="font-size: 12px;">{ props.data['GIT']}</p>
                </div>

                <div className="btn-block" >
                    <button onclick={()=>transition("/tech/news")}>News</button>
                    <button onclick={()=>transition("/tech/client")}>Client</button>
                    <button onclick={()=>transition("/tech/upload")}>Upload</button>
                    <button onclick={()=>transition("/tech/logs")}>Logs</button>
                    <img src={require('../../images/icons8-настройки-white.png')}onclick={ ()=>transition('/tech/setting')}></img>
                    <button >Выйти</button>
                </div>
            </div>
        </header>
}

export function NavTech(props:{data:any}){

     const listLI:Array<JSX.Element> = []
       props.data['list'].forEach((plat:any)=>{
            listLI.push(
                 <li><img src={require('../../images/icons8-' + plat.name + '-blue.png')}></img><a href={'/tech/plat/'+plat.name}>{plat.name}</a></li>
            )
       })

   return  <nav>
                <ul>
                    { ...listLI}
                   
                    <li><img src={require('../../images/icons8-all-blue.png')}></img><a href="/tech/companies">Компании</a></li>
                </ul>
                <a className="btn-yellow" href="/tech/new-project">Новый проект</a>
        </nav>
}