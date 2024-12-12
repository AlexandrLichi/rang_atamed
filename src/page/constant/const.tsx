 
 
export const STATUS:any = new Proxy( {
            CONNECT: "Перешел по ссылке",
            STAR: "Поставил оценку",
            POSITIVE: "Перешел на платформу ",
            NEGATIVE: "Оставил негативный отзыв",
            FAMILY: "Отзыв оставлен на площадке " ,
            MACHINE:"Отзыв оставлен на площадке " ,
            NAME:"Отзыв оставлен на площадке " ,
            platform:""
        },{
            get:function(target:any, prop:string){

                if(["MACHINE", "NAME", "FAMILY", "POSITIVE"].includes(prop)){
                  return target[prop] + target['platform'] ;
                }else{
                  return target[prop]
                }
            }
        });


    



