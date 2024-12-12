import { my_cookie } from "../../../library/cookie";
import { integ } from "../../../rocet/core/integration";
import { NAME, Rocet } from "../../../rocet/core/rocet";
import { transition } from "../../../router/api";
import { POST, POSTF } from "../../../router/Request";
import { InputFile } from "../../component/inputFile";
import { news } from "../../component/news-masseng";


export function SampleClient( sample:any, namesPlatform:{[name:string]:string}, platformList:Array<string>, api:string, bonus:string){
   const content = new Rocet('sample')
    // console.log(sample);
    var TimeInput:any = null;


function contentBody(content:JSX.Element){

        return <div className="body" style={`color:${sample.style.color}; `}>
                            <style>{
                            `button{
                                color: ${sample.button.color};
                                background: ${sample.button.background};
                                border: ${sample.button.border};
                                border-radius: ${sample.button['border-radius']};
                                }`
                                }
                            </style>
                            <div className="header">
                                <img src={"/view/static/images/"+sample.images.header+"?"+sample.hash}> </img>
                            </div>
                                    {content}
                            <div className="footer">
                                <img src={"/view/static/images/"+sample.images.footer+"?"+sample.hash}> </img>
                            </div>
    </div>
}


function platformlists():Array<JSX.Element>{
    let list:Array<JSX.Element> = []
        platformList.forEach((pl)=>{
            list.push( <p class="type-input"  id="platform-button">
                                <img src={`/view/images/search-${pl}.png`} />
                                <b style="margin: 0px 10px; padding: 6px 0px 0px 0px;">{namesPlatform[pl]}</b>
                        </p>)
        })

    return list;
}

// добавляем файлы
function putFile(file:any, path1:string, path2:string){
    let rash = file.name.split('.').reverse()[0];
    let name = `${path1}-${path2}-${api}.${rash}`
    sample[path1][path2] = name;
    sample['hash'] = new Date().getTime()
   
    POSTF('/admin/settig/sample-image',{name:name,sample:sample}, {file:file}).then((response)=>{
        response.text().then((data)=>{
          SampleClient(sample, namesPlatform, platformList, api, bonus);
          news("Файл загружен имя файла "+ name, false);
        })
    })
}

function putText(text:any, path1:string, path2:string|null){
    // проверка на вшивость 
    let contr = true;
    ['<?', '?>','<?php', '<?=','<script>','<script', '<style>', "<style", "</script", "</style"].forEach((str:string)=>{
        if(text.target.value.includes(str)) return contr = false 

    })


    if(!contr) return news('Нельзя вводить код php или скрипты', true)
    if(path2 == null){
         sample[path1] = text.target.value;
    }else{
        sample[path1][path2] = text.target.value;
    }

     if(TimeInput) clearTimeout(TimeInput);

           TimeInput = setTimeout(()=>{
                // положение курсора
               let cursor = text.target.selectionStart

              POST('/admin/settig/sample-text', {sample:sample}).then((request)=>{
                
                     SampleClient(sample, namesPlatform, platformList, api, bonus);
                     news("Текст обновлен", false);
                     let el:any = document.querySelector(`#${path1}-${path2}`)
                     el.focus()
                     el.setSelectionRange(cursor, cursor)
              
              })
           },1500)
}


function ReloadSample(){
    POST('/admin/settig/sample-text', {reload:'reload'}).then((request)=>{
        transition('/admin/setting');
        news('Шаблон вернулся к базовым настройкам', false)
    })
}

function TestTel(){
     let tel = NAME['input-tel-test'].value
     if(tel == '') return news('Введите номер телефона', true);
     if(my_cookie.filial == 'all') return news('Выберете любой филиал в верхней панели', true);
      POST('/admin/settig/sample-test', {telefon:tel, api:api, filialId:my_cookie.filial}).then((request)=>{
        news(request.request, false);
      })
}


    
   content.render(()=>{
    return <div className="sample">

        <h2>Настрока шаблона отзыва клиента</h2>
        <div className="user-client">
            <div className="row">

            <div className="icon-voscl"><img src={require('./../../../images/icons8-восклицательный-знак-96.png')}></img></div>
            <div style={"display:flex; flex-direction: column;  justify-content: center;"}>

                <i>Настройки шаблона отзыва клиетна. Есть два пути положительный (позитивный) и отрицательный (негативный).</i>
                <i>Слева расположены текстовые поля с право расположены вид шаблона страницы </i>
                <i>{ `Каждое текстовое поле содержит переменные <b>{clinic} - Имя филиала</b>, которое выбрал клиент и <b>{name} - Имя клиента</b>`}</i>
                <i>В редактор остнащен автосохранением через 1,5 сек бездействия в текстовых полях. Все изменения сразу применяються на стороне клиентской части и отоброжаются в панели</i>
                <i>Можете изменить цвет фона и цвет кнопок в разделе Экран 5 изменения применяться на все кнопки шаблона</i>
                <i>Запрещенно! Вносить тег style script и php код в текстовые поля</i>
                <i>Текстовые поля поддерживают HTML код </i>
                <i>Нельзя изменять кнопки площадок (Google, Yandex и т.д.) и текст кнопок</i>
                <i>Можете добавить свои кнопки к примеру WhatsApp или Телеграмм</i>
                <i>Если есть потребность убрать финишную картинку замените ее прозрачной png</i>
                <i>Header и Footer могут принимать картику <b>ширина 1000px  высота 330px</b></i>
                <i>Удачной настройки шаблона!</i>
            </div>
            </div>
        </div>
        <div className="user-client">
             <p><b>Вернуться к базовому шаблону </b><i> (все ваши настойки шаблона будут удалены применены стандартные)</i></p>
            <div><button className="reload-btn" onclick={ReloadSample}>Reload</button></div>
            <br/>
            <hr/>
            <br/>
            <p><b>Протестировать </b><i> (oтправить тестовое сообщение)</i></p>
            <input className="input-tel-test" name="input-tel-test" type="tel" placeholder="Номер телефона"></input>
            <div><button className="reload-btn" onclick={TestTel}>Test</button></div>
            <br/>
            <hr/>
            <br/>
             <p><b>При какой оценки считаться позитивный путь клиента (по умолчанию 4)</b><br/><br/><i> Вы можете установить свою оценку позитивного отзыва: К примеру если установить оценку 4, тогда пациент если выберет 4 или 5 (кол. звезд) ему будут показаны площадки (позитивный путь) при остальных он переведеться на негативный путь. Если установить оценку 3 тогда в позитивный путь будут входить 3, 4, 5. В настройках нет оценки 1, так как исключить негативный путь нельзя (теряется смысл всего приложения).</i></p>
             <div style={"display: flex;  align-items: center;"}><b style={'margin: 10px;'}>Оценка: </b>
             <select className="select-tab" onchange={ (evt:any)=>putText(evt, 'positive', null)}>
                <option selected={Number(sample.positive) == 2}>2</option>
                <option selected={Number(sample.positive) == 3}>3</option>
                <option selected={Number(sample.positive) == 4}>4</option>
                <option selected={Number(sample.positive) == 5}>5</option>
             </select>
             </div>
            {/* <input className="input-tel-test" name="input-tel-test" type="tel" placeholder="Номер телефона"></input> */}
            {/* <div><button className="reload-btn" onclick={TestTel}>Test</button></div> */}
        </div>
        <div className="user-client">

            <h3>Экран 1 звезды</h3>
            <div className="row">
                <div className="constructor">
                    <p>Заголовок картинка</p>
                    <InputFile onchanges={(file:any) => putFile(file, "images", 'header')}  value={sample.images.header}></InputFile>
                    <p>Подвал картинка</p>
                    <InputFile  onchanges={(file:any) => putFile(file, "images", 'footer')}  value={sample.images.footer}></InputFile>
                    <p>Иконка "Звезда" Активная</p>
                    <InputFile  onchanges={(file:any) => putFile(file, "iconStar", 'active')}  value={sample.iconStar.active}></InputFile>
                    <p>Иконка "Звезда" Неактивная</p>
                    <InputFile  onchanges={(file:any) => putFile(file, "iconStar", 'deactive')}  value={sample.iconStar.deactive}></InputFile>
                    <p>Текст Заголовок</p>
                    <textarea value={sample.TextStars.header} onkeyup={(evt:any)=>putText(evt,'TextStars', 'header')}  id="TextStars-header"></textarea>
                    <p>Текст Body</p>
                    <textarea value={sample.TextStars.body} onkeyup={(evt:any)=>putText(evt,'TextStars', 'body')} id="TextStars-body"></textarea>
                </div>

                <div className="interactive">
                            {contentBody( <div className="content" style={'background:'+sample.style['background-color']+";"}>
                          <div>{sample.TextStars.header + sample.TextStars.body}</div>
                                       <div className="stars">
                                        <div className="star"><img src= {"/view/static/images/"+sample.iconStar.active+"?"+sample.hash} /></div>
                                        <div className="star"><img src= {"/view/static/images/"+sample.iconStar.active+"?"+sample.hash} /></div>
                                        <div className="star"><img src= {"/view/static/images/"+sample.iconStar.active+"?"+sample.hash} /></div>
                                        <div className="star"><img src= {"/view/static/images/"+sample.iconStar.deactive+"?"+sample.hash} /></div>
                                        <div className="star"><img src= {"/view/static/images/"+sample.iconStar.deactive+"?"+sample.hash} /></div>
                                    </div>
                              <button>Отправить</button>
                          </div>)}
                </div>
             </div>
        </div>

        {/* Путь позитива */}
         <div className="user-client">

            <h3>Экран 2 "Позитивный путь клиента" </h3>
            <div className="row">
                 <div className="constructor">
                    <p>Текст Заголовок</p>
                    <textarea value={sample.TextPositive.header}  onkeyup={(evt:any)=>putText(evt,'TextPositive','header')} id="TextPositive-header"></textarea>
                    <p>Текст Body</p>
                    <textarea value={sample.TextPositive.body}  onkeyup={(evt:any)=>putText(evt,'TextPositive','body')} id="TextPositive-body"></textarea>
                    <p>Текст попись под бонусами</p>
                    <textarea value={sample.TextPositive.dop}  onkeyup={(evt:any)=>putText(evt,'TextPositive','dop')} id="TextPositive-dop"></textarea>
                 </div>
                  <div className="interactive">
                         {contentBody(<div className="content" style={'background:'+sample.style['background-color']+";"}>
                            <div>{sample.TextPositive.header + sample.TextPositive.body}</div>
                             <p class="type-input">{bonus}</p>
                             <div>{sample.TextPositive.dop}</div>

                              <div class="platform">
                                 {...platformlists()}
                              </div>
                         </div>)}
                  </div>

            </div>
         </div>

          <div className="user-client">
            <h3>Экран 3 "Позитивный путь клиента" финальный экран</h3>
            <div className="row">
                 <div className="constructor">
                     <p>Текст Заголовок</p>
                    <textarea value={sample.TextPositiveConfirm.header}  onkeyup={(evt:any)=>putText(evt,'TextPositiveConfirm','header')} id="TextPositiveConfirm-header"></textarea>
                    <p>Текст Body</p>
                    <textarea value={sample.TextPositiveConfirm.body} onkeyup={(evt:any)=>putText(evt,'TextPositiveConfirm','body')} id="TextPositiveConfirm-body"></textarea>
                    <p>Текст подпись</p>
                    <textarea value={sample.TextPositiveConfirm.dop} onkeyup={(evt:any)=>putText(evt,'TextPositiveConfirm','dop')} id="TextPositiveConfirm-dop"></textarea>
                    <p>Текст в конце</p>
                    <textarea value={sample.TextPositiveConfirm.fut} onkeyup={(evt:any)=>putText(evt,'TextPositiveConfirm','fut')} id="TextPositiveConfirm-fut"></textarea>
                 </div>


                  <div className="interactive">
                         {contentBody(<div className="content" style={'background:'+sample.style['background-color']+";"}>
                            <div>{sample.TextPositiveConfirm.header +sample.TextPositiveConfirm.body}</div>
                            <h3 style="margin-bottom: 0px;">Начислим</h3>
                            <p class="type-input">{bonus}</p>
                            <div>{sample.TextPositiveConfirm.dop}</div>
                            <p style="margin-bottom: 0px;">Скопировать имя</p>
                            <p class="type-input">Иванов Иван Иванович</p>
                            <div>{sample.TextPositiveConfirm.fut}</div>
                            <button style="margin-top: 50px;">Понятно, перейти на сайт Яндекс Карты</button>
                         </div>)}
                  </div>

            </div>
         </div>

         {/* Путь негатива */}
          <div className="user-client">

            <h3>Экран 4 "Негативный путь клиента"</h3>
            <div className="row">

                 <div className="constructor">
                    <p>Текст заголовок</p>
                    <textarea value={sample.TextNegativeConfirm.header} onkeyup={(evt:any)=>putText(evt,'TextNegativeConfirm','header')} id="TextNegativeConfirm-header"></textarea>
                    <p>Текст Body</p>
                    <textarea value={sample.TextNegativeConfirm.body} onkeyup={(evt:any)=>putText(evt,'TextNegativeConfirm','body')} id="TextNegativeConfirm-body"></textarea>
                    <p>Текст подпись</p>
                    <textarea value={sample.TextNegativeConfirm.dop} onkeyup={(evt:any)=>putText(evt,'TextNegativeConfirm','dop')} id="TextNegativeConfirm-dop"></textarea>
                     <p>Tекст кнопки</p>
                    <input type='text' value={sample.button4.text} onkeyup={(evt:any)=>putText(evt,'button4','text')} id="button4-text"></input>
                 </div>

                  <div className="interactive">
                         {contentBody(<div className="content" style={'background:'+sample.style['background-color']+";"}>
                            <div>{sample.TextNegativeConfirm.header +sample.TextNegativeConfirm.body + sample.TextNegativeConfirm.dop.replace('{bonus}', bonus)}</div>
                            <button>{sample.button4.text}</button>
                         </div>)}
                  </div>
            </div>
         </div>

        {/* Негативный путь текстовое поле */}
         <div className="user-client">
            <h3>Экран 5 "Негативный путь клиента" текстовое поле</h3>
            <div className="row">

                 <div className="constructor">
                 <p>Текст Заголовок</p>
                 <textarea value={sample.TextNegative.header} onkeyup={(evt:any)=>putText(evt,'TextNegative','header')} id="TextNegative-header"></textarea>
                 <h3>Настройка стилистилей кнопок</h3>
                 <p>Цвет текста</p>
                 <input type="text" value={sample.button.color} onkeyup={(evt:any)=>putText(evt,'button','color')} id="button-color"></input>
                 <p>Цвет заливки</p>
                 <input type="text" value={sample.button.background} onkeyup={(evt:any)=>putText(evt,'button','background')} id="button-background"></input>
                 <p>Бордюр (толщина и цвет)</p>
                 <input type="text" value={sample.button['border']} onkeyup={(evt:any)=>putText(evt,'button','border')} id="button-border"></input>
                 <p>Радиус бордюра</p>
                 <input type="text" value={sample.button['border-radius']} onkeyup={(evt:any)=>putText(evt,'button','border-radius')} id="button-border-radius"></input>
                 <h3>Цвет фона</h3>
                 <input type="text" value={sample.style['background-color']} onkeyup={(evt:any)=>putText(evt,'style','background-color')} id="style-background-color"></input>
                 </div>

                  <div className="interactive">
                         {contentBody(<div className="content" style={'background:'+sample.style['background-color']+";"}>
                            <div>{sample.TextNegative.header}</div>
                            <textarea placeholder="Опишите подробно"></textarea>
                            <button>Отправить</button>
                         </div>)}
                  </div>
            </div>
         </div>

         {/* Финишный экран */}
          <div className="user-client">

            <h3>Экран 6 "Негативный путь клиета" финиш</h3>
            <div className="row">

                 <div className="constructor">
                    <p>Текст Заголовок</p>
                    <textarea value={sample.Finish.header} onkeyup={(evt:any)=>putText(evt,'Finish','header')} id="Finish-header"></textarea>
                    <p>Текст Body</p>
                    <textarea value={sample.Finish.body}  onkeyup={(evt:any)=>putText(evt,'Finish','body')} id="Finish-body"></textarea>
                    <p>Картинка Финишная</p>
                    <InputFile onchanges={(file:any) => putFile(file, "Finish", 'images')}  name="input-file" value={sample.Finish.images}></InputFile>
                 </div>

                  <div className="interactive">
                         {contentBody(<div className="content" style={'background:'+sample.style['background-color']+";"}>
                            <div>{sample.Finish.header + sample.Finish.body}</div>
                            <img class='serd' src={"/view/static/images/"+sample.Finish.images +"?"+sample.hash}></img>
                         </div>)}
                  </div>
            </div>
         </div>
        <br/><br/><br/>
    </div>
   })
}