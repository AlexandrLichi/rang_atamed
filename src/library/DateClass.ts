import { runtime } from "webpack"


export class FDate{

    public Date:Date
    public D:string|number
    public M:string|number
    public Y:string|number
    public H:string|number
    public S:string|number
    public MIN:string|number
    public MS:string|number
    public timer:string|number
    constructor(data:string|Date|number|null = null){

      this.Date = data? this.date(data): new Date() 
      this.D = this.Date.getDate()
      this.M = this.Date.getMonth() + 1
      this.Y = this.Date.getFullYear()
      this.H = this.Date.getHours();
      this.MIN = this.Date.getMinutes();
      this.S = this.Date.getSeconds();
      this.MS = this.Date.getTime();
      this.timer = null
    }

   protected date(data:string|Date|number):Date{
    // let d , m , y 
    
    if(typeof data == 'string'){
        let redact = data.split('-')
        if(redact.length == 3){
            return new Date(data + "T00:00:00")
         }
     }
     if(typeof data == 'number'){
         return new Date(data)
     }

     
    }
    

    ['YYYY-MM-DD'](){
     return [this.Y, 
             Number(this.M) < 10? '0'+ this.M : this.M, 
             Number(this.D) < 10? '0'+ this.D:this.D
            ].join('-')
    }

    ['DD.MM.YYYY'](){
        return [ 
            Number(this.D) < 10? '0'+ this.D:this.D,
            Number(this.M) < 10? '0'+ this.M : this.M, 
            this.Y
            ].join('.')
    }
    ['HH:MM:SS'](){
        return[
            Number(this.H) < 10? '0'+ this.H: this.H,
            Number(this.MIN) < 10? '0'+ this.MIN : this.MIN, 
            Number(this.S) < 10? '0'+ this.S : this.S,
        ].join(':')
    }

    ['D Month YY'](){
    let YY = String(this.Y).slice(-2);
    let month = ['Янв', 'Фев', 'Мар', 'Апр', 'Мая', 'Июн', 'Июл', 'Авг', 'Сент','Окт', 'Нояб', 'Дек'];
    return [this.D, month[ Number(this.M) - 1 ], YY].join(' ') 
    }

    ['D Month'](){
        let month = ['Янв', 'Фев', 'Мар', 'Апр', 'Мая', 'Июн', 'Июл', 'Авг', 'Сент','Окт', 'Нояб', 'Дек'];
        return [this.D, month[ Number(this.M) - 1 ]].join(' ') 
    }

    public TIMER(MS:number|null = null){
        if(this.timer === null) this.timer = Number(this.MS) + Number(MS)
         let res = Number(this.timer) - new Date().getTime()
        if(res < 0) return '00:00:00'
         return this.formatTime(res);
    }

    public formatTime(ms:number){
        const date = new FDate(ms)
       
    
        return date["HH:MM:SS"]()
    }

    public YTD(){
      const d = this.Date.setDate( this.Date.getDate() - 1)
      return new FDate(d)['YYYY-MM-DD']()
    }
    // week ago
    public WA(){
         const d = this.Date.setDate( this.Date.getDate() - 7)
         return new FDate(d)['YYYY-MM-DD']()
    }
     // month ago
     public MA(){
         const d = this.Date.setMonth( this.Date.getMonth() - 1)
         return new FDate(d)['YYYY-MM-DD']()
     }
      public KV(){
         const d = this.Date.setMonth( this.Date.getMonth() - 3)
         return new FDate(d)['YYYY-MM-DD']()
     }

     public YA(){
         const d = this.Date.setFullYear( this.Date.getFullYear() - 1)
         return new FDate(d)['YYYY-MM-DD']()
     }
     static day_difference(dat1:string, dat2:string ){
        const date1:number = new Date(dat1 +'T00:00:00').getTime();
        const date2:number = new Date(dat2 +'T00:00:00').getTime();
        return (date2 - date1) / (1000 * 60 * 60 * 24)
     } 
 }