import { integ } from "../../../rocet/core/integration";
import { Rocet } from "../../../rocet/core/rocet";
import '../../../CSS/tech-cab.scss';
import { Tech } from "../tech-cab";
import { Router } from "../../../router/api";

export function Companies(data:any){
    if(!document.querySelector('content-body')) Tech(data);
    console.log(data)

    const context = new Rocet('content-body')

    const TR:Array<JSX.Element> = [<tr>
        <th>API</th>
        <th>Название</th>
        <th>Платформы</th>
        <th>Филиалы</th>
        <th>К выгрузке</th>
    </tr>]

    data['company'].forEach((company:any)=>{
        let PL:Array<JSX.Element> = [];
        let FL:Array<JSX.Element> = [];
        let parsers:any = [];
        let fill:any = []
      data['parser'].forEach((pl:any)=>{
         let nameParser:any = find(data['list'] ,'id', pl.parser_id)['name'];
         console.log(nameParser)

         if(!parsers.includes(nameParser)){
             parsers.push(nameParser)
             PL.push(<p>{nameParser}</p>)
         }

         if(!fill.includes(pl.filial)){
             fill.push(pl.filial)
             FL.push(<p>{pl.filial}</p>)
         }
      })
      

        TR.push(<tr>
            <td>{company.api}</td>
            <td>{company.company}</td>
            <td>{...PL}</td>
            <td>{...FL}</td>
            <td><a href={'/tech/download/'+company.api}>Перейти</a></td>
        </tr>);
    })

    context.render(()=>{
        return <content-body>
            <div className="tech-table" >
                <h1>Компании</h1>
                <table >{...TR}</table>
            </div>
        </content-body>
    })
   
}

export function find(Arr:Array<any>, name:string, value:string|number):any{
//   console.log(Arr);
for(let i=0; i<Arr.length; i++){
    if(Arr[i][name] == value) return Arr[i];
}

}