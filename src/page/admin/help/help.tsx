import { integ } from "../../../rocet/core/integration";
import { Rocet } from "../../../rocet/core/rocet";
import { Header, Nav } from "../header";

export function Help(data:any){
    const context = new Rocet('context')


    context.render(()=>{
        return<context>
         <Header data={data}/>
        <content>
            <Nav data={data}/>
            <content-body>
            <div className="admin-home">
                <h1>Помощь</h1>
                <div className="block-help">
                    <h2>Как встроить виджет рейтинга?</h2>
                    <p>На вашей старнице там где хотите добавить визуализацию требуеться добавить в тег head СSS и JS  подключения.</p>
                    <div className="code">

                    <code data='1'>{`<link rel="stylesheet" href="${window.location.origin}/widget/css">`}</code>
                    <code data='2'>{`<script src="${window.location.origin}/widget/js"></script>`}</code>

                    </div>
                    <br></br>
                        <p>После разместите в нужном вам месте старници тег там где хотите чтобы показывался</p>
                        <div className="code">
                            <code data='1'>{`<widgets-rang></widgets-rang>`}</code>
                        </div>
                    <br></br>
                    <p>В тег body поместите скрипт. Для виджета требуются базовые настройки  в зависимости от ваших требований</p>
                         <div className="code">
                            <code data='1'>const Widget =  new Widgets();</code>
                            <code data="2"> Widget.api = 'ваш api компании'; // обязательный параметр</code>
                            <code data="3"> Widget.filial = ['ваш филиал1', 'ваш филиал2'] // Обязательный параметр</code>
                            <code data="4"> Widget.platform = ['google', 'yandex'] // Неoбязательный параметр если не указывать платформы, тогда виджет выведет все площадки</code>
                            <code data="5"> Widget.header = true // Неoбязательный параметр, показывать или скрывать заголовки</code>
                            <code data='6'> Widget.startKeys(); // запускает виджет</code>
                        </div>
                </div>
            </div>
            </content-body>
        </content>
        </context>
    })
}