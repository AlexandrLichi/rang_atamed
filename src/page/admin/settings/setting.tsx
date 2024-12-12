import { integ } from "../../../rocet/core/integration";
import { NAME, Rocet, VALUE } from "../../../rocet/core/rocet";

import '../../../CSS/setting-admin.scss'

import { news } from "../../component/news-masseng";
import { Header, Nav } from "../header";

import { PUT } from "../../../router/Request";

import { transition } from "../../../router/api";
import { SampleClient } from "./sample-client";
import { MessageSample } from "./setting.sample.message";


export function SettingAdmin(data:any){
    const context = new Rocet('context')
       

        context.ExecAfter= [
            ()=>SampleClient(data['sample-client'], data['names-platform'], data['platform-list'],  data['api'], data['sample_bonus']),
            ()=>MessageSample(data),
        ]
        const U:Array<JSX.Element> = []

        data['users'].forEach((user:any)=>{
            U.push(<div className="row-user">
                 <b data="Name">{user.name}</b>
                 <b data="Login">{user.email}</b>
                 <b className="pas" data="Password"></b>
                 <button  onclick={(evt:any)=>updatePassword(evt, user.email )}>Обновить пароль</button>
          </div>)
        })

        U.push(<div className="row-user">
           <input type="text" name="name" placeholder="Name"/>
           <input type="text" name="email" placeholder="Email"/>
            <b className="pas" data="Password"></b>
             <button   onclick={newUser}>Добавить</button>
        </div>)

           
    //         // новый пользователь 
        function newUser(evt:any){

            if(NAME['name'].value == ''|| NAME['email'].value == '') return news("Заполните все поля", true)

                VALUE['company_id'] = data['user']['company_id'];
            
                PUT('/admin/setting/new-users',{ "newUser":VALUE }).then((put)=>{
                    console.log(put);
                    if(put['password']){
                        evt.target.parentElement.querySelector('.pas').textContent = put['password']
                        news('Создан новый пользователь запишите пароль', false)
                    }else{
                        news('Такой пользователь существует', true)
                    }
                })
   
        }


    //     // обновление пароля
        function updatePassword(evt:any, email:string){
              PUT('/admin/setting/update-password',{ "email":email }).then((put)=>{
                    if(put['password']){
                        evt.target.parentElement.querySelector('.pas').textContent = put['password']
                        news('Создан новый пользователь запишите пароль', false)
                    }else{
                        news('Упс что-то пошло не так', true)
                    }
                })
        }



    const select_imap:Array<JSX.Element> = []
    const ImapBlok:Array<JSX.Element> = []
    data['filial'].forEach((filial:any)=>{
        if(filial['imap']){
            let imap = JSON.parse(filial['imap']);
            ImapBlok.push(
                        <div className="row-user">
                            <div className="column">
                               <i>Параметры  почты</i>
                            <input type="text" name="imap" placeholder="imap" value={imap.imap}/>
                            <input type="text" name="port" placeholder="port" value={imap.port}/>
                             <input type="text" name="user_imap" placeholder="port" value={imap.user_imap}/>
                            <input type="text" name="password_imap" placeholder="password" value={imap.password_imap}/>
                            </div>
                            <div className="column">
                                  <i>Параметры для филиала</i> 
                                    <input type="text" name="prefix" placeholder="prefix|2prefix" value={imap.prefix.join('|')}/>
                                    <input type="text" name="id" data={filial['id']} value={filial['filial']} disabled/>
                            </div>
                            <button onclick={setImap}>Cохранить</button>
                        </div>
                   )
        }
        select_imap.push(<option value={filial['id']}>{filial['filial']}</option>)
    })
   

    



        function setImap(evt:any){
            let elm = evt.target.parentElement;
            let send:any = {}

                elm.querySelectorAll('input').forEach((input:HTMLInputElement)=>{
                    if(input.value != ''){
                        if(input.hasAttribute('data')){
                            send[input.name] = input.getAttribute('data');
                        }else{
                            if(input.name == 'prefix'){
                                send[input.name] = input.value.trim().split('|')
                            }else{
                                
                                send[input.name] = input.value.trim();
                            }
                        }

                    }else{
                        return news('Не все поля заполнены');
                    }
                })
                let  sel = elm.querySelector('select')
                if(sel){
                    send['id'] = sel.value
                }
               
            PUT('/admin/setting/set-imap', {imap:send}).then((put:any)=>{
                transition('/admin/setting')
                console.log(put);
            })

        }
    


    context.render(()=>{
        return <context>
            <Header data={data}/>
            <content>
                <Nav data={data}/>
        <content-body>
            <div className="setting-admin">
            <h1>Настройки</h1>
            <br/>
            <h2>Пользователи</h2>
            <div className="user-client">{...U}</div>
            <br/><br/>
            <h2>Настрока подключения почтовых отзывов</h2>
            <div className="user-client">
                <div className="row-user">
                <div className="column">
                  <i>Параметры  почты</i>
                  <input type="text" name="imap" placeholder="imap"/>
                  <input type="text" name="port" placeholder="port" value={'993'}/>
                  <input type="text" name="user_imap" placeholder="user"/>
                  <input type="text" name="password_imap" placeholder="password"/>
                </div>
                 <div className="column">
                    <i>Параметры для филиала</i> 
                    <input type="text" name="prefix" placeholder="prefix|2prefix"/>
                    <select>{...select_imap}</select>
                 </div> 
                  <button onclick={setImap}>Добавить</button>
                </div>
                {...ImapBlok}
            </div>
            {/* <h2>Канал негативных отзывов</h2>
            <div className="canal-negative">
                <p>*Перенапревление негативных отзывов на этот адресс</p>
                <div className="negative"></div>
            </div>
            <br/><br/>
            <h2>Грань позитива</h2>
             <div className="star-negative">
                 <p>*Выбере зведу от которой будут считься положительные отзывы</p>
                <star></star>
             </div> */}
             <br/><br/>

             <div className="sample">

             </div>
             <div className="sample-messange">

             </div>
            </div>
            
        </content-body>
        </content>
        </context>
    })

}