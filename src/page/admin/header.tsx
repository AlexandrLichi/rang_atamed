
import { my_cookie } from "../../library/cookie";
import { integ } from "../../rocet/core/integration";
import { transition } from "../../router/api";
import { news } from "../component/news-masseng";
import { Select } from "../component/select-header";

export function Header(props:{data:any}){
    
    function onselect(id:any){
        my_cookie.filial = id;
    }

    function exit(){
        my_cookie.auth = ''
        my_cookie.enter = ''
        transition('/')
    }


    return <header className="admin-header">
            <div className="center-header">
                <div className="btn-block">
                  <logo onclick={()=> transition('/tech')}>Reviews And Ratings</logo>
                   
                  <Select content={props.data.filial} start={my_cookie.filial? my_cookie.filial:false} onchange={onselect} />
                </div>
                <div className="btn-block">
                    <button onclick={exit}>Выйти</button>
                </div>
            </div>
        </header>
}



export function Nav(props:{data:any}){

     function button_request(){
        if(my_cookie.filial != 'all'){
            transition('/feedback/'+props.data['api']+'/' + my_cookie.filial)
        }else{
            news('Выберете филиал в верхней панели', true);
        }
    }

    return  <nav>
                <ul>
                    <li><img src={require('../../images/icons8-график-blue.png')}></img><a href="/admin/analytics">Аналитика</a></li>
                    <li><img src={require('../../images/icons8-звезда-blue.png')}></img><a href="/admin/reviews">Отзывы</a></li>
                    <li><img src={require('../../images/icons8-аватар-64.png')}></img><a href="/admin/request">Запросы отзывов</a></li>
                    <li><img src={require('../../images/icons8-my-filial.png')}></img><a href="/admin/myfilial">Мои филиалы</a></li>
                    <li><img src={require('../../images/icons8-настройки-blue.png')}></img><a href="/admin/setting">Настройки</a></li>
                    <li><img src={require('../../images/icon8-help-blue.png')}></img><a href="/admin/help">Помощь</a></li>
                </ul>
                <button className="btn-yellow" name="send_feedback" onclick={button_request}>Новый запрос</button>
           </nav>
}