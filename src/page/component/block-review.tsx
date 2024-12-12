import { integ } from "../../rocet/core/integration";
import '../../CSS/component/block-review.scss'
import { FDate } from "../../library/DateClass";
import { news } from "./news-masseng";

export function Review(props:{name:string, date:string, area:string, stars:string, text:string, answer:null|{dateanswer:string, answer:string }, img?:string}){



    function Avatar(img:string|null){
        if(img) return <div className="avatar"><img  referrerPolicy="no-referrer" src={img}></img></div>
        return <div className="avatar-no"><img src={require('../../images/icons8-no-avatar.png')} /></div>

    }

    function copuBufer(evt:any){
        const div = evt.target.parentElement.parentElement.parentElement
       const text =  div.querySelector('.body-review').querySelector('p').textContent
       navigator.clipboard.writeText(text).then(()=>{
                news('Скопированно в буфер', false);
       })
    }


    return <div className="block-review">
        <div className="header-review">
             <div className="right">
                {Avatar(props.img)}
                <p>{props.name}</p>
                <p className="date">{new FDate(props.date)['DD.MM.YYYY']()}</p>
             </div> 
             <div className="left">
                <a>{props.area}</a>
                <Stars stars={props.stars}></Stars>
             </div>
            <div className="copu"><img onclick={copuBufer} src={require('./../../images/icons8-copu.png')}/></div>
        </div>
        <div className="body-review">
            <p>{props.text}</p>
            {props.answer && props.answer.answer != ''?
            <div className="body-answer">
                <span>{'Ответ : ' + new FDate(props.date)['DD.MM.YYYY']()}</span>
                <p>{props.answer.answer}</p>

            </div>:<b></b>}
        </div>
        
    </div>
}


export function Stars(props:{stars:string}){
    let s = Number(props.stars)
    const IMG:Array<JSX.Element> = []
    for(let i = 1; i <= 5; i++ ){
        if(i <= s){
            IMG.push(<img src={require('../../images/icons8-звезда-stars.png')}/>)
        }else{
            IMG.push(<img src={require('../../images/icons8-звезда-stars-none.png')}/>)
        }
    }
    return<stars>{...IMG}</stars>
}