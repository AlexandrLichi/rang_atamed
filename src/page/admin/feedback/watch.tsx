import { FDate } from "../../../library/DateClass";
import { integ } from "../../../rocet/core/integration";
import './../../../CSS/component/watch.scss'

export function watch(history: Array<{ [name: string]: string }> | undefined , date_time:string, platform:string): JSX.Element {
    let JSX:Array<JSX.Element> = []

    
    function showmess(divmes:HTMLElement){
       let div = document.createElement('div')
       div.className = 'mess-modal'
       let  button =document.createElement('button')
       button.textContent = 'x'
       button.onclick = ()=>document.querySelector('.mess-modal').remove(); 
       divmes.append(button);
       div.append(divmes)
       document.querySelector('body').append(div)
    }

    if (history) {
        history.forEach((h) => {
            let time = h['date-time'].split(' ')[1]
            let date = h['date-time'].split(' ')[0]
            let status = h['status']
            let string = h['comment']
            let divmes = document.createElement('div')
            divmes.textContent = h['comment']??'';
            if (status == 'CONNECT') {
                JSX.push(<div className="icon dost" data={"Ссылка активирована " + new FDate(date)['DD.MM.YYYY']()}><img src={require('./../../../images/mess.png')} /></div>)
            }
            if (status == 'STAR') {
                JSX.push(<span />)
                JSX.push(<div className="icon" data={"Поставил оценку " + new FDate(date)['DD.MM.YYYY']() + " Время: " + time}><p>{string}</p></div>)
            }

            if (status == "POSITIVE") {
                JSX.push(<span />)
                
                JSX.push(<div className="icon icon-pl" data={"Перешел на платформу " + string + " " + new FDate(date)['DD.MM.YYYY']() + " Время: " + time}>
                   <img src={require('./../../../images/search-' + string + '.png')} />
                </div>)
            }
            if ((status == "NEGATIVE" || status == "FAMILY") && divmes.textContent != '' ) {
                JSX.push(<span/>)
                JSX.push(<div className="icon icon-pl" data={"Оставил  сообщение"} onclick={()=>showmess(divmes)}><img src={require('./../../../images/mess-text.png')} /></div>)
            }
            if (status == "DESTROYED") {
                JSX.push(<span/>)
                JSX.push(<div className="icon icon-pl" data={"Ссылка удалена от времени ожидания"} onclick={()=>showmess(divmes)}><img src={require('./../../../images/icons8-cancel.png')} /></div>)
            }
        })
    }else{
        JSX.push(<div className="icon send" data={"Сообщение отправлено "+ new FDate(date_time.split(' ')[0])['DD.MM.YYYY']()}><img src={require('./../../../images/mess.png')} /></div>)
    }

    return <div className="watch-path">
        {...JSX}
    </div>
}