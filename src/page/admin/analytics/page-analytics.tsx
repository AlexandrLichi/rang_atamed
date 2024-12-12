import { my_cookie } from "../../../library/cookie";
import { integ } from "../../../rocet/core/integration";
import { NAME, Rocet } from "../../../rocet/core/rocet";
import '../../../CSS/page-analytics.scss'
import { REC } from "../request";
import { toUpperCase } from "../../../library/string";
import { FDate } from "../../../library/DateClass";
import { Header, Nav } from "../header";
import { POST } from "../../../router/Request";

export function Analytics(data:any){
   console.log(data);
    const context = new Rocet('context')
    
    const getAnalitic:any = {
        api:data['api'],
        st: new FDate().MA(),
        en: new FDate()["YYYY-MM-DD"](),
        filial_id: my_cookie.filial != 'all'? my_cookie.filial: null,
    }

    context.ExecAfter = [
            ()=>select(),
            ()=> enChange()
    ]
//     if(my_cookie.filial != 'Все филиалы') getAnalitic.filial = my_cookie.filial
 const select = ()=>{
        let selectblock:HTMLElement = document.querySelector('.block-select')
       
        selectblock.onclick = function(){
            if(getAnalitic.filial_id != my_cookie.filial){
               getAnalitic.filial_id =  my_cookie.filial != 'all'? my_cookie.filial: null ;
                 enChange();
            }
        }
    }
//     console.log(getAnalitic)
//     REC('get',{'analytics':getAnalitic}, (data:any)=>{

        function deleteTabe(){
            for(let i = 0; i < NAME['tabe'].children.length - 1; i++){
                NAME['tabe'].children[i].className = ''
            }
        }

        function tabWeek(evt:any){
            // console.log(evt)
            deleteTabe()
            evt.target.className = 'button-open'
            NAME['st'].value  = new FDate().WA()
            NAME['en'].value = new FDate()["YYYY-MM-DD"]()
            enChange()
        }

         function tabMoth(evt:any){
            deleteTabe()
            evt.target.className = 'button-open'
            NAME['st'].value  = new FDate().MA()
            NAME['en'].value = new FDate()["YYYY-MM-DD"]()
            enChange()
         }

          function tabYear(evt:any){
            deleteTabe()
            evt.target.className = 'button-open'
            NAME['st'].value  = new FDate().YA()
            NAME['en'].value = new FDate()["YYYY-MM-DD"]()
            enChange()
         }

         function tabKvart(evt:any){
            deleteTabe()
            evt.target.className = 'button-open'
            NAME['st'].value  = new FDate().KV()
            NAME['en'].value = new FDate()["YYYY-MM-DD"]()
            enChange()
         }

        

        function enChange(){
            getAnalitic.st =  NAME['st'].value
            getAnalitic.en =  NAME['en'].value
            NAME['en'].setAttribute('min', NAME['st'].value )

            POST('/admin/analytics',  {"search":getAnalitic}).then((post:any)=>{
                // console.log(post)
                renderBlockAnalitic(post , NAME['st'].value, NAME['en'].value);
            })

            NAME['dinamic'].textContent = 'Динамика рейтинга и отзывов за ' + FDate.day_difference(getAnalitic.st, getAnalitic.en) + ' дн.'
           

        }


        context.render(()=>{
            return <context>
                <Header data={data}/>
                <content>
                    <Nav data={data}/>
                <content-body>
                <div className="analitics">
                    <h1>Аналитика</h1>
                    
                    <div className="tab-analitics" name='tabe'  >
                        <button onclick={tabWeek}>Неделя</button>
                        <button className="button-open" onclick={tabMoth}>Месяц</button>
                        <button onclick={tabKvart}>Квартал</button>
                        <button onclick={tabYear}>Год</button>

                        <div className="date">
                            <input type="date" onchange={enChange} name='st' value={getAnalitic.st}  max={new FDate()['YYYY-MM-DD']()}/>
                            <p>-</p>
                            <input type="date"  onchange={enChange} value={getAnalitic.en} name='en' min={getAnalitic.st} max={new FDate()['YYYY-MM-DD']()}/>
                        </div>
                    </div>
                    <h4 name="dinamic">{'Динамика рейтинга и отзывов за ' + FDate.day_difference(getAnalitic.st, getAnalitic.en) + ' дн.'}</h4>
                    <div className="block-analitics">
                       {/* {...ELEM} */}
                    </div>
                </div>
                <br/><br/><br/>
            </content-body>
            </content>
            </context>
        })


//         renderBlockAnalitic(data , NAME['st'].value, NAME['en'].value);
//     })


 function renderBlockAnalitic(data:any, st:string, en:string){
 
const anal =  new Rocet('block-analitics')
 const controlPlatform:Array<any> = []
        console.log(data['analytics'])
        const increase =  data['analytics'][1]['increase']

        const ELEM:Array<JSX.Element> = [
            <div className="header-analitics-counter">
                <div>
                    <h1>{String(data['analytics'][1]['requestAll'])}</h1>
                    <p>количество запросов</p>
                </div>
                <div>
                    <h1 className="red">{String(data['analytics'][1]['requestAllNEGATIVE'])}</h1>
                    <p>негатива перехвачено</p>
                </div>
                 <div>
                    <h1 className={increase >= 1?'green':'red'}>{String(increase)}</h1>
                    <p>Прирост отзывов</p>
                </div>
            </div>
        ]

         ELEM.push( <div className="header-analitics">
               
                <b>Платформы</b>
                <b data={new FDate(st)['D Month']() + "  " + new FDate(en)['D Month']()}>Рейтинг</b>
                <b>{'Прирост отзывов <br></br> Полож. и Отриц.'}</b>
                <b>Запросов</b>
                
            </div>)

       
    

        data['analytics'][0].forEach((pl:any)=>{

           if(!controlPlatform.includes(pl.filial)){
                controlPlatform.push(pl.filial)
                ELEM.push( <div className="header-filial-analitics"><h3>{toUpperCase(pl.filial)}</h3><b>{"Перехвачено негатива : " + String(pl.requesClientNegative)}</b></div>)
                data['analytics'][0].forEach((fill:any)=>{

                if(fill.filial === pl.filial){
                   
                    const inStar = increase_star(fill.analis[1]);
                    const strInstar = inStar > 0? '+ '+ String(inStar).split('.').join(','):
                     inStar < 0? String(inStar).split('.').join(','):'-';
                    let rating = fill.analis[1].length > 0? fill.analis[1][0][0] +' - '+ fill.analis[1][fill.analis[1].length - 1][0]:'-/-'

                    ELEM.push(<div className="row-analitics">
                <div className="platform-analitics"><img src={require('../../../images/search-'+fill.platform+'.png')}/><p >{fill.platform}</p></div>
                <div>
                <p>{rating}</p>
                <p className={"tab " + (inStar > 0?"green":"") + (inStar < 0?"red":"") }>{strInstar}</p>
                </div>
                <div><p>{fill.count == 0 ? '-': '+ ' + String(fill.count)}</p> 
                <p className={fill.countPositive == 0?'tab':'tab green'}>{String(fill.countPositive)}</p>
                <p className={fill.countNegative == 0?'tab':'tab red'}>{String(fill.countNegative)}</p>
                </div>
                 <div><p  className={"tab "+(fill.requesClientPositive > 0?'green':'')}>{String(fill.requesClientPositive)}</p></div>
                </div>)
                }
            })
           }

        })
        anal.render(()=>{
            return<div className="block-analitics">
                       {...ELEM}
                    </div>
        })
 }

 function increase_star(anal:Array<Array<string>>):number{
    if(anal.length == 0) return 0
   
    let st = Number(anal[0][0].split(',').join('.'))
    let en = Number(anal[anal.length - 1][0].split(',').join('.'))
    let res:number =  Number((en - st).toFixed(1))
   
    // if((st - en) == 0) return '-'
    return  res

 }



}