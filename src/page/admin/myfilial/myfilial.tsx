import { my_cookie } from "../../../library/cookie";
import { toUpperCase } from "../../../library/string";
import { integ } from "../../../rocet/core/integration";
import { Rocet } from "../../../rocet/core/rocet";
import { PUT } from "../../../router/Request";
import { news } from "../../component/news-masseng";
import { Toggle } from "../../component/toggle";
import { Header, Nav } from "../header";
import { REC } from "../request";


export function myFilial(data:any){
    // my_cookie.filial = 'Все филиалы'
    const context = new Rocet('context')
    console.log(data);
    // REC('get', {company:api}, (data:any)=>{
        let PL:Array<JSX.Element> =[]
     data['platform-list'].forEach((pl:string)=>{

        PL.push(<th>{toUpperCase(pl)}</th>)

     })
    //     console.log(data)
        const TR:Array<JSX.Element> = [
            <tr>
                <th>Филиалы</th>
                {/* <th>Запросы</th>
                <th>Отправлять</th>  */}
                {...PL}
            </tr>
        ]
        
        let Normalise:{[name:string]:Array<any>}= {};
    
        data['parser'].forEach((fil:any) =>{
            Normalise[fil.filial]?Normalise[fil.filial].push(fil) : Normalise[fil.filial] = [fil]
        });

        Object.keys(Normalise).forEach((key)=> {
                 const TD:Array<JSX.Element> = [<td>{key}</td>]

                 for (let i = 0; i < data['platform-list'].length; i++){
                     TD[i + 1] =<td><div><i>Не подключено</i></div></td>;
                 }

                 Normalise[key].forEach((fil:any)=>{

                     data['platform-list'].forEach((name:string, i:number)=>{
                         if(fil.name == name) {
                            if(fil.platform_marketing){
                                let mark:Array<string> = fil.platform_marketing.split('//');
                                TD[i + 1] =<td><div><Toggle tog={mark.includes(fil.name)} name={'tog'} onclick={(evt:any)=>toggle(evt,name, fil.filial_id)}/></div></td>
                            }else{
                                TD[i + 1] =<td><div><Toggle tog={false} name={'tog'} onclick={(evt:any)=>toggle(evt,name, fil.filial_id)}/></div></td>
                            }
                        }
                     })
                 })

                 TR.push(<tr>{...TD}</tr>);
        });

        function toggle( evt:any, name:string, filial_id:string){
            
           const put = {
                id:filial_id,
                platform:name,
                activ:Number(evt.target.getAttribute('value'))
            }

           PUT('/admin/myfilial/toggle', {'toggle':put}).then((put:any)=>{
                console.log(put);
           })
        }


        context.render(()=>{
            return<context>
                <Header data={data}/>
                <content>
                    <Nav data={data}/>
                <content-body>
                <div className="all-filial-admin">
                    <h1>Мои филиалы</h1>
                    <br/>
                    <h2>Продвижение</h2>
                    <table>{...TR}</table>
                </div>
            </content-body>
            </content>
            </context>
        })
    // })
}