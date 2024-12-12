import { integ } from "../../../rocet/core/integration";
import { Rocet } from "../../../rocet/core/rocet";
import { body } from "../../../rocet/core/rocet_elements";
import { POST, POSTF } from "../../../router/Request";
import { InputFile } from "../../component/inputFile";
import { news } from "../../component/news-masseng";

export function MessageSample(data:any){
    const context = new Rocet('sample-messange');
    let TimeInput:any = null;

    function onsendBonus(evt:any){

         if(TimeInput) clearTimeout(TimeInput);
           TimeInput = setTimeout(()=>{
                // положение курсора
               let cursor = evt.target.selectionStart

              POST('/admin/settig/sample-bonus', {sample_bonus: evt.target.value}).then((request)=>{

                     news("Бонус сохранен", false);
                     let el:any = document.querySelector(`input[name=sample_bonus]`)
                     el.focus()
                     el.setSelectionRange(cursor, cursor)
              })
           },1500)
    }


    function downloadSampleImages(file:any){
        let pr = file.name.split('.').reverse()[0]
 
        POSTF("/admin/setting-mess-file",{sample_images:"images_wa-"+ data['api'] + "." + pr}, {file:file}).then((postF)=>{
            postF.text().then((postF)=>{
               news("Файл загружен под именем :images_wa-"+ data['api'] + "." + pr, false);
            });
        })
    }

    context.render(()=>{
        return <div className="sample-messange">
        <div className="user-client">
                <h3>Сообщение</h3>
                <i> Какое вознаграждение за отзыв будет указано в сообщении (по умолчанию 300 бонусов). </i>
                <input type="text" name="sample_bonus" className="input-tel-test" value={data['sample_bonus']} onkeydown={onsendBonus}/>
                <i> Картинка шаблона. </i>
                <InputFile onchanges={downloadSampleImages} placeholder="Картинка шаблона" value={data['sample_image_wa']}/>
        </div>
        </div>
    })
}