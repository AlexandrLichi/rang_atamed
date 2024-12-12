import { POST_MY } from "../../library/GetPost";
import { my_cookie } from "../../library/cookie";
import { IsJsonString } from "../../library/json";
import { integ } from "../../rocet/core/integration";
import { NAME, Rocet, VALUE } from "../../rocet/core/rocet";
import { Admin } from "../admin/admin";

export function form_enter(){

    if(my_cookie.key){
        window.location.href = './admin'
        return
    }

    const context  = new Rocet('context')
    function enter(){
    let formTo = true;


     if(NAME['password'].value  == ''){
            NAME['password'].parentElement.setAttribute('data', 'Введите пароль')
            NAME['password'].parentElement.classList.remove('deactiv')
            formTo = false; 
     }else{
        NAME['password'].parentElement.classList.add('deactiv')
     }

     if(NAME['login'].value == ''){
        console.log(NAME['login'].value)
            NAME['login'].parentElement.setAttribute('data', 'Введите логин')
            NAME['login'].parentElement.classList.remove('deactiv')
            formTo = false; 
     }else{
        NAME['login'].parentElement.classList.add('deactiv')
     }
    // Если все ок отправляем данные 
       if(formTo){
            console.log(NAME['password'].value)
            console.log(NAME['login'].value)

            POST_MY('./PHP/security/enter.php', 'enter', JSON.stringify(
                {
                password:NAME['password'].value,
                login:NAME['login'].value
                }
            )).onload = function (){
                if(IsJsonString(this.responseText)){
                   let api = JSON.parse(this.responseText)
                   let url = window.location.href.split('#')[0];
                   location.replace(url+'./admin')

                }else{
                    if(this.responseText == 'Неверный логин'){
                        NAME['login'].parentElement.setAttribute('data', this.responseText)
                        NAME['login'].parentElement.classList.remove('deactiv')
                    }
                    if(this.responseText == 'Неверный пароль'){
                         NAME['password'].parentElement.setAttribute('data', this.responseText)
                         NAME['password'].parentElement.classList.remove('deactiv')
                    }
                        console.error(this.responseText)
                }
                
            }
       }
    }
    

    function entr(evt:any){
            if(evt.key == 'Enter') enter()
    }





    context.render(()=>{
        return<context>
            <header className="home-header">
            <div className="center-header">
                <logo>Reviews And Ratings</logo>
                <div className="btn-block">
                    <a href="./">Назад</a>
                </div>
            </div>
        </header>
        <div className="div-center">
            <div className="form-enter" onkeydown={entr}>
                <span className="decore-1"></span>
                <span className="decore-2"></span>
                <span className="decore-3"></span>
                <span className="decore-4"></span>
                <h2>Вход в личный кабинет</h2>
                <span className="decore-5"></span>
                <span className="decore-6"></span>
                <span className="decore-7"></span>
                <span className="decore-8"></span>
                <div className="contur deactiv" data="Неверный логин"><input type="text" name="login" placeholder="login/email"></input></div>
                <div className="contur deactiv" data="Неверный пароль"><input type="password" name="password" placeholder="password"></input></div>

                <button onclick={enter}>Войти</button>
            </div>
        </div>
        </context>
    })
}