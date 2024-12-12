import { integ } from "../../rocet/core/integration";
import '../../CSS/component/search-reviews.scss'
import { search } from "../admin/reviews/admin-reviews";
import { NAME } from "../../rocet/core/rocet";
import { FDate } from "../../library/DateClass";



 export function SearchReviews(props:{platform:Array<string>,  onclick:Function, search:any, prefix:Array<string>|undefined|null,}){

    const PL:Array<JSX.Element> = [];
    const ST:Array<JSX.Element> = []
    const DT = {

    }
   


    function search(evt:any, platformS:string|null, num:number|null){

        if(platformS){
            let platform  = evt.srcElement
            if(platform.tagName != 'DIV') platform  = platform.parentElement
            console.log('hi')
            if(platform.className == 'active' && props.search.platform.includes(platform.getAttribute('data'))){
                 props.search.platform = props.search.platform.filter((number:string) => number !== platform.getAttribute('data'));
                platform.className = ''
            }else{
                 platform.className = 'active'
                 props.search.platform.push(platform.getAttribute('data'))
            }
        }
            

        if(num){
            let star = evt.target
            if(star.className == 'active' && props.search.star.includes(star.textContent)){
                
               props.search.star = props.search.star.filter((number:string) => number !== star.textContent);
                
                star.className = ''
            }else{
                 star.className = 'active'
                 props.search.star.push(star.textContent)
            }
        }

        // console.log(evt.srcElement.parentElement.parentElement)
        // if(evt.srcElement.parentElement.parentElement.className == 'search-platform'){
        //     
            
        // }else{
        //    console.log(evt.target)
        //     
        // }
        // console.log(props.search.platform)
       props.onclick(props.search)
    }

   

    // платформы
    props.platform.forEach((name:string)=>{

        let control = false

         props.search.platform.forEach((nameS:string)=>{ if(nameS == name) control = true })

                PL.push(<div onclick={(evt:any)=>search(evt, name, null)} className={control?'active':''} data={name}>
                    
                    <img src={require('../../images/search-' + name + '.png')}></img>
                    <span>{name[0].toUpperCase() + name.slice(1)}</span>
                </div>)

    })

    // prefix 
    if(props.prefix){

        props.prefix.forEach((name:string)=>{
    
            let control = false
    
             props.search.platform.forEach((nameS:string)=>{ if(nameS == name) control = true })
    
                    PL.push(<div onclick={(evt:any)=>search(evt, name, null)} className={control?'active':''} data={name}>
                        <span>{name[0].toUpperCase() + name.slice(1)}</span>
                    </div>)
    
        })
    }




    // Оценки 
    for(let i:number = 1; i < 6; i++){
        let control = false;
        props.search.star.forEach((num:string)=>{if(i == Number(num)) control = true})
                ST.push(<span onclick={(evt:any)=>search(evt,null, i)} className={control?'active':''}>{String(i)}</span>)
    
    }

function changeDate(evt:any){
        if(evt.target.name == 'st'){
            NAME['en'].setAttribute('min', evt.target.value)
            NAME['en'].removeAttribute('disabled')

            if( NAME['st'].value == new FDate()['YYYY-MM-DD']()){
                NAME['en'].value = new FDate()['YYYY-MM-DD']()
                reload()
            }
        }
       
        // console.log(evt)
        if( NAME['st'].value != '' &&  NAME['en'].value != '' ){
           reload()
        }
        // if()
}

function reload(){
    NAME['en'].removeAttribute('disabled')
    props.search.en = NAME['en'].value
    props.search.st = NAME['st'].value
    // console.log(props.search)
    props.onclick(props.search)
}

function sDay(){
    NAME['st'].value = new FDate()['YYYY-MM-DD']()
    NAME['en'].value = new FDate()['YYYY-MM-DD']()
    NAME['en'].setAttribute('min', NAME['st'].value)
    reload()
}

function tDay(){
     NAME['st'].value = new FDate().YTD()
     NAME['en'].value = new FDate().YTD()
     NAME['en'].setAttribute('min', NAME['st'].value)
     reload()
}
function weegAgo(){
     NAME['st'].value = new FDate().WA()
     NAME['en'].value = new FDate()['YYYY-MM-DD']()
     NAME['en'].setAttribute('min', NAME['st'].value)
     reload()
}

function monthAgo(){
     NAME['st'].value = new FDate().MA()
     NAME['en'].value = new FDate()['YYYY-MM-DD']()
     NAME['en'].setAttribute('min', NAME['st'].value)
     reload()
}
function time_All(){
   delete props.search.en
   delete props.search.st
    NAME['st'].value = ''
    NAME['en'].value = ''
    NAME['en'].setAttribute('disabled', '')
    NAME['en'].removeAttribute('min')
    props.onclick(props.search)
}
const DOP:Array<JSX.Element> = []

 function searchImap(evt:any){
    if(props.search.imap['search'] == undefined ||props.search.imap['search'] == false ){
        evt.target.className = 'active'
        props.search.imap['search'] = true;
    }else{
         evt.target.className = ''
        props.search.imap['search'] = false;
    }

    reload()
 }

    if(props.search.imap){
        DOP.push(<button className={props.search.imap['search']?'active':''} onclick={searchImap}>Отзывы с почты</button>)
    }

    return <div className="search">
        <p className="sort-header">По дате</p>
        <div className="search-date">
            <input type="date" name="st" onchange={changeDate} max={new FDate()['YYYY-MM-DD']()} value={props.search.st?props.search.st:''}></input>
            <input type="date" name="en" onchange={changeDate} max={new FDate()['YYYY-MM-DD']()} value={props.search.en?props.search.en:''} ></input>
        </div>
             <div className="search-tab">
                <button onclick={sDay}>Сегодня</button>
                <button onclick={tDay}>Вчера</button>
                <button onclick={weegAgo}>Неделя</button>
                <button onclick={monthAgo}>Месяц</button>
                <button onclick={time_All}>За все время</button>
             </div>
        <p className="sort-header">По площадкам</p>
        <div className="search-platform">{...PL}</div>
        <p className="sort-header">По оценкам</p>
        <div className="search-star">{...ST}</div>
        {DOP.length != 0? <p className="sort-header">Дополнительно</p>:<br/>}
        <div className="search-tab">
             { ...DOP}
            </div>
            
    </div>
    
    

 }