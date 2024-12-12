import { integ } from "../../rocet/core/integration";
import { Rocet } from "../../rocet/core/rocet";
import '../../CSS/home.scss'
import { form_enter } from "../component/form-enter";


export function Home(option:any){


    const context  = new Rocet('context')
        context.ExecAfter = [
        ()=>{if(option.hash == "enter") form_enter() }
    ]
    console.log(option)



    context.render(()=>{
        return<context>
        <header className="home-header">
            <div className="center-header">
                <logo>Reviews And Ratings</logo>
                <div className="btn-block">
                    <a>Начать с нами</a>
                    <a href="./login">Войти</a>
                </div>
            </div>
        </header>
        <content>
            <div className="div-center">
                <h1>Сервис для продвижения в картах</h1>
                <h2>Все отзывы на одном сайте</h2>
                <ul>
                    <li><img src={require('../../images/search-yandex.png')}/><p>Yandex</p></li>
                    <li><img src={require('../../images/search-google.png')}/><p>Google</p></li>
                    <li><img src={require('../../images/search-prodoctorov.png')}/><p>ПроДокторов</p></li>
                    <li><img src={require('../../images/search-popravku.png')}/><p>НаПоправку</p></li>
                    
                </ul>
            </div>
        </content>
        </context>
    })
}