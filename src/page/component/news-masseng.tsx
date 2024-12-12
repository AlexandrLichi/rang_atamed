import { integ } from "../../rocet/core/integration";
import { Rocet } from "../../rocet/core/rocet";
import '../../CSS/component/news-masseng.scss'

 export function news(masseg:string, bad:boolean = true){
      if(bad) console.error(masseg);


    let body = document.querySelector('.notif-news');
    if(!body){
        const notif = document.createElement('div')
        notif.className = 'notif-news'
        body = document.querySelector('body')
        body.append(notif)
    }
    
    
    const context = new Rocet('notif-news')
    setTimeout(()=>{
      try{
        if(context.Element.children[0]){
          context.Element.children[0].classList.toggle('out-news')
        }
      }catch(e:any){
        console.log(e)
      }
      setTimeout(()=>{
        context.Element.remove()
      },1000)
    }, 3000)
    context.render(()=>{
        return<div className="notif-news">
            <div className={'body '+ (bad?'bad':'')}>
                <p>{masseg}</p>
            </div>
        </div>
    })
 }