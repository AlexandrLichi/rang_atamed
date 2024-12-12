

import { get_cookie, my_cookie } from "../../library/cookie";
import { POST_MY } from "../../library/GetPost";
import { IsJsonString } from "../../library/json";
import { integ } from "../../rocet/core/integration";
import { NAME, Rocet, VALUE } from "../../rocet/core/rocet";
import { Router, transition } from "../../router/api";
import { POST } from "../../router/Request";


export function Login(){

    if(my_cookie.enter){
        window.history.pushState({},'','./admin')
        return Router('./admin') 
    }

    const context  = new Rocet('context')
    function enter(evt:any){
        evt.preventDefault()
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


        
          
           POST('/login',
                {

                password:NAME['password'].value,
                login:NAME['login'].value

                }).then((data)=>{
                   
                    if(data['enter']) return transition('/admin');
                    if(data['login']){
                        NAME['login'].parentElement.setAttribute('data', data['login'])
                        NAME['login'].parentElement.classList.remove('deactiv')
                        return;
                    }

                    if(data['password']){
                         NAME['password'].parentElement.setAttribute('data', data['password'])
                         NAME['password'].parentElement.classList.remove('deactiv')
                         return;
                    }
                   
                }) 

        

       }
    }
    

    // function entr(evt:any){
    //         if(evt.key == 'Enter') enter()
    // }





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
            <form className="form-enter">
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
            </form>
                </div>
        </context>
    })
}