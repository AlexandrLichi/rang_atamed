import { start } from "@popperjs/core";
import { my_cookie } from "../../../library/cookie";
import { integ } from "../../../rocet/core/integration";
import { Rocet } from "../../../rocet/core/rocet";
import { Review } from "../../component/block-review";
import { InputSearch } from "../../component/input-search";
import { SearchReviews } from "../../component/search-panel-reviews";
import { REC } from "../request";
import { loaderDot, noneFind } from "../../component/loader-grose";
import { Admin } from "../admin";
import { FDate } from "../../../library/DateClass";
import { GET, POST } from "../../../router/Request";
import { select } from "../../../rocet/core/rocet_elements";
import { Header, Nav } from "../header";


export interface search{
    filial_id:string|null|undefined
    platform:Array<string>|undefined
    star:Array<string>|undefined
    text?:string|null|undefined
    st?:string|undefined
    en?:string|undefined
    
}

export function Reviews(data:any){

    const rocet = new Rocet('context')
    let ReviewsAll:Array<JSX.Element> = [];
    let search:search;
    let TimeInput:any = null

    
    
    search = {
        filial_id: my_cookie.filial? my_cookie.filial : 'all',
        platform:[],
        star:[],
        st: new FDate().MA(),
        en: new FDate()["YYYY-MM-DD"](),
    }
     


    rocet.ExecAfter = [
        ()=>select(),
        ()=>filterButton(search),
    ]
    
    const select = ()=>{
        let selectblock:HTMLElement = document.querySelector('.block-select')

        selectblock.onclick = function(){
            if(search.filial_id != my_cookie.filial){
                search.filial_id =  my_cookie.filial;
                filterButton(search);
            }
        }

    }
    
    function filterButton(search:search){
       
         loaderDot('rewies-my')

            GET('/admin/reviews', {search:search}).onload = function(){
                // return console.log(data);
                data = JSON.parse(this.responseText);
                rerenderingRewiews(data['reviews'])
            }

        }

        function inputSaerch(data:any){
           search.text = data.target.value

           if(TimeInput) clearTimeout(TimeInput);

           TimeInput = setTimeout(()=>{
                filterButton(search);
           },1300)
        }


        function rerenderingRewiews(rewies:Array<any>){

            const h1 = new Rocet('h1')

            h1.render(()=>{return <h1 data={String(rewies.length)}>Отзывы</h1>})
            const filter = new Rocet('.search')
            filter.render(()=><SearchReviews prefix={data['prefix']} platform={data['platform-list']}  onclick={filterButton} search={search}></SearchReviews>)


            const rew = new Rocet('.rewies-my')
            if(rewies.length == 0) return noneFind("rewies-my")
            ReviewsAll = []

            data['reviews'].forEach((el:any)=>{
             ReviewsAll.push(<Review 
                            name={el.name} 
                            text={el.text} 
                            area={el.platform} 
                            stars={el.star} 
                            answer={{answer:el.answer, dateanswer:el['dateanswer']}} 
                            date={el.date} 
                            img={el.img} 
                            />)
                })

            rew.render(()=>{return <div className="rewies-my">{...ReviewsAll}</div>})
        }

rocet.render(()=>{
            return <context>
                        <Header data={data}/>
                        <content>
                            <Nav data={data}/>
                                    <content-body>
                                        <div className="list-reviews">
                                        <h1 data='0'>Отзывы</h1>
                                        <InputSearch onkey={inputSaerch} text={null}/>
                                        <div className="rewies-my">
                                            
                                        {...ReviewsAll}

                                        </div>
                                        </div>
                                        <filter>
                                            <SearchReviews platform={data['platform-list']} prefix={data['prefix']} onclick={filterButton} search={search}></SearchReviews>
                                        </filter>
                                    </content-body>
                                </content>
                    </context>
})
    

}