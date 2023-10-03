import{aY as M,W as p}from"./app.10579c01.js";const e=1e3,o=60*e,n=60*o,r=24*n,u="abort",d="end",c="progress",m="start",f="visibilitychange",y=M({name:"VueCountUp",props:{autoStart:{type:Boolean,default:!0},emitEvents:{type:Boolean,default:!1},interval:{type:Number,default:1e3,validator:t=>t>=0},now:{type:Function,default:()=>Date.now()},tag:{type:String,default:"span"},time:{type:Number,default:0,validator:t=>t>=0},transform:{type:Function,default:t=>t}},emits:[u,d,c,m],data(){return{counting:!1,endTime:0,totalMilliseconds:0,requestId:0}},computed:{days(){return("0"+Math.floor(this.totalMilliseconds/r)).slice(-2)},hours(){return("0"+Math.floor(this.totalMilliseconds%r/n)).slice(-2)},minutes(){return("0"+Math.floor(this.totalMilliseconds%n/o)).slice(-2)},seconds(){return("0"+Math.floor(this.totalMilliseconds%o/e)).slice(-2)},milliseconds(){return Math.floor(this.totalMilliseconds%e)},totalDays(){return this.days},totalHours(){return Math.floor(this.totalMilliseconds/n)},totalMinutes(){return Math.floor(this.totalMilliseconds/o)},totalSeconds(){return Math.floor(this.totalMilliseconds/e)}},watch:{$props:{deep:!0,immediate:!0,handler(){this.totalMilliseconds=this.now()-this.time,this.endTime=this.now()-this.time,this.autoStart&&this.start()}}},mounted(){document.addEventListener(f,this.handleVisibilityChange)},beforeUnmount(){document.removeEventListener(f,this.handleVisibilityChange),this.pause()},methods:{start(){this.counting||(this.counting=!0,this.emitEvents&&this.$emit(m),document.visibilityState==="visible"&&this.continue())},continue(){if(!this.counting)return;const t=Math.min(this.totalMilliseconds,this.interval);if(t>0){let a,i;const l=s=>{a||(a=s),i||(i=s);const h=s-a;h>=t||h+(s-i)/2>=t?this.progress():this.requestId=requestAnimationFrame(l),i=s};this.requestId=requestAnimationFrame(l)}else this.end()},pause(){cancelAnimationFrame(this.requestId)},progress(){!this.counting||(this.totalMilliseconds+=this.interval,this.emitEvents&&this.totalMilliseconds>0&&this.$emit(c,{days:this.days,hours:this.hours,minutes:this.minutes,seconds:this.seconds,milliseconds:this.milliseconds,totalDays:this.totalDays,totalHours:this.totalHours,totalMinutes:this.totalMinutes,totalSeconds:this.totalSeconds,totalMilliseconds:this.totalMilliseconds}),this.continue())},abort(){!this.counting||(this.pause(),this.counting=!1,this.emitEvents&&this.$emit(u))},end(){!this.counting||(this.pause(),this.totalMilliseconds=0,this.counting=!1,this.emitEvents&&this.$emit(d))},update(){this.counting&&(this.totalMilliseconds=Math.max(0,this.now()-this.time))},restart(){this.pause(),this.totalMilliseconds=this.now()-this.time,this.endTime=this.now()-this.time,this.counting=!1,this.start()},handleVisibilityChange(){switch(document.visibilityState){case"visible":this.update(),this.continue();break;case"hidden":this.pause();break}}},render(){return p(this.tag,this.$slots.default?[this.$slots.default(this.transform({days:this.days,hours:this.hours,minutes:this.minutes,seconds:this.seconds,milliseconds:this.milliseconds,totalDays:this.totalDays,totalHours:this.totalHours,totalMinutes:this.totalMinutes,totalSeconds:this.totalSeconds,totalMilliseconds:this.totalMilliseconds}))]:void 0)}});export{y as default};
